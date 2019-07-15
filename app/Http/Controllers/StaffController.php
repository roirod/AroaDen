<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exceptions\NoQueryResultException;
use App\Http\Controllers\Interfaces\BaseInterface;
use App\Http\Controllers\Traits\DirFilesTrait;
use Illuminate\Http\Request;
use App\Models\StaffPositionsEntries;
use App\Models\StaffPositions;
use App\Models\Treatments;
use App\Models\StaffWorks;
use App\Models\Settings;
use App\Models\Staff;
use Carbon\Carbon;
use Validator;
use Exception;
use Lang;
use DB;

class StaffController extends BaseController implements BaseInterface
{
    use DirFilesTrait;

    public function __construct(Staff $staff)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['staff'];
        $this->other_route = $this->config['routes']['patients'];        
        $this->views_folder = $this->config['routes']['staff'];
        $this->form_route = 'list';
        $this->own_dir = 'staff_dir';
        $this->files_dir = "app/".$this->own_dir;
        $this->model = $staff;

        $fields = [
            'surname' => true,
            'name' => true,
            'position' => true,
            'address' => true,
            'city' => true,
            'birth' => true,
            'dni' => true,
            'tel1' => true,
            'tel2' => true,
            'notes' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }

    /**
     *  get the index page
     *      
     *  @return view
     */
    public function index(Request $request)
    {
        $this->setPageTitle(Lang::get('aroaden.staff'));

        return parent::index($request);
    }

    /**
     *  get index page
     * 
     *  @return view       
     */
    public function ajaxIndex(Request $request)
    {
        $this->view_name = 'ajaxIndex';
        $this->view_data['request'] = $request;

        $this->setPageTitle(Lang::get('aroaden.staff'));

        return $this->loadView();
    }
 
    public function list(Request $request)
    {   
        $aColumns = [ 
            0 =>'idsta', 
            1 =>'surname_name',
            2 => 'dni',
            3 => 'tel1',
            4 => 'positions',
        ];

        $iDisplayLength = $request->input('iDisplayLength');
        $iDisplayStart = $request->input('iDisplayStart');

        $sLimit = "";
        if ( isset( $iDisplayStart ) && $iDisplayLength != '-1' )
            $sLimit = "LIMIT ".$iDisplayStart.",". $iDisplayLength;

        $iSortCol_0 = (int) $request->input('iSortCol_0');

        if ( isset( $iSortCol_0 ) ) {
            $sOrder = " ";
            $bSortable = $request->input("bSortable_$iSortCol_0");
            $sSortDir_0 = $request->input('sSortDir_0');

            if ( $bSortable == "true" )
              $sOrder = $aColumns[ $iSortCol_0 ] ." ". $sSortDir_0;

            if ( $sOrder == " " )
              $sOrder = "";
        }

        $sWhere = "";
        $sSearch = $request->input('sSearch');

        if ($sSearch != "")
            $sWhere = $this->sanitizeData($sSearch);

        $data = $this->model::FindStringOnField($sLimit, $sWhere, $sOrder);
        $countTotal = $this->model::CountAll();
        $countFiltered = $this->model::CountFindStringOnField($sWhere);
        $countFiltered = (int)$countFiltered[0]->total;

        $resultArray = [];

        foreach ($data as $key => $value) {
            $resultArray[$key][] = $value->idsta;
            $resultArray[$key][] = $value->surname_name;
            $resultArray[$key][] = $value->dni;
            $resultArray[$key][] = $value->tel1;
            $resultArray[$key][] = $value->positions;
        }

        $output = [
            "sEcho" => intval($request->input('sEcho')),
            "iTotalRecords" => $countTotal,
            "iTotalDisplayRecords" => $countFiltered,
            "aaData" => array_values($resultArray)
        ];

        $this->echoJsonOuptut($output);  
    }

    public function show(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $this->createDir($id);

        $profile_photo_dir = "$this->files_dir/$id/$this->profile_photo_dir";
        $profile_photo = url($this->getFirstJpgOnDir($profile_photo_dir));

        $staff = $this->model::FirstById($id);

        if (is_null($staff)) {
            $request->session()->flash($this->error_message_name, 'Has borrado a este profesional.');    
            return redirect($this->main_route);
        }

        $this->setPageTitle($staff->surname.', '.$staff->name);

        $staffPositionsEntries = StaffPositionsEntries::AllByStaffIdWithName($id);
        $staffPositionsEntries = array_column($staffPositionsEntries, 'name');
        $staffPositionsEntries = implode($staffPositionsEntries, ', ');

        $this->view_data['object'] = $staff;
        $this->view_data['treatments'] = StaffWorks::AllByStaffId($id);
        $this->view_data['staffPositionsEntries'] = $staffPositionsEntries;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;  
        $this->view_data['profile_photo'] = $profile_photo;
        $this->view_data['profile_photo_name'] = $this->profile_photo_name;

        return parent::show($request, $id);   
    }

    public function create(Request $request, $id = false)
    {
        $this->CheckIfStaffPositionsExists();

        $this->view_data['staffPositions'] = StaffPositions::AllOrderByName();
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::create($request);
    }

