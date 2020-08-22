<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\BaseInterface;
use App\Http\Controllers\Traits\UserTrait;
use Illuminate\Http\Request;
use App\Models\Appointments;
use App\Models\Treatments;
use App\Models\Patients;
use App\Models\Invoices;
use App\Models\Budgets;
use App\Models\Record;
use Carbon\Carbon;
use Validator;
use Exception;
use Storage;
use Image;
use Lang;
use Html;
use DB;

class PatientsController extends BaseController implements BaseInterface
{
  use UserTrait;

  /**
   * @var string $odontogram_dir  odontogram_dir
   */
  private $odontogram_dir = '.odontogram_dir';

  /**
   * @var string $odontogram  odontogram
   */
  private $odontogram = 'odontogram';

  /**
   * 
   */
  public function __construct(Patients $patients)
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['patients'];    
    $this->views_folder = $this->config['routes']['patients'];        
    $this->model = $patients;
    $this->form_route = 'list';
    $this->own_dir = 'patients_dir';
    $this->files_dir = "app/".$this->own_dir;
    $this->has_odontogram = true;
    $this->user_type = $this->config['routes']['patients'];

    $fields = [
      'surname' => true,
      'name' => true,
      'address' => true,
      'city' => true,
      'birth' => true,
      'dni' => true,
      'sex' => true,            
      'tel1' => true,
      'tel2' => true,
      'tel3' => true,
      'notes' => true,
      'save' => true
    ];

