<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BudgetsText;
use App\Models\Patients;
use App\Models\Services;
use App\Models\Settings;
use App\Models\Budgets;
use Validator;
use DB;

class BudgetsController extends BaseController
{
    private $_new_url;
    private $_del_url;

    public function __construct(Budgets $budgets, Patients $patients, Services $services)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['budgets'];
        $this->other_route = $this->config['routes']['patients'];      
        $this->views_folder = $this->config['routes']['budgets'];
        $this->model = $budgets;
        $this->model2 = $patients;
        $this->model3 = $services;
        $this->_new_url = url("/$this->main_route");
        $this->_del_url = url("/$this->main_route/delId");
    }

    public function create(Request $request, $id = false)
    {     
        $this->redirectIfIdIsNull($id, $this->other_route);
        $idpat = $this->sanitizeData($id);

        $main_loop = $this->model3::AllOrderByName();        
        $patient = $this->model2::FirstById($idpat);

        $uniqid = uniqid();
        $created_at = date('Y-m-d H:i:s');

        $this->view_data['request'] = $request;
        $this->view_data['created_at'] = $created_at;
        $this->view_data['uniqid'] = $uniqid;        
        $this->view_data['new_url'] = $this->_new_url;
        $this->view_data['del_url'] = $this->_del_url;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;        
        $this->view_data['name'] = $patient->name;
        $this->view_data['surname'] = $patient->surname;
        $this->view_data['object'] = $patient;

        $this->setPageTitle($patient->surname.', '.$patient->name);

        return parent::create($request, $idpat);
    }

    public function store(Request $request)
    {
        $idpat = $this->sanitizeData($request->input('idpat'));
        $idser = $this->sanitizeData($request->input('idser'));
        $price = $this->sanitizeData($request->input('price'));
        $units = $this->sanitizeData($request->input('units'));
        $uniqid = $this->sanitizeData($request->input('uniqid'));        
        $created_at = $this->sanitizeData($request->input('created_at'));
        $tax = $this->sanitizeData($request->input('tax'));
                     
        $this->model::create([
            'idpat' => $idpat,
            'idser' => $idser,
            'price' => $price,
            'units' => $units,
            'uniqid' => $uniqid,
            'tax' => $tax,
            'created_at' => $created_at               
        ]);

        $budgetstext = BudgetsText::FirstById($idpat, $uniqid);

        if (is_null($budgetstext)) {                 
            BudgetsText::create([
                'idpat' => $idpat,
                'uniqid' => $uniqid,                
                'created_at' => $created_at
            ]);
        }

        $budgets = $this->model::AllByIdOrderByName($idpat, $uniqid);

        $this->view_data['budgets'] = $budgets;

        $this->view_name = 'budgetsTable';

        return $this->loadView();
    }

    public function show(Request $request, $id = false)
    {
        $this->redirectIfIdIsNull($id, $this->other_route);
        $idpat = $this->sanitizeData($id);

        $patient = $this->model2::FirstById($idpat);
        $this->setPageTitle($patient->surname.', '.$patient->name);

        $budgets = $this->model::AllById($idpat);
        $budgets_group = $this->model::AllGroupByCode($idpat);

        $this->view_data['request'] = $request;
        $this->view_data['budgets'] = $budgets;
        $this->view_data['budgets_group'] = $budgets_group;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;        
        $this->view_data['object'] = $patient;

        return parent::show($request, $id);
    }

    public function editBudget(Request $request)
    {
        $idpat = $this->sanitizeData($request->input('idpat'));
        $uniqid = $this->sanitizeData($request->input('uniqid'));     

        $patient = $this->model2::FirstById($idpat);
        $this->setPageTitle($patient->surname.', '.$patient->name);

        $budgets = $this->model::AllByIdOrderByName($idpat, $uniqid);
        $budgetstext = BudgetsText::FirstById($idpat, $uniqid);

        $this->view_data['request'] = $request;
        $this->view_data['budgets'] = $budgets;
        $this->view_data['del_url'] = $this->_del_url;
        $this->view_data['budgetstext'] = $budgetstext;
        $this->view_data['uniqid'] = $uniqid;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;     
        $this->view_data['object'] = $patient;
        
        $this->view_name = 'edit';

        return $this->loadView();
    }

    public function mode(Request $request)
    {
        $uniqid = $this->sanitizeData($request->input('uniqid')); 
        $mode = $this->sanitizeData($request->input('mode'));     
        $text = $this->sanitizeData($request->input('text'));   
        $idpat = $this->sanitizeData($request->input('idpat'));   

        if ($mode == 'save_text') {
            BudgetsText::where('uniqid', $uniqid)->update(['text' => $text]);

            $data = [];
            $data['msg'] = 'Texto guardado'; 

            $this->echoJsonOuptut($data);
        }

        $budgets = $this->model::AllByCode($uniqid);
        $created_at = $budgets[0]->created_at;

        $taxtotal = $this->model::GetTaxTotal($uniqid);
        $notaxtotal = $this->model::GetNoTaxTotal($uniqid);
        $total = $this->model::GetTotal($uniqid);
        $company = $this->getSettings();

        $this->view_data['request'] = $request;
        $this->view_data['text'] = $text;
        $this->view_data['budgets'] = $budgets;
        $this->view_data['uniqid'] = $uniqid;
        $this->view_data['created_at'] = $created_at;
        $this->view_data['mode'] = $mode;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;  
        $this->view_data['taxtotal'] = $taxtotal[0]->total;
        $this->view_data['notaxtotal'] = $notaxtotal[0]->total;
        $this->view_data['total'] = $total[0]->total;
        $this->view_data['company'] = $company;  

        if ($mode == 'print') {

            $this->view_name = 'print';

            return $this->loadView();

        } else {
  
            $this->view_name = 'mode';

            return $this->loadView();
            
        }      
    } 

    public function delCode(Request $request)
    {
        $uniqid = $request->input('uniqid');
        $idpat = $request->input('idpat');

        Budgets::where('uniqid', $uniqid)->delete();
        BudgetsText::where('uniqid', $uniqid)->delete();
        
        return redirect("/$this->main_route/$idpat");
    }

    public function delId(Request $request)
    {      
        $idbud = $this->sanitizeData($request->input('idbud'));
        $uniqid = $this->sanitizeData($request->input('uniqid'));  

        $budget = Budgets::find($idbud);
        $budget->delete();
        
        $budgets = Budgets::AllByCode($uniqid);  

        if (count($budgets) == 0)
            BudgetsText::where('uniqid', $uniqid)->delete();

        $this->view_data['budgets'] = $budgets;

        $this->view_name = 'budgetsTable';

        return $this->loadView();
    }

}
