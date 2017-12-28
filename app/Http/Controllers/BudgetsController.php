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
    }

    public function create(Request $request, $id = false)
    {     
        $this->redirectIfIdIsNull($id, $this->other_route);
        $idpat = $this->sanitizeData($id);

        $main_loop = $this->model3::AllOrderByName();
        $patient = $this->model2::FirstById($idpat);

        $uniqid = uniqid();
        $created_at = date('Y-m-d H:i:s');
        $new_url = url("/$this->main_route");
        $del_url = url("/$this->main_route/delid");

        $this->view_data['request'] = $request;
        $this->view_data['created_at'] = $created_at;
        $this->view_data['uniqid'] = $uniqid;        
        $this->view_data['new_url'] = $new_url;
        $this->view_data['del_url'] = $del_url;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;        
        $this->view_data['name'] = $patient->name;
        $this->view_data['surname'] = $patient->surname;

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

        $presup = $this->model::AllByIdOrderByName($idpat, $uniqid);

        $cadena = '';

        foreach ($presup as $presu) {

            $cadena .= '
                <tr>
                    <td class="wid140">'.$presu->name.'</td>
                    <td class="wid95 textcent">'.$presu->units.'</td>
                    <td class="wid95 textcent">'.$presu->price.' €</td>

                    <td class="wid50">

                      <form id="delform">
                      
                        <input type="hidden" name="idpre" value="'.$presu->idpre.'">
                        <input type="hidden" name="uniqid" value="'.$uniqid.'">

                        <div class="btn-group">
                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-times"></i>  <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                              <li> <button type="submit"> <i class="fa fa-times"></i> Borrar</button></li> 
                            </ul> 
                        </div>

                      </form>   
                    </td>

                    <td class="wid230"> </td>                            
                </tr> 
            ';
        }
                         
        return $cadena;     
    }

    public function show(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->other_route);
        $idpat = $this->sanitizeData($id);

        $patient = $this->model2::FirstById($idpat);
        $this->setPageTitle($patient->surname.', '.$patient->name);

        $presup = $this->model::AllById($idpat);
        $presgroup = $this->model::AllGroupByCode($idpat);

        $this->view_data['request'] = $request;
        $this->view_data['presup'] = $presup;
        $this->view_data['presgroup'] = $presgroup;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;        

        return parent::show($request, $id);
    }

    public function presuedit(Request $request)
    {
        $idpat = $this->sanitizeData($request->input('idpat'));
        $uniqid = $this->sanitizeData($request->input('uniqid'));     

        $patient = $this->model2::FirstById($idpat);
        $this->setPageTitle($patient->surname.', '.$patient->name);

        $delurl = url("/$this->main_route/delid");

        $presup = $this->model::AllByIdOrderByName($idpat, $uniqid);
        $prestex = BudgetsText::FirstById($idpat, $uniqid);

        $this->view_data['request'] = $request;
        $this->view_data['presup'] = $presup;
        $this->view_data['delurl'] = $delurl;
        $this->view_data['prestex'] = $prestex;
        $this->view_data['uniqid'] = $uniqid;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;     

        return $this->loadView($this->views_folder.'.edit', $this->view_data);
    }

    public function presmod(Request $request)
    {
        $uniqid = $this->sanitizeData($request->input('uniqid')); 
        $presmod = $this->sanitizeData($request->input('presmod'));     
        $text = $this->sanitizeData($request->input('text'));   
        $idpat = $this->sanitizeData($request->input('idpat'));   

        if ($presmod == 'save_text') {
            BudgetsText::where('uniqid', $uniqid)->update(['text' => $text]);

            $data = [];
            $data['msg'] = 'Texto guardado'; 

            $this->echoJsonOuptut($data);
        }

        $presup = $this->model::AllByCode($uniqid);
        $created_at = $presup[0]->created_at;

        $taxtotal = $this->model::GetTaxTotal($uniqid);
        $notaxtotal = $this->model::GetNoTaxTotal($uniqid);
        $total = $this->model::GetTotal($uniqid);
        $empre = $this->getSettings();
  
        $this->view_data['request'] = $request;
        $this->view_data['text'] = $text;
        $this->view_data['presup'] = $presup;
        $this->view_data['uniqid'] = $uniqid;
        $this->view_data['created_at'] = $created_at;
        $this->view_data['presmod'] = $presmod;
        $this->view_data['idpat'] = $idpat;
        $this->view_data['idnav'] = $idpat;  
        $this->view_data['taxtotal'] = $taxtotal;
        $this->view_data['notaxtotal'] = $notaxtotal;
        $this->view_data['total'] = $total;
        $this->view_data['empre'] = $empre;  

        if ($presmod == 'imp') {

            return $this->loadView($this->views_folder.'.imp', $this->view_data);

        } else {
  
            return $this->loadView($this->views_folder.'.mod', $this->view_data);
            
        }      
    } 

    public function delcod(Request $request)
    {
        $uniqid = $request->input('uniqid');
        $idpat = $request->input('idpat');

        Budgets::where('uniqid', $uniqid)->delete();
        BudgetsText::where('uniqid', $uniqid)->delete();
        
        return redirect("/$this->main_route/$idpat");
    }

    public function delid(Request $request)
    {      
        $idpre = $this->sanitizeData($request->input('idpre'));
        $uniqid = $this->sanitizeData($request->input('uniqid'));  

        $presup = Budgets::find($idpre);
        $presup->delete();
        
        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.name')
                ->where('uniqid', $uniqid)
                ->orderBy('servicios.name' , 'ASC')
                ->get();  

        if (count($presup) == 0)
            BudgetsText::where('uniqid', $uniqid)->delete();

        $cadena = '';

        foreach ($presup as $presu) {
            $cadena .= '
                <tr>
                    <td class="wid140">'.$presu->name.'</td>
                    <td class="wid95 textcent">'.$presu->units.'</td>
                    <td class="wid95 textcent">'.$presu->price.' €</td>
                    <td class="wid50">
                      <form id="delform">
                        <input type="hidden" name="idpre" value="'.$presu->idpre.'">
                        <input type="hidden" name="uniqid" value="'.$uniqid.'">

                        <div class="btn-group">
                            <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-times"></i>  <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                              <li> <button type="submit"> <i class="fa fa-times"></i> Borrar</button></li> 
                            </ul> 
                        </div>
                      </form>   
                    </td>
                    <td class="wid230"> </td>                            
                </tr> 
            ';
        }
                         
        return $cadena;
    }

}