    $this->form_fields = array_replace($this->form_fields, $fields);
  }

  public function index(Request $request)
  {   
    $this->setPageTitle(Lang::get('aroaden.patients'));

    $this->view_data["load_js"]["datatables"] = true;

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

    $this->setPageTitle(Lang::get('aroaden.patients'));

    return $this->loadView();
  }

  public function list(Request $request)
  {
    $aColumns = [ 
      0 => 'idpat', 
      1 => 'surname_name',
      2 => 'dni',
      3 => 'tel1',
      4 => 'city'
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
    $countTotal = $this->model->CountAll();
    $countFiltered = $this->model::CountFindStringOnField($sWhere);
    $countFiltered = (int)$countFiltered[0]->total;

    $resultArray = [];

    foreach ($data as $key => $value) {
      $resultArray[$key][] = $value->idpat;
      $resultArray[$key][] = $value->surname_name;
      $resultArray[$key][] = $value->dni;
      $resultArray[$key][] = $value->tel1;
      $resultArray[$key][] = $value->city;
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

    $patient = $this->model::FirstById($id);

    if (empty($patient->idpat)) {
      $request->session()->flash($this->error_message_name, Lang::get('aroaden.no_patient_or_deleted'));    
      return redirect($this->main_route);
    }

    $this->createDir($id, true);

    $appointments = $patient->appointments;
    $treatments = Treatments::AllByPatientId($id);
    $invoiceLines = $patient->invoiceLines->pluck('idtre')->toArray();
    $treatments_sum = Treatments::SumByPatientId($id);
    $birth = trim($patient->birth);
    $profile_photo_dir = "$this->files_dir/$id/$this->profile_photo_dir";
    $profile_photo = url($this->getFirstJpgOnDir($profile_photo_dir));
    $age = 0;

    if (isset($birth)) {
      $date = explode("-", $birth, 3);          
      $age = Carbon::createFromDate($date[0], $date[1], $date[2])->age;
    }

    $this->setPageTitle($patient->surname.', '.$patient->name);

    $this->view_data['object'] = $patient;
    $this->view_data['appointments'] = $appointments;
    $this->view_data['treatments'] = $treatments;
    $this->view_data['invoiceLines'] = $invoiceLines;    
    $this->view_data['treatments_sum'] = $treatments_sum;
    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $id;        
    $this->view_data['age'] = $age;
    $this->view_data['profile_photo'] = $profile_photo;
    $this->view_data['profile_photo_name'] = $this->profile_photo_name;

    return parent::show($request, $id);
  }

  public function create(Request $request, $id = false)
  {
    $this->setPageTitle(Lang::get('aroaden.create_patient'));

    return parent::create($request);
  }

  public function store(Request $request)
  {
    extract($this->sanitizeRequest($request->all()));

    $route = "$this->main_route/create";

    DB::beginTransaction();

    try {

      $exists = $this->model::FirstByDni($dni);

      if ( isset($exists->dni) ) {
        $msg = Lang::get('aroaden.dni_in_use', ['dni' => $dni, 'surname' => $exists->surname, 'name' => $exists->name]);
        throw new Exception($msg);
      }

      $birth = $this->convertDmYToYmd($birth);

      $insertedId = $this->model::insertGetId([
        'name' => $name,
        'surname' => $surname,
        'dni' => $dni,
        'tel1' => $tel1,
        'tel2' => $tel2,
        'tel3' => $tel3,
        'sex' => $sex,
        'address' => $address,
        'city' => $city,
        'birth' => $birth,
        'notes' => $notes,
        'created_at' => date('Y-m-d H:i:s')
      ]);

      Record::create(['idpat' => $insertedId]);

      DB::commit();

    } catch (\Exception $e) {

      DB::rollBack();

      $request->session()->flash($this->error_message_name, $e->getMessage()); 
      return redirect($route)->withInput();

    }

    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message'));
    return redirect("$this->main_route/$insertedId"); 
  }

  public function edit(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);

    $object = $this->model::FirstById($id);
    $this->setPageTitle($object->surname.', '.$object->name);

    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $id;        
    $this->view_data['object'] = $object;

    return parent::edit($request, $id);
  }

  public function update(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);
    $route = "$this->main_route/$id/edit";

    extract($this->sanitizeRequest($request->all()));

    try {

      $patient = $this->model::FirstById($id);
      $exists = $this->model::CheckIfDniExistsOnUpdate($id, $dni);

      if ( isset($exists->dni) ) {
        $msg = Lang::get('aroaden.dni_in_use', ['dni' => $exists->dni, 'surname' => $exists->surname, 'name' => $exists->name]);
        throw new Exception($msg);
      }

      $birth = $this->convertDmYToYmd($birth);

      $patient->name = $name;
      $patient->surname = $surname;
      $patient->birth = $birth;
      $patient->dni = $dni;
      $patient->tel1 = $tel1;
      $patient->tel2 = $tel2;
      $patient->tel3 = $tel3;
      $patient->sex = $sex;
      $patient->address = $address;
      $patient->city = $city;
      $patient->notes = $notes;
      $patient->updated_at = date('Y-m-d H:i:s');
      $patient->save();

    } catch (\Exception $e) {

      $request->session()->flash($this->error_message_name, $e->getMessage());  
      return redirect($route)->withInput();

    }

    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
    return redirect("$this->main_route/$id");
  }

  public function record(Request $request, $id)
  {  
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);

    $this->view_name = 'record';
    
    $record = Record::find($id);

    if (is_null($record)) {
      Record::create([
        'idpat' => $id
      ]);

      return redirect("/$this->main_route/$id/$this->view_name");
    }

    $object = $this->model::FirstById($id);
    $this->setPageTitle($object->surname.', '.$object->name);

    $this->form_route = 'editRecord';

    $this->view_data['request'] = $request;
    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $id;
    $this->view_data['record'] = $record;
    $this->view_data['object'] = $object;

    return $this->loadView();
  } 

  public function editRecord(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);

    $record = Record::find($id);
    $object = $this->model::FirstById($id);
    $this->setPageTitle($object->surname.', '.$object->name);

    $this->form_route = 'saveRecord';

    $this->view_data['request'] = $request;
    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $id;
    $this->view_data['record'] = $record;
    $this->view_data['object'] = $object;

    $this->view_name = 'editRecord';

    return $this->loadView();
  }

  public function saveRecord(Request $request, $id)
  {   
    $id = $this->sanitizeData($id);     
    $this->redirectIfIdIsNull($id, $this->main_route);

    $this->view_name = 'record';

    $record = Record::find($id);

    $record->medical_record = $this->sanitizeData($request->input('medical_record'));   
    $record->diseases = $this->sanitizeData($request->input('diseases'));   
    $record->medicines = $this->sanitizeData($request->input('medicines'));   
    $record->allergies = $this->sanitizeData($request->input('allergies'));   
    
    $record->save();

    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
    return redirect("$this->main_route/$id/$this->view_name");
  }  

  public function file(Request $request, $id)
  {
    return $this->loadFileView($request, $id);
  }
 
  public function odontogram(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);

    $dir = "$this->files_dir/$id/$this->odontogram_dir";
    $odontogram = url($this->getFirstJpgOnDir($dir));

    $object = $this->model::FirstById($id);
    $this->setPageTitle($object->surname.', '.$object->name); 

    $this->view_data['request'] = $request;
    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $id;
    $this->view_data['odontogram'] = $odontogram;
    $this->view_data['object'] = $object;
    
    $this->view_name = 'odontogram';
    
    return $this->loadView();
  }

  public function uploadOdontogram(Request $request, $id)
  {   
    $file = $request->file('file');
    $id = $this->sanitizeData($id);
    $dir = "$this->files_dir/$id/$this->odontogram_dir";
    $fsDir = storage_path($dir);
    $output = [];

    try {
         
      $this->checkIfFileIsValid($file);

      $extension = $file->getClientOriginalExtension();

      if ($extension == $this->default_img_type) {

        $this->deleteAllFilesOnDir($dir);

        $file_name = $this->odontogram.'_'.uniqid().'.'.$this->default_img_type;

        $this->misc_array['file'] = $file;
        $this->misc_array['save_path'] = $fsDir;
        $this->misc_array['fsFilename'] = $file_name;

        $this->saveFileOnDisk();

      } else {

        throw new Exception(Lang::get('aroaden.no_jpg_img'));

      }

      $output['odontogram'] = url($this->getFirstJpgOnDir($dir));

    } catch (Exception $e) {
     
      $output['error'] = true;
      $output['msg'] = $e->getMessage();

    } 

    $this->echoJsonOuptut($output);
  }   

  public function downloadOdontogram(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->main_route);

    $dir = "$this->files_dir/$id/$this->odontogram_dir";
    $odontogram = $this->getFirstJpgOnDir($dir);
    $odontogram = public_path($odontogram);

    return response()->download($odontogram);
  }    
  
  public function resetOdontogram(Request $request, $id)
  {  
    $id = $this->sanitizeData($id);
    $dir = "$this->files_dir/$id/$this->odontogram_dir";
    $this->view_name = 'odontogram';

    try {
         
      $this->deleteAllFilesOnDir($dir);

      $dir = "/$this->own_dir/$id";
      $odontogram = "/$dir/$this->odontogram_dir/$this->odontogram".'_'.uniqid().'.'.$this->default_img_type;
      $default_odontogram = "$this->img_folder/$this->odontogram".'.'.$this->default_img_type;

      Storage::copy($default_odontogram, $odontogram);
        
      return redirect("/$this->main_route/$id/$this->view_name");

    } catch (Exception $e) {

      $request->session()->flash($this->error_message_name, $e->getMessage());
      return redirect("/$this->main_route/$id/$this->view_name");

    }   
  }

  public function budgets(Request $request, $id)
  {
    $id = $this->sanitizeData($id);    
    $this->redirectIfIdIsNull($id, $this->main_route);

    $budgets = Budgets::AllById($id);
    $object = $this->model::FirstById($id);
    $this->setPageTitle($object->surname.', '.$object->name);

    $this->view_data['request'] = $request;
    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $id;
    $this->view_data['budgets'] = $budgets;

    $this->view_name = 'budgets';

    return $this->loadView();
  }    

}