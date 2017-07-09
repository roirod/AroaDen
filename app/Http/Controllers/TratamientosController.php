<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Pacientes;
use App\Models\Servicios;
use App\Models\Tratampacien;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class TratamientosController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Pacientes';
        $this->other_route = 'Citas';        
        $this->views_folder = 'trat';
    }   

    public function index()
    { }

    public function create(Request $request)
    { }

    public function crea(Request $request)
    {     
        $idpac = $request->input('idpac');

        $this->redirectIfIdIsNull($idpac, $this->main_route);

        $servicios = DB::table('servicios')
                    ->whereNull('deleted_at')
                    ->orderBy('nomser', 'ASC')
                    ->get();

        $pacientes = DB::table('pacientes')
                    ->where('idpac', $idpac)
                    ->whereNull('deleted_at')
                    ->first();

        $surname = $pacientes->surname;
        $name = $pacientes->name;

        return view($this->views_folder.'.crea', [
            'request' => $request,
            'servicios' => $servicios,
            'idpac' => $idpac,
            'surname' => $surname,
            'name' => $name
        ]);
    }

    public function selcrea(Request $request)
    {     
        $idser = $request->input('idser');
        $idpac = $request->input('idpac');
        $surname = $request->input('surname');
        $name = $request->input('name');     

        $this->redirectIfIdIsNull($idpac, $this->main_route);

        $servicio = DB::table('servicios')
                    ->where('idser', $idser)
                    ->whereNull('deleted_at')
                    ->first();
        
        $personal = DB::table('personal')
                    ->whereNull('deleted_at')
                    ->orderBy('ape', 'ASC')
                    ->get();
        
        return view($this->views_folder.'.selcrea', [
            'request' => $request,
            'servicio' => $servicio,
            'personal' => $personal,
            'idpac' => $idpac,
            'surname' => $surname,
            'name' => $name
        ]);
    }

    public function store(Request $request)
    {
        $idpac = $request->input('idpac');

        $this->redirectIfIdIsNull($idpac, $this->main_route);   
                  
        $validator = Validator::make($request->all(), [
            'idpac' => 'required',
            'idser' => 'required',
            'price' => 'required',
            'units' => 'required',
            'paid' => 'required',
            'date' => 'required|date',
            'tax' => 'max:12',
            'per1' => '',
            'per2' => ''
        ]);
            
        if ($validator->fails()) {
            return redirect("/$this->main_route/$idpac")
                         ->withErrors($validator)
                         ->withInput();
        } else {

            $idpac = $this->sanitizeData( $request->input('idpac') );
            $idser = $this->sanitizeData( $request->input('idser') );
            $price = $this->sanitizeData( $request->input('price') );
            $units = $this->sanitizeData( $request->input('units') );
            $paid = $this->sanitizeData( $request->input('paid') );
            $date = $this->sanitizeData( $request->input('date') );
            $tax = $this->sanitizeData( $request->input('tax') );
            $per1 = $this->sanitizeData( $request->input('per1') );
            $per2 = $this->sanitizeData( $request->input('per2') );      

            Tratampacien::create([
                'idpac' => $idpac,
                'idser' => $idser,
                'price' => $price,
                'units' => $units,
                'paid' => $paid,
                'date' => $date,
                'tax' => $tax,
                'per1' => $per1,
                'per2' => $per2
            ]);
              
            $request->session()->flash($success_message_name, Lang::get('aroaden.success_message') );  
                            
            return redirect("/$this->main_route/$idpac");
        }     
    }

    public function show($id)
    { }

    public function edit(Request $request, $idpac, $idtra)
    {
        $this->redirectIfIdIsNull($idpac, $this->main_route);
        $this->redirectIfIdIsNull($idtra, $this->main_route);

        $idpac = $this->sanitizeData($idpac);
        $idtra = $this->sanitizeData($idtra);
    
        $tratampa = DB::table('tratampacien')
            ->join('servicios','tratampacien.idser','=','servicios.idser')
            ->select('tratampacien.*','servicios.nomser')
            ->where('idtra', $idtra)
            ->first();

        $personal = DB::table('personal')->whereNull('deleted_at')->get();

        return view($this->views_folder.'.edit', [
            'request' => $request,
            'tratampa' => $tratampa,
            'personal' => $personal,
            'idtra' => $idtra,
            'idpac' => $idpac
        ]);
    }

    public function update(Request $request, $idtra)
    {
        $this->redirectIfIdIsNull($request->input('idpac'), $this->main_route);
        $this->redirectIfIdIsNull($idtra, $this->main_route);

        $idpac = $this->sanitizeData($request->input('idpac'));
        $idtra = $this->sanitizeData($idtra);  
                  
        $validator = Validator::make($request->all(), [
            'paid' => 'required',
            'date' => 'required|date',
            'per1' => '',
            'per2' => ''
        ]);
            
        if ($validator->fails()) {
            return redirect("/$this->other_route/$idpac/$idtra/edit")
                         ->withErrors($validator)
                         ->withInput();
        } else {

            $paid = htmlentities (trim($request->input('paid')),ENT_QUOTES,"UTF-8");
            $date = htmlentities (trim($request->input('date')),ENT_QUOTES,"UTF-8");
            $per1 = htmlentities (trim($request->input('per1')),ENT_QUOTES,"UTF-8");
            $per2 = htmlentities (trim($request->input('per2')),ENT_QUOTES,"UTF-8");            
    
            $tratampacien = Tratampacien::find($idtra);
            
            $tratampacien->paid = htmlentities (trim($paid),ENT_QUOTES,"UTF-8");
            $tratampacien->date = htmlentities (trim($date),ENT_QUOTES,"UTF-8");            
            $tratampacien->per1 = htmlentities (trim($per1),ENT_QUOTES,"UTF-8");
            $tratampacien->per2 = htmlentities (trim($per2),ENT_QUOTES,"UTF-8");
                                                
            $tratampacien->save();
              
            $request->session()->flash($success_message_name, Lang::get('aroaden.success_message') );  
                            
            return redirect("/$this->main_route/$idpac");
        }     
    }

    public function del(Request $request, $idpac, $idtra)
    {         
        $idpac = $this->sanitizeData($idpac); 
        $idtra = $this->sanitizeData($idtra);  

        $this->redirectIfIdIsNull($idtra, $this->main_route);
        $this->redirectIfIdIsNull($idpac, $this->main_route);

        $tratampa = DB::table('tratampacien')
            ->join('servicios','tratampacien.idser','=','servicios.idser')
            ->select('tratampacien.*','servicios.nomser')
            ->where('idtra', $idtra)
            ->first(); 

        return view($this->views_folder.'.del', [
            'request' => $request,
            'tratampa' => $tratampa,
            'idtra' => $idtra,
            'idpac' => $idpac
        ]);
    }

    public function destroy(Request $request, $idtra)
    {               
        $idpac = $this->sanitizeData($request->input('idpac'));
        $idtra = $this->sanitizeData($idtra);  

        $this->redirectIfIdIsNull($idtra, $this->main_route);
        $this->redirectIfIdIsNull($idpac, $this->main_route);
                
        $tratampacien = Tratampacien::find($idtra);
      
        $tratampacien->delete();

        $request->session()->flash($success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect("$this->main_route/$idpac");
    }
}
