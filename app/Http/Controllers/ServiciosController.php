<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Servicios;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class ServiciosController extends BaseController
{
    private $iva_tipos;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->iva_tipos = require(base_path().'/config/iva_tipos.php');
    }

    public function index(Request $request)
    {			
		$servicios = DB::table('servicios')
                        ->whereNull('deleted_at')
                        ->orderBy('nomser', 'ASC')
                        ->get();		

        $count = DB::table('servicios')
                        ->whereNull('deleted_at')
                        ->count();

        return view('serv.index', [
            'servicios' => $servicios,
            'request' => $request,
            'count' => $count
        ]);          
    }

    public function list(Request $request)
    {   
        $busca = $request->input('busca');
        $busca = htmlentities (trim($busca),ENT_QUOTES,"UTF-8");

        $data = $this->consultaItems($busca);

        header('Content-type: application/json; charset=utf-8');

        echo json_encode($data);

        exit();
    }  

    public function create(Request $request)
    {
        $iva_tipos = $this->iva_tipos;
   	 
    	return view('serv.create', [
            'request' => $request,
            'ivatp' => $iva_tipos
        ]);	
    }
 
    public function store(Request $request)
    {          
        $nomser = ucfirst(strtolower( $request->input('nomser') ) );

        $nomser = htmlentities (trim($nomser),ENT_QUOTES,"UTF-8");
        $precio = htmlentities (trim( $request->input('precio')),ENT_QUOTES,"UTF-8");
        $iva = htmlentities (trim( $request->input('iva')),ENT_QUOTES,"UTF-8");     

        $servicios = DB::table('servicios')
                        ->orderBy('nomser','ASC')
                        ->get();
          
        foreach ($servicios as $servi) {
           if ($servi->nomser == $nomser) {
               $request->session()->flash('errmess', "Nombre: $nomser - ya en uso, use cualquier otro.");
               return redirect('Servicios/create')->withInput();
           }
        } 

    	$validator = Validator::make($request->all(), [
            'nomser' => 'required|unique:servicios|max:111',
            'precio' => 'required',
            'iva' => ''
	    ]);
    	        
        if ($validator->fails()) {
	        return redirect('Servicios/create')
	                     ->withErrors($validator)
	                     ->withInput();
	     } else {
	        	
		    servicios::create([
                'nomser' => $nomser,
		        'precio' => $precio,
		        'iva' => $iva
		    ]);
		      
		    $request->session()->flash('sucmess', 'Hecho!!!');	
	        	        	
	        return redirect('Servicios/create');
        }     
    }
 
    public function show($id)
    { }

    public function edit(Request $request,$idser)
    {

        if ( null === $idser ) {
            return redirect('Servicios');
        }

        $idser = htmlentities (trim($idser),ENT_QUOTES,"UTF-8");

        $servicio = servicios::find($idser);

        $iva_tipos = $this->iva_tipos;    

        return view('serv.edit', [
            'request' => $request,
            'servicio' => $servicio,
            'ivatp' => $iva_tipos,
            'idser' => $idser
        ]);
    }

    public function update(Request $request,$idser)
    {
        if ( null === $idser ) {
            return redirect('Servicios');
        }

        $idser = htmlentities (trim($idser),ENT_QUOTES,"UTF-8");

        $nomser = ucfirst(strtolower( $request->input('nomser') ) );

        $nomser = htmlentities (trim($nomser),ENT_QUOTES,"UTF-8");
        $precio = htmlentities (trim( $request->input('precio')),ENT_QUOTES,"UTF-8");
        $iva = htmlentities (trim( $request->input('iva')),ENT_QUOTES,"UTF-8");

        $servicios = DB::table('servicios')
                        ->orderBy('nomser','ASC')
                        ->get();

        $servi = servicios::find($idser);

        if ($servi->nomser != $nomser) { 
            foreach ($servicios as $servi) {
               if ($servi->nomser == $nomser) {
                   $request->session()->flash('errmess', "Nombre: $nomser - ya en uso, use cualquier otro.");
                   return redirect("Servicios/$idser/edit");
               }
            }
        }

        $validator = Validator::make($request->all(), [
            'nomser' => 'required|max:111',
            'precio' => 'required',
            'iva' => 'required'
        ]);
            
        if ($validator->fails()) {
            return redirect("Servicios/$idser/edit")
                         ->withErrors($validator);
        } else {        
            
            $servicios = servicios::find($idser);
                 
            $servicios->nomser = $nomser;
            $servicios->precio = $precio;
            $servicios->iva = $iva;         
            
            $servicios->save();

            $request->session()->flash('sucmess', 'Hecho!!!');

            return redirect('Servicios');
        }   
    }

    public function del(Request $request,$idser)
    {
        if ( null === $idser ) {
            return redirect('Servicios');
        }   

        $idser = htmlentities (trim($idser),ENT_QUOTES,"UTF-8");

        $servicio = servicios::find($idser);

        return view('serv.del', [
            'request' => $request,
            'servicio' => $servicio,
            'idser' => $idser
        ]);
    }
 
    public function destroy(Request $request,$idser)
    {          
        if ( null === $idser ) {
            return redirect('Servicios');
        }   
        
        $idser = htmlentities (trim($idser),ENT_QUOTES,"UTF-8");

        servicios::destroy($idser); 

        $request->session()->flash('sucmess', 'Hecho!!!');
        
        return redirect('Servicios');
    }

    public function consultaItems($busca)
    {
        $count = DB::table('servicios')
                    ->whereNull('deleted_at')
                    ->count();

        if ($count === 0) {
            $data['servicios'] = false;
            $data['count'] = false;       
            $data['msg'] = ' No hay servicios en la base de datos. ';

            return $data;
        }

        $servicios = DB::table('servicios')
                        ->select('idser', 'nomser', 'precio', 'iva')
                        ->whereNull('deleted_at')
                        ->where('nomser','LIKE','%'.$busca.'%')
                        ->orderBy('nomser','ASC')
                        ->get();

        $count = DB::table('servicios')
                    ->whereNull('deleted_at')
                    ->where('nomser','LIKE','%'.$busca.'%')
                    ->count();

        return $this->recorrerItems($servicios, $count);
    }

    public function recorrerItems($servicios, $count)
    {
        $data = [];

        if ($count === 0) {

            $data['servicios'] = false;
            $data['count'] = false;       
            $data['msg'] = ' No hay resultados. ';

        } else {

            $data['servicios'] = $servicios;
            $data['count'] = $count;        
            $data['msg'] = false;
            
        }

        return $data;
    }
}
