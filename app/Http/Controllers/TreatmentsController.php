<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatments;
use App\Models\StaffWorks;
use App\Models\Patients;
use App\Models\Services;
use App\Models\Staff;
use Validator;
use Exception;
use Lang;
use View;
use DB;

class TreatmentsController extends BaseController
{

  public function __construct(Treatments $treatments)
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['treatments'];
    $this->other_route = $this->config['routes']['patients'];             
    $this->views_folder = $this->config['routes']['treatments'];
    $this->model = $treatments;
    $this->form_route = 'select';   

    $fields = [
      'units' => true,
      'paid' => true,
      'day' => true,
      'staff' => true,
      'save' => true
    ];

    $this->form_fields = array_replace($this->form_fields, $fields);
  }   

  public function create(Request $request, $id = false)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->other_route);
    $object = Patients::FirstById($id);

    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $object->idpat;
    $this->view_data['object'] = $object;
    $this->view_data['services'] = Services::AllOrderByName();
    $this->view_data['staff'] = Staff::AllOrderBySurnameNoPagination();

    $this->setPageTitle($object->surname.', '.$object->name);

    return parent::create($request, $id);
  }

  public function select(Request $request)
  {
    $id = $this->sanitizeData($request->input('idser_select'));

    $service = Services::FirstById($id);

    $data = [];
    $data['idser'] = $id;
    $data['name'] = html_entity_decode($service->name);        
    $data['price'] = $service->price;
    $data['tax'] = $service->tax;   
    
    $this->echoJsonOuptut($data);
  }

  public function store(Request $request)
  {
    extract($this->sanitizeRequest($request->all()));

    $staff = $request->staff;
    $data['error'] = false;

    DB::beginTransaction();

    try {

      $service = Services::FirstById($idser);     
      $price = $service->price;
      $tax = $service->tax;
      $day = $this->convertDmYToYmd($day);

      if ($this->checkIfPaidIsHigher($units, $price, $paid))
        throw new Exception(Lang::get('aroaden.paid_is_higher'));

      $idtre = Treatments::insertGetId([
        'idpat' => $idpat,
        'idser' => $idser,
        'price' => $price,
        'units' => $units,
        'paid' => $paid,
        'day' => $day,
        'tax' => $tax,
        'created_at' => date('Y-m-d H:i:s')
      ]);

      if (is_array($staff) && count($staff) > 0) {
        foreach ($staff as $idsta) {
          StaffWorks::create([
            'idsta' => $idsta,
            'idtre' => $idtre
          ]);
        }
      }

      DB::commit();

    } catch (\Exception $e) {

      DB::rollBack();

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
  }

  public function edit(Request $request, $id)
  {
    $id = $this->sanitizeData($id);   
    $this->redirectIfIdIsNull($id, $this->other_route);

    $treatment = Treatments::FirstById($id);
    $object = Patients::FirstById($treatment->idpat);

    $this->autofocus = 'units';

    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $object->idpat;        
    $this->view_data['object'] = $object;
    $this->view_data['treatment'] = $treatment;
    $this->view_data['staff_works'] = StaffWorks::AllById($id)->toArray();
    $this->view_data['staff'] = Staff::AllOrderBySurnameNoPagination();
    
    $this->setPageTitle($object->surname.', '.$object->name);

    return parent::edit($request, $id);
  }

  public function update(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $data['error'] = false;

    extract($this->sanitizeRequest($request->all()));

    $day = $this->convertDmYToYmd($day);
    $staff = $request->staff;

    DB::beginTransaction();

    try {

      $treatment = Treatments::find($id);

      if ($this->checkIfPaidIsHigher($units, $price, $paid))
        throw new Exception(Lang::get('aroaden.paid_is_higher'));

      $treatment->units = $units;
      $treatment->paid = $paid;
      $treatment->day = $day;
      $treatment->updated_at = date('Y-m-d H:i:s');
      $treatment->save();

      StaffWorks::where('idtre', $id)->delete();

      if (is_array($staff) && count($staff) > 0) {
        foreach ($staff as $idsta) {
          StaffWorks::create([
            'idsta' => $idsta,
            'idtre' => $id
          ]);
        }
      }

      DB::commit();

    } catch (\Exception $e) {

      DB::rollBack();

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);  
  }

  public function renderPaymentsTable($idpat)
  {
    $this->views_folder = 'patients.includes';
    $this->view_name = 'paymentsTable';

    $this->view_data['treatments_sum'] = $this->model::SumByPatientId($idpat);

    return $this->returnViewString();
  }

  public function destroy(Request $request, $id)
  {               
    $id = $this->sanitizeData($id);
    $object = $this->model::find($id);
    $data['error'] = false;

    DB::beginTransaction();

    try {

      $this->model::destroy($id);
      
      StaffWorks::where('idtre', $id)->delete();

      DB::commit();

    } catch (Exception $e) {

      DB::rollBack();

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $data['htmlContent'] = $this->renderPaymentsTable($object->idpat);

    $this->echoJsonOuptut($data);
  }
}
