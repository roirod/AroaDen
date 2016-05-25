<?php

namespace App\Http\Controllers;

use DB;
use App\presup;
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
    {
    }

    public function create(Request $request,$idpac)
    {     
        if ( null == $idpac ) {
            return redirect('Pacientes');
        }
        
        $pacientes = pacientes::find($idpac);

        $servicios = servicios::all();        

        $cod = date('Y-m-d H:i:s');

        $posturl = url('/Presup');

        $apepac = $pacientes->apepac;
        $nompac = $pacientes->nompac;

        return view('presup.create', [
            'request' => $request,
            'cod' => $cod,
            'posturl' => $posturl,
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
                                <td class="wid110">'.$presu->cod.'</td> 
                                <td class="wid180">'.$presu->nomser.'</td> 
                                <td class="wid95 textcent">'.$presu->canti.'</td>
                                <td class="wid290"></td>
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

        $pacientes = DB::table('pacientes')->where('idpac', $idpac)->first();
                               
        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.nomser')
                ->where('idpac', $idpac)
                ->orderBy('cod' , 'ASC')
                ->get();  

        $presgroup = DB::table('presup')
                ->groupBy('cod')
                ->having('idpac','=',$idpac)
                ->orderBy('cod' , 'ASC')
                ->get(); 

        return view('presup.show', [
            'request' => $request,
            'presup' => $presup,
            'presgroup' => $presgroup,
            'idpac' => $idpac
        ]);       
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
