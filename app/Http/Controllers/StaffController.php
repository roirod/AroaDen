<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exceptions\NoQueryResultException;
use App\Http\Controllers\Interfaces\BaseInterface;
use App\Http\Controllers\Traits\DirFilesTrait;
use App\Models\StaffPositionsEntries;
use App\Models\StaffPositions;
use Illuminate\Http\Request;
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
      'save' => true
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
      4 => 'positions'
    ];

    $iDisplayLength = $this->sanitizeData($request->input('iDisplayLength'));
    $iDisplayStart = $this->sanitizeData($request->input('iDisplayStart'));
    $sEcho = $this->sanitizeData($request->input('sEcho'));

    $sLimit = "";

    if (isset( $iDisplayStart ) && $iDisplayLength != '-1')
      $sLimit = "LIMIT ".$iDisplayStart.",". $iDisplayLength;

    $iSortCol_0 = (int)$this->sanitizeData($request->input('iSortCol_0'));

    if (isset($iSortCol_0)) {
      $sOrder = " ";
      $bSortable = $this->sanitizeData($request->input('bSortable_'.$iSortCol_0));
      $sSortDir_0 = $this->sanitizeData($request->input('sSortDir_0'));

      if ($bSortable == "true")
        $sOrder = $aColumns[$iSortCol_0] ." ". $sSortDir_0;

      if ($sOrder == " ")
        $sOrder = "";
    }

    $sWhere = "";
    $sSearch = $this->sanitizeData($request->input('sSearch'));

    if ($sSearch != "")
      $sWhere = $sSearch;

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
      "sEcho" => intval($sEcho),
      "iTotalRecords" => $countTotal,
      "iTotalDisplayRecords" => $countFiltered,
      "aaData" => $resultArray
    ];

    $this->echoJsonOuptut($output);  
  }

  public function show(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);

    $staff = $this->model::FirstById($id);

    if (is_null($staff)) {
      $request->session()->flash($this->error_message_name, 'Has borrado a este profesional.');    
      return redirect($this->main_route);
    }

    $this->createDir($id);

    $profile_photo_dir = "$this->files_dir/$id/$this->profile_photo_dir";
    $profile_photo = url($this->getFirstJpgOnDir($profile_photo_dir));

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
    $this->view_data['staffPositions'] = StaffPositions::AllOrderByName();
    $this->view_data['form_fields'] = $this->form_fields;
    $this->view_data['misc_text'] = Lang::get('aroaden.add_staff');

    return parent::create($request);
  }

  public function store(Request $request)
  {
    extract($this->sanitizeRequest($request->all()));
    
    $route = "$this->main_route/create";

    $exists = $this->model::FirstByDniDeleted($dni);

    if ( isset($exists->dni) ) {
      $messa = 'Repetido. El dni: '.$dni.', pertenece a: '.$exists->surname.', '.$exists->name;
      $request->session()->flash($this->error_message_name, $messa);
      return redirect($route)->withInput();
    }

    $birth = $this->convertDmYToYmd($birth);

    $validator = Validator::make($request->all(),[
      'name' => 'required|max:111',
      'surname' => 'required|max:111',
      'dni' => 'unique:'. $this->model->getTableName() .'|max:12',
      'tel1' => 'max:11',
      'tel2' => 'max:11',
      'address' => 'max:111',
      'city' => 'max:111',
      'birth' => 'date',
      'notes' => ''
    ]);
        
    if ($validator->fails()) {
      return redirect($route)
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

        $positions = $request->positions;

        if (is_array($positions) && count($positions) > 0) {
          foreach ($positions as $idstpo) {
            StaffPositionsEntries::create([
              'idsta' => (int)$idsta,
              'idstpo' => (int)$idstpo
            ]);
          }
        }

        DB::commit();

      } catch (\Exception $e) {

        DB::rollBack();

        $request->session()->flash($this->error_message_name, $e->getMessage());  
        return redirect($route);

      }

      $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );      
      return redirect("/$this->main_route");
    } 
  }

  public function edit(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);

    $object = $this->model::FirstById($id);

    $this->view_data['object'] = $object;
    $this->view_data['idnav'] = $id;       
    $this->view_data['id'] = $id;
    $this->view_data['form_fields'] = $this->form_fields;
    $this->view_data['staffPositions'] = StaffPositions::AllOrderByName();
    $this->view_data['staffPositionsEntries'] = StaffPositionsEntries::AllByStaffId($id);
    $this->view_data['misc_text'] = Lang::get('aroaden.edit_staff');

    $this->setPageTitle($object->surname.', '.$object->name);

    return parent::edit($request, $id);
  }

  public function update(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);
    $route = "/$this->main_route/$id/edit";

    extract($this->sanitizeRequest($request->all()));

    $exists = $this->model::CheckIfExistsOnUpdate($id, $dni);

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

        $birth = $this->convertDmYToYmd($birth);

        $staff = $this->model::FirstById($id);
       
        $staff->name = $name;
        $staff->surname = $surname;
        $staff->dni = $dni;
        $staff->tel1 = $tel1;
        $staff->tel2 = $tel2;
        $staff->address = $address;
        $staff->city = $city;
        $staff->birth = $birth;
        $staff->notes = $notes;
        $staff->updated_at = date('Y-m-d H:i:s');

        $staff->save();

        StaffPositionsEntries::where('idsta', $id)->delete();

        $positions = $request->positions;

        if (is_array($positions) && count($positions) > 0) {
          foreach ($positions as $idstpo) {
            StaffPositionsEntries::create([
              'idsta' => (int)$idsta,
              'idstpo' => (int)$idstpo
            ]);
          }
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

  /**
   *  destroy
   */
  public function destroy(Request $request, $id)
  {
    $this->misc_array['checkDestroy'] = true;

    return parent::destroy($request, $id);  
  }

}
