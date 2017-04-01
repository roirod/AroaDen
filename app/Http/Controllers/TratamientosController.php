<?php

namespace App\Http\Controllers;

use DB;
use App\pacientes;
use App\servicios;
use App\tratampacien;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class TratamientosController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }   

    public function index()
    { }

    public function create(Request $request)
    { }

    public function crea(Request $request)
    {     
        $idpac = $request->input('idpac');

        if ( empty($idpac) ) {
            return redirect('Pacientes');
        }
        
        $servicios = DB::table('servicios')
                    ->whereNull('deleted_at')
                    ->orderBy('nomser', 'ASC')
                    ->get();

        $pacientes = DB::table('pacientes')
                    ->where('idpac', $idpac)
                    ->whereNull('deleted_at')
                    ->first();

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

        $servicio = DB::table('servicios')
                    ->where('idser', $idser)
                    ->whereNull('deleted_at')
                    ->first();
        
        $personal = DB::table('personal')
                    ->whereNull('deleted_at')
                    ->orderBy('ape', 'ASC')
                    ->get();
        
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
            'per2' => ''
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

            tratampacien::create([
                'idpac' => $idpac,
                'idser' => $idser,
                'precio' => $precio,
                'canti' => $canti,
                'pagado' => $pagado,
                'fecha' => $fecha,
                'iva' => $iva,
                'per1' => $per1,
                'per2' => $per2
            ]);
              
            $request->session()->flash('sucmess', 'Hecho!!!');  
                            
            return redirect("/Pacientes/$idpac");
        }     
    }

    public function show($id)
    { }

    public function edit(Request $request,$idpac,$idtra)
    {
        if ( null === $idpac ) {
            return redirect('Pacientes');
        }
        
        if ( null === $idtra ) {
            return redirect('Pacientes');
        }

        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");
        $idtra = htmlentities (trim($idtra),ENT_QUOTES,"UTF-8");

        $tratampa = DB::table('tratampacien')
            ->join('servicios','tratampacien.idser','=','servicios.idser')
            ->select('tratampacien.*','servicios.nomser')
            ->where('idtra', $idtra)
            ->first();

        $personal = DB::table('personal')->whereNull('deleted_at')->get();

        return view('trat.edit', [
            'request' => $request,
            'tratampa' => $tratampa,
            'personal' => $personal,
            'idtra' => $idtra,
            'idpac' => $idpac
        ]);
    }

    public function update(Request $request,$idtra)
    {
        if ( null === $idtra ) {
            return redirect('Pacientes');
        }

        $idtra = htmlentities(trim($idtra),ENT_QUOTES,"UTF-8");
        $idpac = htmlentities (trim($request->input('idpac')),ENT_QUOTES,"UTF-8");

        if ( null === $idpac ) {
            return redirect('Pacientes');
        }     
                  
        $validator = Validator::make($request->all(), [
            'pagado' => 'required',
            'fecha' => 'required|date',
            'per1' => '',
            'per2' => ''
        ]);
            
        if ($validator->fails()) {
            return redirect("/Citas/$idpac/$idtra/edit")
                         ->withErrors($validator)
                         ->withInput();
        } else {

            $pagado = htmlentities (trim($request->input('pagado')),ENT_QUOTES,"UTF-8");
            $fecha = htmlentities (trim($request->input('fecha')),ENT_QUOTES,"UTF-8");
            $per1 = htmlentities (trim($request->input('per1')),ENT_QUOTES,"UTF-8");
            $per2 = htmlentities (trim($request->input('per2')),ENT_QUOTES,"UTF-8");            
    
            $tratampacien = tratampacien::find($idtra);
            
            $tratampacien->pagado = htmlentities (trim($pagado),ENT_QUOTES,"UTF-8");
            $tratampacien->fecha = htmlentities (trim($fecha),ENT_QUOTES,"UTF-8");            
            $tratampacien->per1 = htmlentities (trim($per1),ENT_QUOTES,"UTF-8");
            $tratampacien->per2 = htmlentities (trim($per2),ENT_QUOTES,"UTF-8");
                                                
            $tratampacien->save();
              
            $request->session()->flash('sucmess', 'Hecho!!!');  
                            
            return redirect("/Pacientes/$idpac");
        }     
    }

    public function del(Request $request,$idpac,$idtra)
    {         
        $idtra = htmlentities (trim($idtra),ENT_QUOTES,"UTF-8");
        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        if ( null === $idtra ) {
            return redirect('Pacientes');
        }

        if ( null === $idpac ) {
            return redirect('Pacientes');
        }

        $tratampa = DB::table('tratampacien')
            ->join('servicios','tratampacien.idser','=','servicios.idser')
            ->select('tratampacien.*','servicios.nomser')
            ->where('idtra', $idtra)
            ->first(); 

        return view('trat.del', [
            'request' => $request,
            'tratampa' => $tratampa,
            'idtra' => $idtra,
            'idpac' => $idpac
        ]);
    }

    public function destroy(Request $request,$idtra)
    {               
        $idtra = htmlentities (trim($idtra),ENT_QUOTES,"UTF-8");
        $idpac = htmlentities(trim($request->input('idpac')),ENT_QUOTES,"UTF-8");

        if ( null === $idtra ) {
            return redirect('Pacientes');
        }

        if ( null === $idpac ) {
            return redirect('Pacientes');
        } 
        
        $idtra = htmlentities (trim($idtra),ENT_QUOTES,"UTF-8");
        
        $tratampacien = tratampacien::find($idtra);
      
        $tratampacien->delete();

        $request->session()->flash('sucmess', 'Hecho!!!');
        
        return redirect("Pacientes/$idpac");
    }
}
