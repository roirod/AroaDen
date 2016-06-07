<?php

namespace App\Http\Controllers;

use DB;
use App\servicios;

use Auth;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;


class ServiciosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {			
		$servicios = DB::table('servicios')->orderBy('nomser', 'ASC')->get();				

        return view('serv.index', [
            'servicios' => $servicios,
            'request' => $request
        ]);          
    }

    public function ver(Request $request)
    {   
        $busca = $request->input('busca');
    
        if ( isset($busca) ) {
            $busca = htmlentities (trim($busca),ENT_QUOTES,"UTF-8");        
            $servicios = DB::table('servicios')
                        ->where('nomser','LIKE','%'.$busca.'%')
                        ->orderBy('nomser','ASC')
                        ->get();
        } 
             
        return view('serv.ver', [
            'servicios' => $servicios,
            'busca' => $busca,
            'request' => $request
        ]);    
    }     

    public function create(Request $request)
    {
    	$ivatp = ["0%" => 0,"4%" => 4,"10%" => 10,"21%" => 21]; 
    	 
    	return view('serv.create', [
            'request' => $request,
            'ivatp' => $ivatp
        ]);	
    }
 

    public function store(Request $request)
    {          
        $nomser = ucfirst(strtolower( $request->input('nomser') ) );
        $notas = ucfirst(strtolower( $request->input('notas') ) );

        $nomser = htmlentities (trim($nomser),ENT_QUOTES,"UTF-8");
        $precio = htmlentities (trim( $request->input('precio')),ENT_QUOTES,"UTF-8");
        $iva = htmlentities (trim( $request->input('iva')),ENT_QUOTES,"UTF-8");
        $notas = htmlentities (trim( $notas),ENT_QUOTES,"UTF-8");        

        $servicios = DB::table('servicios')
                        ->orderBy('nomser','ASC')
                        ->get();
          
        foreach ($servicios as $servi) {
           if ($servi->nomser == $nomser) {
               $request->session()->flash('errmess', 'Nombre en uso, use cualquier otro.');
               return redirect('Servicios/create');
           }
        } 

    	$validator = Validator::make($request->all(), [
            'nomser' => 'required|unique:servicios|max:77',
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
	        	        	
	        return redirect('Servicios');
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

        $ivatp = ["0%" => 0,"4%" => 4,"10%" => 10,"21%" => 21];      

        return view('serv.edit', [
            'request' => $request,
            'servicio' => $servicio,
            'ivatp' => $ivatp,
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
          
        foreach ($servicios as $servi) {
           if ($servi->nomser == $nomser) {
               $request->session()->flash('errmess', 'Nombre en uso, use cualquier otro.');
               return redirect("Servicios/$idser/edit");
           }
        } 

        $validator = Validator::make($request->all(), [
            'nomser' => 'required|unique:servicios|max:77',
            'precio' => 'required',
            'iva' => ''
        ]);
            
        if ($validator->fails()) {
            return redirect("Servicios/$idser/edit")
                         ->withErrors($validator)
                         ->withInput();
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
        
        $servicios = servicios::find($idser);
      
        $servicios->delete();

        $request->session()->flash('sucmess', 'Hecho!!!');
        
        return redirect('Servicios');
    }
}
