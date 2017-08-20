<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Presup;
use App\Models\Prestex;
use App\Models\Pacientes;
use App\Models\Servicios;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class PresupuestosController extends BaseController
{
    public function __construct(Presup $presup)
    {
        $this->middleware('auth');

        $this->main_route = 'Presup';
        $this->other_route = 'Pacientes';
        $this->views_folder = 'presup';
        $this->model = $presup;

        parent::__construct();
    }

    public function create(Request $request, $id = false)
    {     
        $this->redirectIfIdIsNull($id, $this->other_route);
        $id = $this->sanitizeData($id);

        $main_loop = Servicios::AllOrderByName();        

        $code = date('Y-m-d H:i:s');
        $nueurl = url("/$this->main_route");
        $delurl = url("/$this->main_route/delid");

        $paciente = Pacientes::FirstById($id);
        $this->page_title = $paciente->surname.', '.$paciente->name.' - '.$this->page_title;

        $this->view_data['request'] = $request;
        $this->view_data['code'] = $code;
        $this->view_data['nueurl'] = $nueurl;
        $this->view_data['delurl'] = $delurl;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['id'] = $id;
        $this->view_data['idpac'] = $id;        
        $this->view_data['name'] = $paciente->name;
        $this->view_data['surname'] = $paciente->surname;

        return parent::create($request, $id);
    }

    public function store(Request $request)
    {
        $idpac = $this->sanitizeData($request->input('idpac'));
        $idser = $this->sanitizeData($request->input('idser'));
        $price = $this->sanitizeData($request->input('price'));
        $units = $this->sanitizeData($request->input('units'));
        $code = $this->sanitizeData($request->input('code'));
        $tax = $this->sanitizeData($request->input('tax'));
                     
        $this->model::create([
            'idpac' => $idpac,
            'idser' => $idser,
            'price' => $price,
            'units' => $units,
            'code' => $code,
            'tax' => $tax,
            'created_at' => date('Y-m-d H:i:s')                
        ]);

        $prestex = Prestex::FirstById($idpac, $code);

        if (is_null($prestex)) {                 
            Prestex::create([
                'idpac' => $idpac,
                'code' => $code
            ]);
        }

        $presup = $this->model::AllByIdOrderByName($idpac, $code);

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
                        <input type="hidden" name="code" value="'.$code.'">

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
        $id = $this->sanitizeData($id);

        $presup = $this->model::AllById($id);
        $presgroup = $this->model::AllGroupByCode($id);

        $this->view_data['request'] = $request;
        $this->view_data['presup'] = $presup;
        $this->view_data['presgroup'] = $presgroup;
        $this->view_data['id'] = $id;
        $this->view_data['idpac'] = $id;        

        return view($this->views_folder.'.show', $this->view_data);   
    }

    public function presuedit(Request $request)
    {
        $id = $this->sanitizeData($request->input('idpac'));
        $code = $this->sanitizeData($request->input('code'));     

        $prestex = Prestex::FirstById($id, $code);

        $delurl = url("/$this->main_route/delid");

        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.name')
                ->where('code', $code)
                ->orderBy('servicios.name' , 'ASC')
                ->get();

        return view('presup.edit', [
            'request' => $request,
            'presup' => $presup,
            'delurl' => $delurl,
            'code' => $code,
            'idpac' => $idpac,
            'prestex' => $prestex
        ]);        
    }

    public function presmod(Request $request)
    {
        $code = htmlentities (trim( $request->input('code')),ENT_QUOTES,"UTF-8"); 

        $presmod = $request->input('presmod');

        $texto = htmlentities (trim( $request->input('texto')),ENT_QUOTES,"UTF-8");

        if ( null == $code ) {
            return redirect('Pacientes');
        }

        $prestex = prestex::where('code', $code)->first();

        $prestex->texto = $texto;            
        $prestex->save();

        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.name')
                ->where('code', $code)
                ->orderBy('servicios.name' , 'ASC')
                ->get();

        $totiva = DB::table('presup')
                ->selectRaw('SUM((units*price*iva)/100) AS tot')
                ->where('code', $code)
                ->get();

        $siniva = DB::table('presup')
                ->selectRaw('SUM(units*price)-SUM((units*price*iva)/100) AS tot')
                ->where('code', $code)
                ->get();                

        $sumtot = DB::table('presup')
                ->selectRaw('SUM(units*price) AS tot')
                ->where('code', $code)
                ->get();                

        $empre = DB::table('empre')->where('id','1')->first();

        if ($presmod == 'imp') {

            return view('presup.imp', [
                'request' => $request,
                'code' => $code,
                'texto' => $texto,
                'presup' => $presup,
                'presmod' => $presmod,
                'totiva' => $totiva,
                'siniva' => $siniva,
                'sumtot' => $sumtot,
                'empre' => $empre
            ]);  

        } else {
  
            return view('presup.mod', [
                'request' => $request,
                'code' => $code,
                'texto' => $texto,
                'presup' => $presup,
                'totiva' => $totiva,
                'siniva' => $siniva,
                'sumtot' => $sumtot,
                'empre' => $empre
            ]);  
        }      
    } 

    public function delcod(Request $request)
    {
        $code = $request->input('code');
        $idpac = $request->input('idpac');

        if ( null == $code ) {
            return redirect('Pacientes');
        }
        
        if ( null == $idpac ) {
            return redirect('Pacientes');
        }

        $presup = DB::table('presup')
                ->where('code', $code)
                ->where('idpac', $idpac)
                ->delete();

        $prestex = DB::table('prestex')->where('idpac', $idpac)->where('code', $code)->delete();
        
        return redirect("/Presup/$idpac");
    }

    public function delid(Request $request)
    {
        $idpre = $request->input('idpre');

        if ( null === $idpre ) {
            return redirect('Pacientes');
        }   

        $code = $request->input('code');            

        if ( null == $code ) {
            return redirect('Pacientes');
        }       

        $presup = DB::table('presup')
                ->where('code', $code)
                ->first();

        if (is_null($presup)) {
            $prestex = DB::table('prestex')->where('code', $code)->first();
            $prestex->delete();
        }
         
        $presup = presup::find($idpre);
      
        $presup->delete();
        
        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.name')
                ->where('code', $code)
                ->orderBy('servicios.name' , 'ASC')
                ->get();  

        $cadena = '';

        foreach ($presup as $presu) {
            $cadena .= '<tr>
                            <td class="wid140">'.$presu->name.'</td>
                            <td class="wid95 textcent">'.$presu->units.'</td>
                            <td class="wid95 textcent">'.$presu->price.' €</td>

                            <td class="wid50">

                              <form id="delform">
                              
                                <input type="hidden" name="idpre" value="'.$presu->idpre.'">
                                <input type="hidden" name="code" value="'.$code.'">

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
                        </tr> ';
        }
                         
        return $cadena;
    }

}
