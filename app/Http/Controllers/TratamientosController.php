<?php

namespace App\Http\Controllers;

use DB;
use App\pacientes;
use App\servicios;
use App\tratampacien;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class TratamientosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }   

    public function index()
    {  }

    public function create(Request $request)
    {     
    }

    public function crea(Request $request)
    {     
        $idpac = $request->input('idpac');

        if ( empty($idpac) ) {
            return redirect('Pacientes');
        }
        
        $servicios = DB::table('servicios')->orderBy('nomser', 'ASC')->get();
        $pacientes = pacientes::find($idpac);

        $apepac = $pacientes->apepac;
        $nompac = $pacientes->nompac;

        return view('trat.crea', [
            'request' => $request,
            'servicios' => $servicios,
            'idpac' => $idpac,
            'apepac' => $apepac,
            'nompac' => $nompac
        ]);
    }

    public function selcrea(Request $request)
    {     
        $idser = $request->input('idser');
        $idpac = $request->input('idpac');
        $apepac = $request->input('apepac');
        $nompac = $request->input('nompac');     

        $servicio = servicios::find($idser);
        $personal = DB::table('personal')->orderBy('ape', 'ASC')->get();
        
        return view('trat.selcrea', [
            'request' => $request,
            'servicio' => $servicio,
            'personal' => $personal,
            'idpac' => $idpac,
            'apepac' => $apepac,
            'nompac' => $nompac
        ]);
    }

    public function store(Request $request)
    {
        
        $idpac = $request->input('idpac');

        if ( null == $idpac ) {
            return redirect('Pacientes');
        }       
                  
        $validator = Validator::make($request->all(), [
            'idpac' => 'required',
            'idser' => 'required',
            'precio' => 'required',
            'canti' => 'required',
            'pagado' => 'required',
            'fecha' => 'required|date',
            'iva' => 'max:12',
            'per1' => '',
            'per2' => '',
            'notas' => ''
        ]);
            
        if ($validator->fails()) {
            return redirect("/Pacientes/$idpac")
                         ->withErrors($validator)
                         ->withInput();
        } else {

            $idpac = htmlentities (trim($request->input('idpac')),ENT_QUOTES,"UTF-8");
            $idser = htmlentities (trim($request->input('idser')),ENT_QUOTES,"UTF-8");
            $precio = htmlentities (trim($request->input('precio')),ENT_QUOTES,"UTF-8");
            $canti = htmlentities (trim($request->input('canti')),ENT_QUOTES,"UTF-8");
            $pagado = htmlentities (trim($request->input('pagado')),ENT_QUOTES,"UTF-8");
            $fecha = htmlentities (trim($request->input('fecha')),ENT_QUOTES,"UTF-8");
            $iva = htmlentities (trim($request->input('iva')),ENT_QUOTES,"UTF-8");
            $per1 = htmlentities (trim($request->input('per1')),ENT_QUOTES,"UTF-8");
            $per2 = htmlentities (trim($request->input('per2')),ENT_QUOTES,"UTF-8");            
            $notas = htmlentities (trim($request->input('notas')),ENT_QUOTES,"UTF-8");

            tratampacien::create([
                'idpac' => $idpac,
                'idser' => $idser,
                'precio' => $precio,
                'canti' => $canti,
                'pagado' => $pagado,
                'fecha' => $fecha,
                'iva' => $iva,
                'per1' => $per1,
                'per2' => $per2,
                'notas' => $notas
            ]);
              
            $request->session()->flash('sucmess', 'Hecho!!!');  
                            
            return redirect("/Pacientes/$idpac");
        }     
    }

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
