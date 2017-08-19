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
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Presup';
        $this->other_route = 'Pacientes';
        $this->views_folder = 'presup';
    }

    public function create(Request $request, $id = false)
    {     
        $this->redirectIfIdIsNull($id, $this->other_route);
        $id = $this->sanitizeData($id);
        
        $paciente = Pacientes::FirstById($id);
        $servicios = Servicios::AllOrderByName();        

        $code = date('Y-m-d H:i:s');

        $nueurl = url("/$this->main_route");
        $delurl = url("/$this->main_route/delid");

        $this->page_title = $paciente->surname.', '.$paciente->name.' - '.$this->page_title;

        $this->view_data['request'] = $request;
        $this->view_data['code'] = $code;
        $this->view_data['nueurl'] = $nueurl;
        $this->view_data['delurl'] = $delurl;
        $this->view_data['servicios'] = $servicios;
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
                     
        Presup::create([
            'idpac' => $idpac,
            'idser' => $idser,
            'price' => $price,
            'units' => $units,
            'code' => $code,
            'tax' => $tax,
            'created_at' => date('Y-m-d H:i:s')                
        ]);

        $prestex = Prestex::FirstById($id, $code);

        if (is_null($prestex)) {                 
            Prestex::create([
                'idpac' => $idpac,
                'code' => $code
            ]);
        }

        $presup = Presup::AllByIdOrderByName($idpac, $code);

        $cadena = '';

        foreach ($presup as $presu) {

            $cadena .= '
                <tr>
                    <td class="wid140">'.$presu->nomser.'</td>
                    <td class="wid95 textcent">'.$presu->canti.'</td>
                    <td class="wid95 textcent">'.$presu->precio.' €</td>

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

        $presup = Presup::AllById($id, $code);
        $presgroup = Presup::AllGroupByCode($id);

        $this->view_data['request'] = $request;
        $this->view_data['presup'] = $presup;
        $this->view_data['presgroup'] = $presgroup;
        $this->view_data['id'] = $id;
        $this->view_data['idpac'] = $id;        

        return view($this->views_folder.'.show', $this->view_data);   
    }

    public function presuedit(Request $request)
    {
        $idpac = $this->sanitizeData($request->input('idpac'));
        $code = $this->sanitizeData($request->input('code'));     

        $prestex = Prestex::FirstById($id, $code);

        $delurl = url("/$this->main_route/delid");

        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.nomser')
                ->where('cod', $cod)
                ->orderBy('servicios.nomser' , 'ASC')
                ->get();

        return view('presup.edit', [
            'request' => $request,
            'presup' => $presup,
            'delurl' => $delurl,
            'cod' => $cod,
            'idpac' => $idpac,
            'prestex' => $prestex
        ]);        
    }

    public function presmod(Request $request)
    {
        $cod = htmlentities (trim( $request->input('cod')),ENT_QUOTES,"UTF-8"); 

        $presmod = $request->input('presmod');

        $texto = htmlentities (trim( $request->input('texto')),ENT_QUOTES,"UTF-8");

        if ( null == $cod ) {
            return redirect('Pacientes');
        }

        $prestex = prestex::where('cod', $cod)->first();

        $prestex->texto = $texto;            
        $prestex->save();

        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.nomser')
                ->where('cod', $cod)
                ->orderBy('servicios.nomser' , 'ASC')
                ->get();

        $totiva = DB::table('presup')
                ->selectRaw('SUM((canti*precio*iva)/100) AS tot')
                ->where('cod', $cod)
                ->get();

        $siniva = DB::table('presup')
                ->selectRaw('SUM(canti*precio)-SUM((canti*precio*iva)/100) AS tot')
                ->where('cod', $cod)
                ->get();                

        $sumtot = DB::table('presup')
                ->selectRaw('SUM(canti*precio) AS tot')
                ->where('cod', $cod)
                ->get();                

        $empre = DB::table('empre')->where('id','1')->first();

        if ($presmod == 'imp') {

            return view('presup.imp', [
                'request' => $request,
                'cod' => $cod,
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
                'cod' => $cod,
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
        $cod = $request->input('cod');
        $idpac = $request->input('idpac');

        if ( null == $cod ) {
            return redirect('Pacientes');
        }
        
        if ( null == $idpac ) {
            return redirect('Pacientes');
        }

        $presup = DB::table('presup')
                ->where('cod', $cod)
                ->where('idpac', $idpac)
                ->delete();

        $prestex = DB::table('prestex')->where('idpac', $idpac)->where('cod', $cod)->delete();
        
        return redirect("/Presup/$idpac");
    }

    public function delid(Request $request)
    {
        $idpre = $request->input('idpre');

        if ( null === $idpre ) {
            return redirect('Pacientes');
        }   

        $cod = $request->input('cod');            

        if ( null == $cod ) {
            return redirect('Pacientes');
        }       

        $presup = DB::table('presup')
                ->where('cod', $cod)
                ->first();

        if (is_null($presup)) {
            $prestex = DB::table('prestex')->where('cod', $cod)->first();
            $prestex->delete();
        }
         
        $presup = presup::find($idpre);
      
        $presup->delete();
        
        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.nomser')
                ->where('cod', $cod)
                ->orderBy('servicios.nomser' , 'ASC')
                ->get();  

        $cadena = '';

        foreach ($presup as $presu) {
            $cadena .= '<tr>
                            <td class="wid140">'.$presu->nomser.'</td>
                            <td class="wid95 textcent">'.$presu->canti.'</td>
                            <td class="wid95 textcent">'.$presu->precio.' €</td>

                            <td class="wid50">

                              <form id="delform">
                              
                                <input type="hidden" name="idpre" value="'.$presu->idpre.'">
                                <input type="hidden" name="cod" value="'.$cod.'">

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
