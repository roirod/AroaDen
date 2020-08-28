<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\BaseInterface;
use Illuminate\Http\Request;
use App\Models\Appointments;
use App\Models\Patients;
use Validator;
use Exception;
use DateTime;
use Lang;
use DB;

class AppointmentsController extends BaseController implements BaseInterface
{
  public function __construct(Appointments $appointments, Patients $patients)
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['appointments'];
    $this->other_route = $this->config['routes']['patients'];      
    $this->views_folder = $this->config['routes']['appointments'];        
    $this->model = $appointments;        
    $this->model2 = $patients; 
    $this->form_route = 'list';
   
    $this->view_data["load_js"]["datetimepicker"] = true;
   
    $fields = [
      'hour' => true,
      'day' => true,
      'notes' => true,
      'save' => true
    ];

    $this->form_fields = array_replace($this->form_fields, $fields);
  }
  
  public function index(Request $request)
  {
    $this->view_data["load_js"]["datatables"] = true;

    return parent::index($request);
  }
  
  public function list(Request $request)
  {
    $aColumns = [
      0 => 'idpat', 
      1 => 'surname_name',
      2 => 'day',
      3 => 'hour',
      4 => 'notes'
    ];

    $iDisplayLength = $this->sanitizeData($request->input('iDisplayLength'));
    $iDisplayStart = $this->sanitizeData($request->input('iDisplayStart'));
    $sEcho = $this->sanitizeData($request->input('sEcho'));

    $sLimit = "";

    if (isset( $iDisplayStart ) && $iDisplayLength != '-1') {
      $soffset = $iDisplayStart;
      $sLimit = $iDisplayLength;
    }

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

    $sWhere = $this->sanitizeData($request->input('sSearch'));

    $data = $this->model->FindStringOnField($soffset, $sLimit, $sWhere, $sOrder);
    $countTotal = $this->model->CountAll();
    $countFiltered = $this->model::CountFindStringOnField($sWhere);
    $countFiltered = (int)$countFiltered[0]->total;

    $resultArray = [];

    foreach ($data as $key => $value) {
      $resultArray[$key][] = $value->idpat;
      $resultArray[$key][] = $value->surname_name;
      $resultArray[$key][] = date('d-m-Y', strtotime($value->day));
      $resultArray[$key][] = substr($value->hour, 0, -3);
      $resultArray[$key][] = $value->notes;
    }

    $output = [
      "sEcho" => intval($sEcho),
      "iTotalRecords" => $countTotal,
      "iTotalDisplayRecords" => $countFiltered,
      "aaData" => $resultArray
    ];

    $this->echoJsonOuptut($output);
  }

  public function create(Request $request, $id = false)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->other_route);

    $object = $this->model2->FirstById($id);
    $this->setPageTitle($object->surname.', '.$object->name);

    $this->view_data["legend"] = Lang::get('aroaden.create_appointment');
    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $object->idpat;
    $this->view_data['object'] = $object;

    return parent::create($request, $id);  
  }

  public function store(Request $request)
  {
    $data = [];
    $data['error'] = false;

    $this->validate($request, [
      'idpat' => $this->config['validates']['idpat'][0],
      'day' => $this->config['validates']['day'][0],
      'hour' => $this->config['validates']['hour'][0],
      'notes' => $this->config['validates']['notes'][0]
    ]);

    try {

      extract($this->sanitizeRequest($request->all()));

      $data['redirectTo'] = "/$this->other_route/$idpat";

      $day = $this->convertDmYToYmd($day);

      $this->model::create([
        'idpat' => $idpat,
        'hour' => $hour,
        'day' => $day,
        'notes' => $notes
      ]);

    } catch (Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
  }

  public function edit(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->other_route);
    $object = $this->model::FirstById($id);

    $this->autofocus = 'hour';
    $this->view_data['object'] = $object;
    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $object->idpat;
    $this->view_data['name'] = $object->name;
    $this->view_data['surname'] = $object->surname;
    $this->view_data["legend"] = Lang::get('aroaden.edit_appointment');

    $this->setPageTitle($object->surname.', '.$object->name);

    return parent::edit($request, $id);
  }

  public function update(Request $request, $id)
  {
    $id = $this->sanitizeData($id);

    $this->validate($request, [
      'day' => $this->config['validates']['day'][0],
      'hour' => $this->config['validates']['hour'][0],
      'notes' => $this->config['validates']['notes'][0]
    ]);
      
    $data = [];
    $data['error'] = false;

    try {

      $exists = $this->model::CheckIfIdExists($id);

      if (!$exists)
        throw new Exception(Lang::get('aroaden.error'));
          
      $object = $this->model::find($id);

      $data['redirectTo'] = "/$this->other_route/$object->idpat";

      extract($this->sanitizeRequest($request->all()));

      $day = $this->convertDmYToYmd($day);

      $object->hour = $hour;
      $object->day = $day;
      $object->notes = $notes;
      
      $object->save();

    } catch (Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
  }

  public function destroy(Request $request, $id)
  {      
    return parent::destroy($request, $id);        
  }

}