    public function store(Request $request)
    {
        $name = $this->sanitizeData($request->input('name'));
        $surname = $this->sanitizeData($request->input('surname'));
        $dni = $this->sanitizeData($request->input('dni'));
        $tel1 = $this->sanitizeData($request->input('tel1'));
        $tel2 = $this->sanitizeData($request->input('tel2'));
        $address = $this->sanitizeData($request->input('address'));
        $city = $this->sanitizeData($request->input('city'));
        $birth = $this->convertDmYToYmd($request->input('birth'));
        $birth = $this->sanitizeData($birth);
        $notes = $this->sanitizeData($request->input('notes'));
        $positions = $request->input('positions');

        if (is_null($positions)) {
            $messa = 'No has seleccionado ningún cargo.';
            $request->session()->flash($this->error_message_name, $messa);
            return redirect("/$this->main_route/create")->withInput();
        }

        $exists = $this->model::FirstByDniDeleted($dni);
   
        if ( isset($exists->dni) ) {
            $messa = 'Repetido. El dni: '.$dni.', pertenece a: '.$exists->surname.', '.$exists->name;
            $request->session()->flash($this->error_message_name, $messa);
            return redirect("/$this->main_route/create")->withInput();
        }

        $validator = Validator::make($request->all(),[
           'name' => 'required|max:111',
           'surname' => 'required|max:111',
           'dni' => 'unique:staff|max:12',
           'tel1' => 'max:11',
           'tel2' => 'max:11',
           'address' => 'max:111',
           'city' => 'max:111',
           'birth' => 'date',
           'notes' => ''
        ]);
            
        if ($validator->fails()) {
             return redirect("/$this->main_route/create")
                         ->withErrors($validator)
                         ->withInput();
        } else {

            DB::beginTransaction();

            try {

                $idsta = $this->model::insertGetId([
                    'name' => $name,
                    'surname' => $surname,
                    'dni' => $dni,
                    'tel1' => $tel1,
                    'tel2' => $tel2,
                    'address' => $address,
                    'city' => $city,
                    'birth' => $birth,
                    'notes' => $notes,
                    'created_at' => date('Y-m-d H:i:s')
                ]);

                foreach ($positions as $idstpo) {
                    StaffPositionsEntries::create([
                      'idsta' => (int)$idsta,
                      'idstpo' => (int)$idstpo
                    ]);
                }

                DB::commit();

            } catch (\Exception $e) {

                DB::rollBack();

                $request->session()->flash($this->error_message_name, $e->getMessage());  
                return redirect("/$this->main_route");

            }

            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );      
            return redirect("/$this->main_route");
        } 
    }
  
    public function edit(Request $request, $id)
    {
        $this->CheckIfStaffPositionsExists();

        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $object = $this->model::FirstById($id);

        $this->view_data['object'] = $object;
        $this->view_data['idnav'] = $id;       
        $this->view_data['id'] = $id;
        $this->view_data['form_fields'] = $this->form_fields;
        $this->view_data['staffPositions'] = StaffPositions::AllOrderByName();
        $this->view_data['staffPositionsEntries'] = StaffPositionsEntries::AllByStaffId($id);
        $this->view_data['is_create_view'] = false;

        $this->setPageTitle($object->surname.', '.$object->name);

        return parent::edit($request, $id);
    }

    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);
        $route = "/$this->main_route/$id/edit";

        $positions = $request->input('positions');

        if (is_null($positions)) {
            $messa = 'No has seleccionado ningún cargo.';
            $request->session()->flash($this->error_message_name, $messa);
            return redirect($route)->withInput();
        }

        $input_dni = $this->sanitizeData($request->input('dni'));
        $exists = $this->model::CheckIfExistsOnUpdate($id, $input_dni);

        if ( isset($exists->dni) ) {
            $msg = Lang::get('aroaden.dni_in_use', ['dni' => $exists->dni, 'surname' => $exists->surname, 'name' => $exists->name]);
            $request->session()->flash($this->error_message_name, $msg);
            return redirect($route)->withInput();
        }
              
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:111',
            'surname' => 'required|max:111',
            'dni' => 'required|max:12',
            'tel1' => 'max:11',
            'tel2' => 'max:11',
            'notes' => '',
            'address' => 'max:111',
            'city' => 'max:111',
            'birth' => 'date'
        ]);
            
        if ($validator->fails()) {
             return redirect($route)
                         ->withErrors($validator)
                         ->withInput();
        } else {

            DB::beginTransaction();

            try {

                $birth = $this->convertDmYToYmd($request->input('birth'));
                $birth = $this->sanitizeData($birth);
                         
                $staff = $this->model::FirstById($id);
               
                $staff->name = $this->sanitizeData($request->input('name'));
                $staff->surname = $this->sanitizeData($request->input('surname'));
                $staff->dni = $this->sanitizeData($request->input('dni'));
                $staff->tel1 = $this->sanitizeData($request->input('tel1'));
                $staff->tel2 = $this->sanitizeData($request->input('tel2'));
                $staff->address = $this->sanitizeData($request->input('address'));
                $staff->city = $this->sanitizeData($request->input('city'));
                $staff->birth = $birth;
                $staff->notes = $this->sanitizeData($request->input('notes'));
                $staff->updated_at = date('Y-m-d H:i:s');

                $staff->save();

                StaffPositionsEntries::where('idsta', $id)->delete();

                foreach ($positions as $idstpo) {
                    StaffPositionsEntries::create([
                      'idsta' => (int)$id,
                      'idstpo' => (int)$idstpo
                    ]);
                }

                DB::commit();

            } catch (\Exception $e) {

                DB::rollBack();

                $request->session()->flash($this->error_message_name, $e->getMessage());  
                return redirect($route);

            }

            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );      
            return redirect("/$this->main_route/$id");
        }
    }

    public function file(Request $request, $id)
    {
        return $this->loadFileView($request, $id);
    }

    private function CheckIfStaffPositionsExists()
    {
        $count = StaffPositions::CountAll();

        if ($count === 0) {
            $request->session()->flash($this->error_message_name, 'No hay cargos de personal, debes crear alguno.');    
            return redirect();
        }

        return redirect()->back();
    }

}
