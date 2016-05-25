<?php

namespace App\Http\Controllers;

use DB;
use App\personal;

use Carbon\Carbon;
use Storage;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class PersonalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {   
        $numpag = 11;

        $personal = DB::table('personal')->orderBy('ape', 'ASC')->orderBy('nom', 'ASC')->paginate($numpag);
              
        return view('per.index', [
          'personal' => $personal,
          'request' => $request,
        ]);   
    }
  
    public function ver(Request $request)
    {   
        $busca = $request->input('busca');
    
        if ( isset($busca) ) {
          $busca = htmlentities (trim($busca),ENT_QUOTES,"UTF-8");        
          $personal = DB::table('personal')->where('ape','LIKE','%'.$busca.'%')->orderBy('ape','ASC')->orderBy('nom','ASC')->get();
        } 
        
        return view('per.ver', [
          'request' => $request,
          'personal' => $personal,
          'busca' => $busca
        ]);   
    }   

    public function show(Request $request,$idper)
    {
        if (empty($idper)) {
          return redirect("/Personal"); 
        }

        $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");
          
        $this->dircrea($idper);
           
        $personal = DB::table('personal')->where('idper', $idper)->first();
                       
        $trabajos = DB::table('tratampacien')
                ->join('pacientes', 'tratampacien.idpac','=','pacientes.idpac')
                ->join('servicios', 'tratampacien.idser','=','servicios.idser')
                ->select('tratampacien.*','pacientes.apepac','pacientes.nompac','servicios.nomser')
                ->where('per1', $idper)
                ->orWhere('per2', $idper)
                ->orderBy('fecha' , 'ASC')
                ->get();    
            
        $count = DB::table('tratampacien')
                      ->where('per1', $idper)
                      ->orWhere('per2', $idper) 
                      ->count(); 

        return view('per.show', [
            'request' => $request,
            'personal' => $personal,
            'trabajos' => $trabajos,
            'count' => $count,
            'idper' => $idper
        ]);       
    }

    public function create(Request $request)
    {
          return view('per.create', ['request' => $request]);   
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
                   'nom' => 'required|max:44',
                   'ape' => 'required|max:77',
                   'dni' => 'unique:personal|max:12',
                   'tel1' => 'max:11',
                   'tel2' => 'max:11',
                   'cargo' => 'max:44',
                   'notas' => '',
                   'direc' => 'max:111',
                   'pobla' => 'max:111',
                   'fenac' => 'date'
        ]);
            
        if ($validator->fails()) {
             return redirect('/Personal/create')
                         ->withErrors($validator)
                         ->withInput();
        } else {

            $nom = ucfirst(strtolower( $request->input('nom') ) );
            $ape = ucwords(strtolower( $request->input('ape') ) );
            $direc = ucfirst(strtolower( $request->input('direc') ) );
            $pobla = ucfirst(strtolower( $request->input('pobla') ) );
            $notas = ucfirst(strtolower( $request->input('notas') ) );
            
            $nom = htmlentities (trim($nom),ENT_QUOTES,"UTF-8");
            $ape = htmlentities (trim($ape),ENT_QUOTES,"UTF-8");
            $cargo = htmlentities (trim($request->input('cargo')),ENT_QUOTES,"UTF-8");
            $dni = htmlentities (trim($request->input('dni')),ENT_QUOTES,"UTF-8");
            $tel1 = htmlentities (trim($request->input('tel1')),ENT_QUOTES,"UTF-8");
            $tel2 = htmlentities (trim($request->input('tel2')),ENT_QUOTES,"UTF-8");
            $direc = htmlentities (trim($direc),ENT_QUOTES,"UTF-8");
            $pobla = htmlentities (trim($pobla),ENT_QUOTES,"UTF-8");
            $fenac = htmlentities (trim($request->input('fenac')),ENT_QUOTES,"UTF-8");
            $notas = htmlentities (trim($notas),ENT_QUOTES,"UTF-8");
                        
            personal::create([
                  'nom' => $nom,
                  'ape' => $ape,
                  'dni' => $dni,
                  'tel1' => $tel1,
                  'tel2' => $tel2,
                  'cargo' => $cargo,
                  'notas' => $notas,
                  'direc' => $direc,
                  'pobla' => $pobla,
                  'fenac' => $fenac
            ]);
          
            $request->session()->flash('sucmess', 'Hecho!!!');  
                        
            return redirect('Personal/create');
        }      
    }
  
    public function edit(Request $request,$idper)
    {
        if (empty($idper)) {
          return redirect("/Personal"); 
        }

        $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");
         
         $personal = personal::find($idper); 

         return view('per.edit', ['request' => $request,
                                  'personal' => $personal,
                                  'idper' => $idper]);
    }

    public function update(Request $request,$idper)
    {
        if ( null === $idper ) {
            return redirect('Personal');
        }

        $validator = Validator::make($request->all(),[
                   'nom' => 'required|max:44',
                   'ape' => 'required|max:77',
                   'dni' => 'unique:personal|max:12',
                   'tel1' => 'max:11',
                   'tel2' => 'max:11',
                   'cargo' => 'max:44',
                   'notas' => '',
                   'direc' => 'max:111',
                   'pobla' => 'max:111',
                   'fenac' => 'date'
        ]);
            
        if ($validator->fails()) {
             return redirect("Personal/$idper/edit")
                         ->withErrors($validator)
                         ->withInput();
        } else {        

            $fenac = $request->input('fenac');
            
            $regex = '/^(18|19|20)\d\d[\/\-.](0[1-9]|1[012])[\/\-.](0[1-9]|[12][0-9]|3[01])$/';
            
            if ( preg_match($regex, $fenac) ) {  } else {
               $request->session()->flash('errmess', 'Fecha/s incorrecta');
               return redirect("Personal/$idper/edit");
            }
            
            $idper = $request->input('idper');

            $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");
            
            $personal = personal::find($idper);
                    
            $nom = ucfirst(strtolower( $request->input('nom') ) );
            $ape = ucwords(strtolower( $request->input('ape') ) );
            $notas = ucfirst(strtolower( $request->input('notas') ) );
            $direc = ucfirst(strtolower( $request->input('direc') ) );
            $pobla = ucfirst(strtolower( $request->input('pobla') ) );
            
            $personal->nom = htmlentities (trim($nom),ENT_QUOTES,"UTF-8");
            $personal->ape = htmlentities (trim($ape),ENT_QUOTES,"UTF-8");
            $personal->dni = htmlentities (trim($request->input('dni')),ENT_QUOTES,"UTF-8");
            $personal->tel1 = htmlentities (trim($request->input('tel1')),ENT_QUOTES,"UTF-8");
            $personal->tel2 = htmlentities (trim($request->input('tel2')),ENT_QUOTES,"UTF-8");
            $personal->cargo = htmlentities (trim($request->input('cargo')),ENT_QUOTES,"UTF-8");
            $personal->notas = htmlentities (trim($notas),ENT_QUOTES,"UTF-8");
            $personal->direc = htmlentities (trim($direc),ENT_QUOTES,"UTF-8");
            $personal->pobla = htmlentities (trim($pobla),ENT_QUOTES,"UTF-8");
            $personal->fenac = htmlentities (trim($request->input('fenac')),ENT_QUOTES,"UTF-8");
            
            $personal->save();

            $request->session()->flash('sucmess', 'Hecho!!!');

            return redirect("Personal/$idper");
        }   
    }

    public function file(Request $request,$idper)
    {
        if (empty($idper)) {
          return redirect("/Personal"); 
        }

        $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");

        $this->dircrea($idper);

        $perdir = "/perdir/$idper/";

        $files = Storage::files($perdir);

        $url = url("Personal/$idper");
        
        return view('per.file', [
          'request' => $request,
          'idper' => $idper,
          'files' => $files,
          'url' => $url
        ]);
    }

    public function upload(Request $request)
    {
        $idper = $request->input('idper');

        if (empty($idper)) {
          return redirect("/Personal"); 
        }

        $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");

        $perdir = storage_path("app/perdir/$idper");
         
        $files = $request->file('files');
          
        $ficount = count($files);
        $uploadcount = 0;
          
        foreach($files as $file) {
              $filename = $file->getClientOriginalName();
              $file->move($perdir, $filename);
            $uploadcount ++;  
        }
        
        if($uploadcount == $ficount){
            return redirect("Personal/$idper/file");
        } else {
            $request->session()->flash('error', 'error!!!');
            return redirect("Personal/$idper/file");
        }
    }
    
    public function download(Request $request,$idper,$file)
    {   
        $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");

        $perdir = storage_path("app/perdir/$idper");

        $filedown = $perdir.'/'.$file;

        return response()->download($filedown);
    } 

    public function filerem(Request $request)
    {
        $idper = $request->input('idper');

        $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");     

        $perdir = storage_path("app/perdir/$idper");

        $file = $request->input('filerem');

        $filerem = $perdir.'/'.$file;
          
        unlink($filerem);   
          
        return redirect("Personal/$idper/file");
    }  
   
    public function del(Request $request,$idper)
    {
        if (empty($idper)) {
          return redirect("/Personal"); 
        }

        $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");
          
        return view('per.del', [
          'request' => $request,
          'idper' => $idper
        ]);
    }
 
    public function destroy(Request $request,$idper)
    {      
        if (empty($idper)) {
          return redirect("/Personal"); 
        }

        $idper = htmlentities (trim($idper),ENT_QUOTES,"UTF-8");    
        
        $personal = personal::find($idper);
      
        $personal->delete();
        
        return redirect('Personal');
    }

    public function dircrea($idper)
    {               
        $perdir = "/perdir/$idper/";

        if ( ! Storage::exists($perdir) ) { 
            Storage::makeDirectory($perdir, 0770, true);
        }

        $thumbdir = $perdir.'/.thumbdir';

        if ( ! Storage::exists($thumbdir) ) { 
            Storage::makeDirectory($thumbdir, 0770, true);
        }
    }  
}
