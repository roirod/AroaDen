<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BudgetsText;
use App\Models\Patients;
use App\Models\Services;
use App\Models\Budgets;
use Validator;
use Lang;
use DB;

class BudgetsController extends BaseController
{

    public function __construct(Budgets $budgets, Patients $patients, Services $services)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['budgets'];
        $this->other_route = $this->config['routes']['patients'];      
        $this->views_folder = 'budgets';
        $this->model = $budgets;
        $this->model2 = $patients;
        $this->model3 = $services;
    }

    public function create(Request $request, $id = false)
    {     
        $this->redirectIfIdIsNull($id, $this->other_route);
        $idpat = $this->sanitizeData($id);

        $main_loop = $this->model3::AllOrderByName();        
        $patient = $this->model2::FirstById($idpat);

        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;        
        $this->view_data['name'] = $patient->name;
        $this->view_data['surname'] = $patient->surname;
        $this->view_data['object'] = $patient;
        $this->view_data['created_at'] = date('Y-m-d H:i:s'); 
        $this->view_data['uniqid'] = uniqid();

        $this->setPageTitle($patient->surname.', '.$patient->name);

        return parent::create($request, $idpat);
    }

    public function store(Request $request)
    {
        $budgetArray = $request->input('budgetArray');
        $onUpdate = $request->input('onUpdate');
        $onUpdateDeleteAll = $request->input('onUpdateDeleteAll');
        $uniqid = $request->input('uniqid');
        $data = [];
        $data['msg'] = Lang::get('aroaden.success_message');
        $data['error'] = false;

        try {

            if (empty($budgetArray) && $onUpdate == false) {

                $data['msg'] = Lang::get('aroaden.no_add_treatments');
                $data['error'] = true;

            } elseif (empty($budgetArray) && $onUpdate && $onUpdateDeleteAll) {

                $this->delete($uniqid, true);

            } elseif (!empty($budgetArray) && $onUpdate) {

                $this->delete($uniqid);
                $this->save($budgetArray);

            } elseif (!empty($budgetArray) && $onUpdate == false) {

                $this->save($budgetArray);

            }

        } catch (\Exception $e) {

            $data['msg'] = Lang::get('aroaden.db_query_error');
            $data['error'] = true;

        }

        return $this->echoJsonOuptut($data);
    }

    private function save($budgetArray)
    {
        $budgetsTextExists = false;

        DB::beginTransaction();

        try {

            foreach ($budgetArray as $key => $val) {
                $this->model::create([
                    'idpat' => $val["idpat"],
                    'idser' => $val["idser"],
                    'price' => $val["price"],
                    'units' => $val["units"],
                    'uniqid' => $val["uniqid"],
                    'tax' => $val["tax"],
                    'created_at' => $val["created_at"]               
                ]);

                if (!$budgetsTextExists) {
                    $budgetstext = BudgetsText::FirstById($val["idpat"], $val["uniqid"]);

                    if (is_null($budgetstext)) {          
                        BudgetsText::create([
                            'idpat' => $val["idpat"],
                            'uniqid' => $val["uniqid"],                
                            'created_at' => $val["created_at"]
                        ]);

                        $budgetsTextExists = true;
                    }                    
                }
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            throw $e;

        }
    }

    public function show(Request $request, $id = false)
    {
        $this->redirectIfIdIsNull($id, $this->other_route);
        $idpat = $this->sanitizeData($id);

        $patient = $this->model2::FirstById($idpat);
        $this->setPageTitle($patient->surname.', '.$patient->name);

        $budgets = $this->model::AllById($idpat);
        $budgets_group = $this->model::AllGroupByCode($idpat);

        $this->view_data['budgets'] = $budgets;
        $this->view_data['budgets_group'] = $budgets_group;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;        
        $this->view_data['object'] = $patient;

        return parent::show($request, $id);
    }

    public function edit(Request $request, $uniqid)
    {
        $uniqid = $this->sanitizeData($uniqid);     

        $budgets = $this->model::AllByCode($uniqid);
        $arr_budgets = $budgets->toArray();
        $idpat = $arr_budgets[0]->idpat;
        $budgetstext = BudgetsText::FirstById($idpat, $uniqid);

        $patient = $this->model2::FirstById($idpat);
        $this->setPageTitle($patient->surname.', '.$patient->name);

        $this->view_data['request'] = $request;
        $this->view_data['budgets'] = $budgets;
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

        } else {
  
            $this->view_name = 'mode';
            
        }

        return $this->loadView();
    } 

    public function delBudget(Request $request)
    {
        $uniqid = $request->input('uniqid');
        $idpat = $request->input('idpat');

        try {

            $this->delete($uniqid, true);

        } catch (\Exception $e) {

            $request->session()->flash($this->error_message_name, $e->getMessage()); 

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
