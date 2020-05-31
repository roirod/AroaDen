<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Invoices\Rectification;
use App\Http\Controllers\Invoices\Complete;
use App\Http\Controllers\Libs\PdfLib;
use Illuminate\Http\Request;
use App\Models\InvoiceLines;
use App\Models\Treatments;
use App\Models\Patients;
use App\Models\Invoices;
use Exception;
use Lang;
use DB;

class InvoicesController extends BaseController
{
  private $invoice_types = [
    'Complete' => 'complete',
    //'Rectification' => 'rectification'
  ];

  public function __construct(Invoices $invoices)
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['invoices'];
    $this->other_route = $this->config['routes']['patients'];             
    $this->views_folder = $this->config['routes']['invoices'];        
    $this->model = $invoices;
  }

  public function index(Request $request)
  {   
    $this->setPageTitle(Lang::get('aroaden.invoices'));

    return parent::index($request);
  }

  public function list(Request $request)
  {
    $aColumns = [ 
      0 =>'pat.idpat', 
      1 =>'surname_name',
      2 => 'inv.number',
      3 => 'inv.serial',
      4 => 'inv.type',
      5 => 'inv.exp_date'
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
      $resultArray[$key][] = "$this->main_route/$value->idpat";
      $resultArray[$key][] = $value->surname_name;
      $resultArray[$key][] = $value->number;
      $resultArray[$key][] = $value->serial;
      $resultArray[$key][] = Lang::get('aroaden.'.$value->type);
      $resultArray[$key][] = $this->convertYmdToDmY($value->exp_date);
    }

    $output = [
      "sEcho" => intval($sEcho),
      "iTotalRecords" => $countTotal,
      "iTotalDisplayRecords" => $countFiltered,
      "aaData" => $resultArray
    ];

    $this->echoJsonOuptut($output);  
  }

  public function show(Request $request, $idpat = false)
  {     
    $idpat = $this->sanitizeData($idpat); 
    $this->redirectIfIdIsNull($idpat, $this->other_route);

    $object = Patients::FirstById($idpat);
    $items = $this->model->AllByPatient($idpat);   

    $this->setPageTitle($object->surname.', '.$object->name);

    $this->view_data['request'] = $request;
    $this->view_data['object'] = $object;
    $this->view_data['items'] = $items;
    $this->view_data['invoice_types'] = $this->invoice_types;
    $this->view_data['default_type'] = $this->invoice_types['Complete'];
    $this->view_data['idpat'] = $idpat;
    $this->view_data['idnav'] = $idpat;        

    return parent::show($request, $idpat);
  }

  public function invoicesFactory(Request $request)
  {
    $idpat = $this->sanitizeData($request->idpat);
    $type = $this->sanitizeData($request->type);

    switch ($type) {
      case $this->invoice_types['Complete']:
        $object = new Complete($this->model);
        return $object->createInvoice($request, $idpat);
        break;

      case $this->invoice_types['Rectification']:
        $object = new Rectification($this->model);
        return $object->createInvoice($request, $idpat);
        break;

      default:
        $request->session()->flash($this->error_message_name, Lang::get('aroaden.error_message'));    
        return redirect("$this->main_route/$idpat");
        break;
    }
  }

  public function create(Request $request, $id = false)
  {    
    $idpat = $request->idpat;
    $type = $request->type;

    $patient = Patients::FirstById($idpat);
    $invoiceLines = $patient->invoiceLines->pluck('idtre')->toArray();
    $items = Treatments::PaidByPatientId($idpat);
    $updatedA = Treatments::getUpdatedPaidByPatientId($idpat);
    $company = $this->getSettings();

    $this->setPageTitle($patient->surname.', '.$patient->name);

    $this->view_data['request'] = $request;
    $this->view_data['object'] = $patient;
    $this->view_data['invoiceLines'] = $invoiceLines;
    $this->view_data['items'] = $items;
    $this->view_data['type'] = $type;
    $this->view_data['updatedA'] = $updatedA;
    $this->view_data['company'] = $company;   
    $this->view_data['idpat'] = $idpat;
    $this->view_data['idnav'] = $idpat;        

    return parent::create($request, $idpat);
  }

  public function preview(Request $request)
  {
    $data = [];
    $data['error'] = false;
    $data['reload'] = false;

    $this->downloadPdf = false;
    $this->request = $request;

    try {

      $this->checkData();
      $this->prepareData();

      $data['htmlContent'] = $this->returnViewString();

    } catch (Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();
      $data['reload'] = true;

    }

    return $this->echoJsonOuptut($data);
  }

  public function store(Request $request)
  {
    $data = [];
    $data['error'] = false;
    $data['reload'] = false;

    $this->request = $request;

    try {

      $this->checkData();
      $this->saveData();

    } catch (Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();
      $data['reload'] = true;

    }

    return $this->echoJsonOuptut($data);
  }

  public function downloadPdf($number)
  {
    $number = $this->sanitizeData($number);

    $this->view_data['number'] = $number;

    $this->prepareData();

    $object = $this->view_data['object'];
    $pdfName = "$object->name $object->surname $number.pdf";
    $pdfData = $this->returnViewString();
    $pdfObj = new PdfLib($pdfData, $pdfName);

    return $pdfObj->downloadPdf();
  }

  private function checkData()
  {
    $idpat = $this->request->idpat;
    $updatedA = $this->request->updatedA;
    $updatedNowA = Treatments::getUpdatedPaidByPatientId($idpat);

    $arraysNotEqual = ($updatedA != $updatedNowA);

    if ($arraysNotEqual)
      throw new Exception(Lang::get('aroaden.error_data_change'));
  }

  private function prepareData()
  {
    if ($this->downloadPdf) {

      $number = $this->view_data['number'];
      $invoice = $this->model->FirstById($number);
      $items = InvoiceLines::GetByNumber($number);
      $object = Patients::FirstById($invoice->idpat);

      $type = $invoice->type;
      $serial = $invoice->serial;
      $place = $invoice->place;
      $exp_date = $this->convertYmdToDmY($invoice->exp_date);
      $notes = $invoice->notes;

      $this->view_data['idpat'] = $invoice->idpat;
      $this->view_data['idnav'] = $invoice->idpat;

    } else {

      extract($this->sanitizeRequest($this->request->all()));

      $idtreArray = $this->request->idtreArray;

      $object = Patients::FirstById($idpat);
      $number = $this->model->GetNextNumber();

      $items = [];

      foreach ($idtreArray as $key => $idtre) {
        $items[] = Treatments::FirstById($idtre);
      }

      $this->view_data['number'] = $number;
    }

    $company = $this->getSettings();

    $this->view_name = 'doc';
    $this->views_folder .= '.includes';

    $this->view_data['items'] = $items;
    $this->view_data['object'] = $object;
    $this->view_data['company'] = $company;
    $this->view_data['type'] = $type;
    $this->view_data['serial'] = $serial;
    $this->view_data['place'] = $place;
    $this->view_data['exp_date'] = $exp_date;
    $this->view_data['notes'] = $notes;
    $this->view_data['has_date'] = true;
    $this->view_data['downloadPdf'] = $this->downloadPdf;
  }

  private function saveData()
  {
    extract($this->sanitizeRequest($this->request->all()));

    $idtreArray = $this->request->idtreArray;
    $exp_date = $this->convertDmYToYmd($exp_date);
    $number = $this->model->GetNextNumber();

    $dataA = [];
    $dataA['number'] = $number;    
    $dataA['serial'] = $serial;
    $dataA['idpat'] = $idpat;
    $dataA['type'] = $type;
    $dataA['exp_date'] = $exp_date;
    $dataA['place'] = $place;
    $dataA['notes'] = $notes;

    if (isset($parent_num))
      $dataA['parent_num'] = $parent_num;

    if (isset($parent_serial))
      $dataA['parent_serial'] = $parent_serial;

    DB::beginTransaction();

    try {

      $this->model::create($dataA);

      foreach ($idtreArray as $key => $idtre) {
        $item = Treatments::FirstById($idtre);

        InvoiceLines::create([
          'number' => $number,
          'idtre' => $item->idtre,
          'idser' => $item->idser,
          'price' => $item->price,
          'units' => $item->units,
          'paid' => $item->paid,
          'tax' => $item->tax,
          'day' => $item->day
        ]);
      }

      DB::commit();

    } catch (Exception $e) {

      DB::rollBack();

      throw $e;

    }
  }

  public function edit(Request $request, $number)
  {
    $this->view_data['number'] = $this->sanitizeData($number);

    $this->prepareData();

    $this->views_folder = $this->config['routes']['invoices'];        

    return parent::edit($request, $number);  
  }

  public function update(Request $request, $numberold)
  {
    $numberold = $this->sanitizeData($numberold);    
    $data = [];
    $data['error'] = false;
    $data['reload'] = false;

    extract($this->sanitizeRequest($request->all()));

    try {

      if ((int)$numberold !== (int)$number) {
        $exists = $this->model->FirstById($number);

        if ( isset($exists->number) )
          throw new Exception(Lang::get('aroaden.number_in_use', ['number' => $number]));
      }

      $object = $this->model::find($numberold);

      if ((int)$numberold !== (int)$number)
        $object->number = $number;    

      $object->serial = $serial;    
      $object->place = $place;
      $object->exp_date = $this->convertDmYToYmd($exp_date);
      $object->notes = $notes;
      $object->save();

    } catch (Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();
      $data['reload'] = true;

    }

    return $this->echoJsonOuptut($data);
  }

  public function destroy(Request $request, $id)
  {      
    return parent::destroy($request, $id);        
  }

}



