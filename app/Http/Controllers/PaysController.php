<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patients;
use Lang;

class PaysController extends BaseController
{
  public function __construct(Patients $patients)
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['pays'];
    $this->other_route = $this->config['routes']['patients'];
    $this->views_folder = $this->config['routes']['pays'];
    $this->form_route = 'list';        
    $this->model = $patients;      
  }

  public function index(Request $request)
  {
    $this->setPageTitle(Lang::get('aroaden.payments'));

    return parent::index($request);
  }

  public function list(Request $request)
  {
    $aColumns = [ 
        0 =>'idpat', 
        1 =>'surname_name',
        2 => 'total',
        3 => 'paid',
        4 => 'rest'
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

    $data = $this->model::GetPayments($sLimit, $sWhere, $sOrder);
    $countTotal = $this->model::countPayments();
    $countTotal = (int)$countTotal[0]->total;
    $countFiltered = $this->model::countFilteredPayments($sWhere);
    $countFiltered = (int)$countFiltered[0]->total;

    $resultArray = [];

    foreach ($data as $key => $value) {
        $resultArray[$key][] = "$this->other_route/$value->idpat";
        $resultArray[$key][] = $value->surname_name;
        $resultArray[$key][] = $this->formatNumber($value->total);
        $resultArray[$key][] = $this->formatNumber($value->paid);
        $resultArray[$key][] = $this->formatNumber($value->rest);
    }

    $output = [
        "sEcho" => intval($sEcho),
        "iTotalRecords" => $countTotal,
        "iTotalDisplayRecords" => $countFiltered,
        "aaData" => $resultArray
    ];

    $this->echoJsonOuptut($output);  
  }

}
