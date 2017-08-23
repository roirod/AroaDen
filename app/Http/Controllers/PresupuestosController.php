<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Presup;
use App\Models\Prestex;
use App\Models\Pacientes;
use App\Models\Servicios;
use App\Models\Settings;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class PresupuestosController extends BaseController
{
    public function __construct(Presup $presup)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Presup';
        $this->other_route = 'Pacientes';
        $this->views_folder = 'presup';
        $this->model = $presup;
    }

    public function create(Request $request, $id = false)
    {     
        $this->redirectIfIdIsNull($id, $this->other_route);
        $idpac = $this->sanitizeData($id);

        $main_loop = Servicios::AllOrderByName();        

        $uniqid = uniqid();
        $created_at = date('Y-m-d H:i:s');
        $nueurl = url("/$this->main_route");
        $delurl = url("/$this->main_route/delid");

        $paciente = Pacientes::FirstById($idpac);
        $this->page_title = $paciente->surname.', '.$paciente->name.' - '.$this->page_title;

        $this->view_data['request'] = $request;
        $this->view_data['created_at'] = $created_at;
        $this->view_data['uniqid'] = $uniqid;        
        $this->view_data['nueurl'] = $nueurl;
        $this->view_data['delurl'] = $delurl;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['idpac'] = $idpac;
        $this->view_data['idnav'] = $idpac;        
        $this->view_data['name'] = $paciente->name;
        $this->view_data['surname'] = $paciente->surname;

        return parent::create($request, $idpac);
    }

    public function store(Request $request)
    {
        $idpac = $this->sanitizeData($request->input('idpac'));
        $idser = $this->sanitizeData($request->input('idser'));
        $price = $this->sanitizeData($request->input('price'));
        $units = $this->sanitizeData($request->input('units'));
        $uniqid = $this->sanitizeData($request->input('uniqid'));        
        $created_at = $this->sanitizeData($request->input('created_at'));
        $tax = $this->sanitizeData($request->input('tax'));
                     
        $this->model::create([
            'idpac' => $idpac,
            'idser' => $idser,
            'price' => $price,
            'units' => $units,
            'uniqid' => $uniqid,
            'tax' => $tax,
            'created_at' => $created_at               
        ]);

        $prestex = Prestex::FirstById($idpac, $uniqid);

        if (is_null($prestex)) {                 
            Prestex::create([
                'idpac' => $idpac,
                'uniqid' => $uniqid,                
                'created_at' => $created_at
            ]);
        }

        $presup = $this->model::AllByIdOrderByName($idpac, $uniqid);

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
        $idpac = $this->sanitizeData($id);

        $paciente = Pacientes::FirstById($idpac);
        $this->page_title = $paciente->surname.', '.$paciente->name.' - '.$this->page_title;
        $this->passVarsToViews();

        $presup = $this->model::AllById($idpac);
        $presgroup = $this->model::AllGroupByCode($idpac);

        $this->view_data['request'] = $request;
        $this->view_data['presup'] = $presup;
        $this->view_data['presgroup'] = $presgroup;
        $this->view_data['idpac'] = $idpac;
        $this->view_data['idnav'] = $idpac;        

        return view($this->views_folder.'.show', $this->view_data);   
    }

    public function presuedit(Request $request)
    {
        $idpac = $this->sanitizeData($request->input('idpac'));
        $uniqid = $this->sanitizeData($request->input('uniqid'));     

        $paciente = Pacientes::FirstById($idpac);
        $this->page_title = $paciente->surname.', '.$paciente->name.' - '.$this->page_title;
        $this->passVarsToViews();

        $delurl = url("/$this->main_route/delid");

        $presup = $this->model::AllByIdOrderByName($idpac, $uniqid);
        $prestex = Prestex::FirstById($idpac, $uniqid);

        $this->view_data['request'] = $request;
        $this->view_data['presup'] = $presup;
        $this->view_data['delurl'] = $delurl;
        $this->view_data['prestex'] = $prestex;
        $this->view_data['uniqid'] = $uniqid;        
        $this->view_data['idpac'] = $idpac;
        $this->view_data['idnav'] = $idpac;     

        return view($this->views_folder.'.edit', $this->view_data);   
    }

    public function presmod(Request $request)
    {
        $uniqid = $this->sanitizeData($request->input('uniqid'));     
        $presmod = $this->sanitizeData($request->input('presmod'));     
        $text = $this->sanitizeData($request->input('text'));   
        $idpac = $this->sanitizeData($request->input('idpac'));   

        if ($presmod == 'save_text') {
            Prestex::where('uniqid', $uniqid)->update(['text' => $text]);

            $data = [];
            $data['msg'] = 'Texto guardado'; 

            $this->echoJsonOuptut($data);
        }

        $presup = $this->model::AllByCode($uniqid);
        $taxtotal = $this->model::GetTaxTotal($uniqid);
        $notaxtotal = $this->model::GetNoTaxTotal($uniqid);
        $total = $this->model::GetTotal($uniqid);
        $empre = Settings::getObject();
            
        $this->view_data['request'] = $request;
        $this->view_data['text'] = $text;
        $this->view_data['presup'] = $presup;
        $this->view_data['uniqid'] = $uniqid;
        $this->view_data['presmod'] = $presmod;
        $this->view_data['idpac'] = $idpac;
        $this->view_data['idnav'] = $idpac;  
        $this->view_data['taxtotal'] = $taxtotal;
        $this->view_data['notaxtotal'] = $notaxtotal;
        $this->view_data['total'] = $total;
        $this->view_data['empre'] = $empre;  

        if ($presmod == 'imp') {

            return view($this->views_folder.'.imp', $this->view_data);  

        } else {
  
            return view($this->views_folder.'.mod', $this->view_data);  
 
        }      
    } 

    public function delcod(Request $request)
    {
        $uniqid = $request->input('uniqid');
        $idpac = $request->input('idpac');

        $presup = DB::table('presup')
                ->where('uniqid', $uniqid)
                ->delete();

        $prestex = DB::table('prestex')
                ->where('uniqid', $uniqid)
                ->delete();
        
        return redirect("/$this->main_route/$idpac");
    }

    public function delid(Request $request)
    {      
        $idpre = $this->sanitizeData($request->input('idpre'));
        $uniqid = $this->sanitizeData($request->input('uniqid'));  

        $presup = presup::find($idpre);
        $presup->delete();
        
        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.name')
                ->where('uniqid', $uniqid)
                ->orderBy('servicios.name' , 'ASC')
                ->get();  

        if (count($presup) == 0) {

            $prestex = DB::table('prestex')
                    ->where('uniqid', $uniqid)
                    ->delete();

        }

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
