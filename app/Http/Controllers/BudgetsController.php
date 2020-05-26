<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Libs\PdfLib;
use Illuminate\Http\Request;
use App\Models\BudgetsText;
use App\Models\Patients;
use App\Models\Services;
use App\Models\Budgets;
use Exception;
use Lang;
use DB;

class BudgetsController extends BaseController
{

  public function __construct(Budgets $budgets, Patients $patients, Services $services)
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['budgets'];
    $this->other_route = $this->config['routes']['patients'];      
    $this->views_folder = 'budgets';
    $this->model = $budgets;
    $this->model2 = $patients;
    $this->model3 = $services;    
  }

  public function create(Request $request, $id = false)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->other_route);

    $items = $this->model3::AllOrderByName();        
    $patient = $this->model2::FirstById($id);
    $updatedA = $this->model3::getUpdated();        

    $this->view_data['items'] = $items;
    $this->view_data['idpat'] = $id;
    $this->view_data['idnav'] = $id;        
    $this->view_data['object'] = $patient;
    $this->view_data['created_at'] = date('Y-m-d H:i:s'); 
    $this->view_data['uniqid'] = uniqid();
    $this->view_data['updatedA'] = $updatedA;

    $this->setPageTitle($patient->surname.', '.$patient->name);

    return parent::create($request, $id);
  }

  public function store(Request $request)
  {
    extract($this->sanitizeRequest($request->all()));

    $this->request = $request;

    $data = [];
    $data['error'] = false;

    try {

      if (!(bool)$onUpdate)
        $this->checkData();

      if (empty($itemsArray) && (bool)$onUpdate == false) {

        throw new Exception(Lang::get('aroaden.no_add_treatments'));

      } elseif (empty($itemsArray) && (bool)$onUpdate && (bool)$onUpdateDeleteAll) {

        $this->delete($uniqid, true);

      } elseif (is_array($itemsArray) && (bool)$onUpdate) {

        $this->delete($uniqid);
        $this->save();
        BudgetsText::where('uniqid', $uniqid)->update(['text' => $notes]);

      } elseif (is_array($itemsArray) && (bool)$onUpdate == false) {

        $this->save();

      }

    } catch (Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    return $this->echoJsonOuptut($data);
  }

  private function checkData()
  {
    $updatedA = $this->request->updatedA;
    $updatedNowA = $this->model3::getUpdated();        
    $arraysNotEqual = ($updatedA != $updatedNowA);

    if ($arraysNotEqual)
      throw new Exception(Lang::get('aroaden.error_data_change'));
  }

  private function save()
  {
    try {

      extract($this->sanitizeRequest($this->request->all()));

      foreach ($itemsArray as $key => $val) {

        $service = $this->model3::FirstById($val["idser"]);        

        $this->model::create([
          'idpat' => $idpat,
          'idser' => $val["idser"],
          'units' => $val["units"],
          'uniqid' => $uniqid,
          'price' => $service->price,
          'tax' => $service->tax,
          'created_at' => $created_at           
        ]);
      }

    } catch (Exception $e) {

      throw $e;

    }
  }

  public function show(Request $request, $id = false)
  {
    $id = $this->sanitizeData($id);   
    $this->redirectIfIdIsNull($id, $this->other_route);

    $items = $this->model::AllById($id);
    $patient = $this->model2::FirstById($id);
    $this->setPageTitle($patient->surname.', '.$patient->name);

    $this->view_data['items'] = $items;
    $this->view_data['idpat'] = $id;
    $this->view_data['idnav'] = $id;        
    $this->view_data['object'] = $patient;

    return parent::show($request, $id);
  }

  public function edit(Request $request, $uniqid)
  {
    $uniqid = $this->sanitizeData($uniqid);

    try {

      $items = $this->model::AllByCode($uniqid);
      $arr_items = $items->toArray();
      $idpat = $arr_items[0]->idpat;

      $budgetstext = BudgetsText::where('uniqid', $uniqid)->first();

      if (is_null($budgetstext)) {
        BudgetsText::create([
          'idpat' => $idpat,
          'uniqid' => $uniqid
        ]);

        return redirect("/$this->main_route/$uniqid/edit");
      }

      $patient = $this->model2::FirstById($idpat);

    } catch (Exception $e) {

      $request->session()->flash($this->error_message_name, $e->getMessage()); 
      return redirect("/$this->main_route/$idpat");

    }
    
    $this->setPageTitle($patient->surname.', '.$patient->name);

    $this->view_data['request'] = $request;
    $this->view_data['items'] = $items;
    $this->view_data['budgetstext'] = $budgetstext;
    $this->view_data['uniqid'] = $uniqid;
    $this->view_data['created_at'] = $arr_items[0]->created_at;
    $this->view_data['idpat'] = $idpat;
    $this->view_data['idnav'] = $idpat;     
    $this->view_data['object'] = $patient;
    
    $this->view_name = 'edit';

    return $this->loadView();
  }

  public function downloadPdf(Request $request, $uniqid)
  {
    $uniqid = $this->sanitizeData($uniqid);

    $this->commonMode($uniqid);

    $patient = $this->view_data['patient'];
    $created_at = $this->view_data['created_at'];
    $created_at = $this->convertYmdToDmY($created_at);
    $pdfName = "$patient->name $patient->surname $created_at.pdf";
    $pdfData = $this->returnViewString();
    $pdfObj = new PdfLib($pdfData, $pdfName);

    return $pdfObj->downloadPdf();
  }

  private function commonMode($uniqid)
  {  
    $items = $this->model::AllByCode($uniqid);
    $idpat = $items[0]->idpat;
    $patient = $this->model2::FirstById($idpat);
    $created_at = $items[0]->created_at;
    $budgetstext = BudgetsText::where('uniqid', $uniqid)->first();
    $company = $this->getSettings();

    $this->views_folder .= '.includes';
    $this->view_name = 'pdf';

    $this->view_data['patient'] = $patient;
    $this->view_data['text'] = $budgetstext->text;
    $this->view_data['items'] = $items;
    $this->view_data['uniqid'] = $uniqid;
    $this->view_data['created_at'] = $created_at;
    $this->view_data['company'] = $company;
    $this->view_data['downloadPdf'] = $this->downloadPdf;    
  }

  public function delBudget(Request $request)
  {
    $uniqid = $request->uniqid;
    $idpat = $request->idpat;

    try {

      $this->delete($uniqid, true);

    } catch (\Exception $e) {

      $request->session()->flash($this->error_message_name, $e->getMessage()); 
      return redirect("/$this->main_route/$idpat");

    }
    
    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message'));

    return redirect("/$this->main_route/$idpat");
  }

  private function delete($uniqid, $deleteAll = false)
  {
    DB::beginTransaction();

    try {

      $this->model::where('uniqid', $uniqid)->delete();

      if ($deleteAll)
        BudgetsText::where('uniqid', $uniqid)->delete();

      DB::commit();

    } catch (\Exception $e) {

      DB::rollBack();

      throw $e;

    }
  }

}
