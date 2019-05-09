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

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['pays'];
        $this->other_route = $this->config['routes']['patients'];
        $this->view_data['pays_route'] = $this->config['routes']['pays'];
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
        $iDisplayLength = $request->input('iDisplayLength');
        $iDisplayStart = $request->input('iDisplayStart');

        $sLimit = "";
        if (isset($iDisplayStart ) && $iDisplayLength != '-1')
            $sLimit = "LIMIT ".$iDisplayStart.",". $iDisplayLength;

        $data = $this->model::GetTotalPayments($sLimit);
        $countTotal = $this->model::countTotalPatientsPayments();
        $countTotal = (int) $countTotal[0]->total;

        $resultArray = [];

        foreach ($data as $key => $value) {
            $resultArray[$key][] = "$this->other_route/$value->idpat";
            $resultArray[$key][] = $value->surname_name;
            $resultArray[$key][] = $this->formatNumber($value->total);
            $resultArray[$key][] = $this->formatNumber($value->paid);
            $resultArray[$key][] = $this->formatNumber($value->rest);
        }

        $output = [
            "sEcho" => intval($request->input('sEcho')),
            "iTotalRecords" => $countTotal,
            "iTotalDisplayRecords" => $countTotal,
            "aaData" => array_values($resultArray)
        ];

        $this->echoJsonOuptut($output);  
    }

}
