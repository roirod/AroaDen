<?php

namespace App\Http\Controllers;

use DB;
use App\presup;
use App\prestex;
use App\pacientes;
use App\servicios;

use Validator;

use Illuminate\Http\Request;
use App\Http\Requests;

class PresupuestosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    { }

    public function create(Request $request,$idpac)
    {     
        if ( null == $idpac ) {
            return redirect('Pacientes');
        }
        
        $pacientes = pacientes::find($idpac);

        $servicios = servicios::all();        

        $cod = date('Y-m-d H:i:s');

        $nueurl = url('/Presup');
        $delurl = url('/Presup/delid');

        $apepac = $pacientes->apepac;
        $nompac = $pacientes->nompac;

        return view('presup.create', [
            'request' => $request,
            'cod' => $cod,
            'nueurl' => $nueurl,
            'delurl' => $delurl,
            'servicios' => $servicios,
            'idpac' => $idpac,
            'apepac' => $apepac,
            'nompac' => $nompac
        ]);
    }

    public function store(Request $request)
    {
        $idpac = htmlentities (trim( $request->input('idpac')),ENT_QUOTES,"UTF-8");
        $idser = htmlentities (trim( $request->input('idser')),ENT_QUOTES,"UTF-8");
        $precio = htmlentities (trim( $request->input('precio')),ENT_QUOTES,"UTF-8");
        $canti = htmlentities (trim( $request->input('canti')),ENT_QUOTES,"UTF-8");
        $cod = htmlentities (trim( $request->input('cod')),ENT_QUOTES,"UTF-8");
        $iva = htmlentities (trim( $request->input('iva')),ENT_QUOTES,"UTF-8");            

        if ( null == $idpac ) {
            return redirect('Pacientes');
        }         
          
        $validator = Validator::make($request->all(), [
            'idpac' => 'required',
            'idser' => 'required',
            'precio' => 'required',
            'canti' => 'required',
            'cod' => 'required',
            'iva' => 'required',            
        ]);
            
        if ($validator->fails()) {
            return redirect("/Citas/$idpac/create")
                         ->withErrors($validator)
                         ->withInput();
        } else {
                
            presup::create([
                'idpac' => $idpac,
                'idser' => $idser,
                'precio' => $precio,
                'canti' => $canti,
                'cod' => $cod,
                'iva' => $iva,
            ]);

            $presup = DB::table('presup')
                    ->join('servicios', 'presup.idser','=','servicios.idser')
                    ->select('presup.*','servicios.nomser')
                    ->where('idpac', $idpac)
                    ->where('cod', $cod)
                    ->orderBy('nomser' , 'ASC')
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

    public function show(Request $request,$idpac)
    {
        if ( null == $idpac ) {
            return redirect('Pacientes');
        }

        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");
                               
        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.nomser')
                ->where('idpac', $idpac)
                ->orderBy('cod' , 'DESC')
                ->get();  

        $presgroup = DB::table('presup')
                ->groupBy('cod')
                ->having('idpac','=',$idpac)
                ->orderBy('cod' , 'DESC')
                ->get(); 

        return view('presup.show', [
            'request' => $request,
            'presup' => $presup,
            'presgroup' => $presgroup,
            'idpac' => $idpac
        ]);       
    }

    public function presuedit(Request $request)
    {
        $idpac = htmlentities (trim( $request->input('idpac')),ENT_QUOTES,"UTF-8");
        $cod = htmlentities (trim( $request->input('cod')),ENT_QUOTES,"UTF-8");          

        if ( null == $idpac ) {
            return redirect('Pacientes');
        }   

        if ( null == $cod ) {
            return redirect('Pacientes');
        }

        $prestex = DB::table('prestex')->where('idpac', $idpac)->where('cod', $cod)->first();

        if (is_null($prestex)) {

            $validator = Validator::make($request->all(),[
                'idpac' => 'required',
                'cod' => 'required',
                'texto' => ''
            ]);
                    
            if ($validator->fails()) {
                return redirect('Servicios/create')
                             ->withErrors($validator)
                             ->withInput();
             } else {
                    
                prestex::create([
                    'idpac' => $idpac,
                    'cod' => $cod
                ]);
            }   
        }

        $prestex = DB::table('prestex')->where('idpac', $idpac)->where('cod', $cod)->first();

        $delurl = url('/Presup/delid');

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

    public function update(Request $request, $id)
    { }

    public function edit($id)
    { }

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

    public function destroy(Request $request,$idpre)
    {
    }
}
