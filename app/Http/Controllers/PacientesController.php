<?php

namespace App\Http\Controllers;

use DB;
use App\pacientes;
use App\ficha;

use Carbon\Carbon;
use Storage;
use Html;
use Image;
use Validator;

use Illuminate\Http\Request;
use App\Http\Requests;


class PacientesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {  	
    	$numpag = 100;

    	$pacientes = DB::table('pacientes')
                    ->orderBy('apepac', 'ASC')
                    ->orderBy('nompac', 'ASC')
                    ->paginate($numpag);
                    
    	return view('pac.index', [
    		'pacientes' => $pacientes,
         	'request' => $request
        ]);   
    }
  
    public function ver(Request $request)
    {  	
    	$busca = $request->input('busca');
   	
	  	if ( isset($busca) ) {
		  $busca = htmlentities (trim($busca),ENT_QUOTES,"UTF-8"); 		  
  		  $pacientes = DB::table('pacientes')
                    ->where('apepac','LIKE','%'.$busca.'%')
                    ->orderBy('apepac','ASC')
                    ->orderBy('nompac','ASC')
                    ->get();
  		} 
  		     
    	return view('pac.ver', [
    		'pacientes' => $pacientes,
            'busca' => $busca,
         	'request' => $request
        ]);    
    }   
  
    public function show(Request $request,$idpac)
    {
	    if ( null == $idpac ) {
    	    return redirect('Pacientes');
    	}

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $this->dircrea($idpac);

        $fotoper = url("/app/pacdir/$idpac/.fotoper.jpg");

	    $pacientes = DB::table('pacientes')->where('idpac', $idpac)->first();

        $citas = pacientes::find($idpac)
                    ->citas()
                    ->orderBy('diacit', 'DESC')
                    ->orderBy('horacit', 'DESC')
                    ->get();

		$tratampacien = DB::table('tratampacien')
		            ->join('servicios','tratampacien.idser','=','servicios.idser')
		            ->select('tratampacien.*','servicios.nomser')
		            ->where('idpac', $idpac)
		            ->orderBy('fecha','DESC')
		            ->get(); 
	    
	    $suma = DB::table('tratampacien')
				    ->selectRaw('SUM(canti*precio) AS sumtot, SUM(pagado) AS totpaga, SUM(canti*precio)-SUM(pagado) AS resto')
				    ->where('idpac', $idpac)
				    ->get();

	  	$fenac = trim($pacientes->fenac);

	  	if (isset($fenac)) {
		  	$Fecha = explode("-",$fenac,3);

		  	$Fecha0 = $Fecha[0];
		  	$Fecha1 = $Fecha[1];
		  	$Fecha2 = $Fecha[2];
		  	  
		  	$Edad = Carbon::createFromDate($Fecha0,$Fecha1,$Fecha2)->age;
	  	} else {
	  		$Edad = 0;
	  	}

        return response()->view('pac.show',[
            'request' => $request,
            'pacientes' => $pacientes,
            'citas' => $citas,
            'tratampacien' => $tratampacien,
            'suma' => $suma,
            'idpac' => $idpac,
            'fotoper' => $fotoper,
            'Edad' => $Edad
        ])
           ->header('Expires', 'Sun, 01 Jan 1966 00:00:00 GMT')
           ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
           ->header('Cache-Control', ' post-check=0, pre-check=0', FALSE)
           ->header('Pragma', 'no-cache');     
    }

    public function create(Request $request)
    {
		return view('pac.create', ['request' => $request]);	
    }

    public function store(Request $request)
    {
        $pacientes = DB::table('pacientes')
                        ->orderBy('dni','ASC')
                        ->get();
          
        $dni = htmlentities (trim($request->input('dni')),ENT_QUOTES,"UTF-8");
          
        foreach ($pacientes as $pacien) {
           if ($pacien->dni == $dni) {
               $request->session()->flash('errmess', 'DNI en uso, use cualquier otro.');
               return redirect('Pacientes/create');
           }
        }   

        $validator = Validator::make($request->all(),[
    		'nompac' => 'required|max:44',
            'apepac' => 'required|max:77',
            'direc' => 'max:111',
            'pobla' => 'max:111',
            'dni' => 'unique:pacientes|max:12',
            'tel1' => 'max:11',
            'tel2' => 'max:11',
            'tel3' => 'max:11',
            'sexo' => 'max:12',
            'fenac' => 'date',
            'notas' => ''
	    ]);
            
        if ($validator->fails()) {
	         return redirect('/Pacientes/create')
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {

        	$nompac = ucfirst(strtolower( $request->input('nompac') ) );
        	$apepac = ucwords(strtolower( $request->input('apepac') ) );
        	$notas = ucfirst(strtolower( $request->input('notas') ) );
        	$direc = ucfirst(strtolower( $request->input('direc') ) );
        	$pobla = ucfirst(strtolower( $request->input('pobla') ) );
        	
			$nompac = htmlentities (trim($nompac),ENT_QUOTES,"UTF-8");
			$apepac = htmlentities (trim($apepac),ENT_QUOTES,"UTF-8");
			$dni = htmlentities (trim($request->input('dni')),ENT_QUOTES,"UTF-8");
			$tel1 = htmlentities (trim($request->input('tel1')),ENT_QUOTES,"UTF-8");
			$tel2 = htmlentities (trim($request->input('tel2')),ENT_QUOTES,"UTF-8");
			$tel3 = htmlentities (trim($request->input('tel3')),ENT_QUOTES,"UTF-8");
			$sexo = htmlentities (trim($request->input('sexo')),ENT_QUOTES,"UTF-8");
			$notas = htmlentities (trim($notas),ENT_QUOTES,"UTF-8");
			$direc = htmlentities (trim($direc),ENT_QUOTES,"UTF-8");
			$pobla = htmlentities (trim($pobla),ENT_QUOTES,"UTF-8");
			$fenac = htmlentities (trim($request->input('fenac')),ENT_QUOTES,"UTF-8");
        	        	

            $insertGetId = pacientes::insertGetId([
	          'nompac' => $nompac,
	          'apepac' => $apepac,
	          'dni' => $dni,
	          'tel1' => $tel1,
	          'tel2' => $tel2,
	          'tel3' => $tel3,
	          'sexo' => $sexo,
	          'notas' => $notas,
	          'direc' => $direc,
	          'pobla' => $pobla,
	          'fenac' => $fenac
		    ]);

            ficha::create([
              'idpac' => $insertGetId
            ]);
	      
	        $request->session()->flash('sucmess', 'Hecho!!!');	
        	        	
        	return redirect('Pacientes/create');
        }      
    }
  
    public function edit(Request $request,$idpac)
    {
    	if ( null === $idpac ) {
    		return redirect('Pacientes');
    	}

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

    	$pacientes = pacientes::find($idpac);

    	return view('pac.edit', [
            'request' => $request,
    		'pacientes' => $pacientes,
    		'idpac' => $idpac
        ]);
    }

    public function update(Request $request,$idpac)
    {
    	if ( null === $idpac ) {
    		return redirect('Pacientes');
    	}

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $validator = Validator::make($request->all(),[
    		'nompac' => 'required|max:44',
            'apepac' => 'required|max:77',
            'direc' => 'max:111',
            'pobla' => 'max:111',
            'dni' => 'max:12',
            'tel1' => 'max:11',
            'tel2' => 'max:11',
            'tel3' => 'max:11',
            'sexo' => 'max:12',
            'fenac' => 'date',
            'notas' => ''
	    ]);
            
        if ($validator->fails()) {
	        return redirect("Pacientes/$idpac/edit")
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {		

			$fenac = $request->input('fenac');
			
			$regex = '/^(18|19|20)\d\d[\/\-.](0[1-9]|1[012])[\/\-.](0[1-9]|[12][0-9]|3[01])$/';
			
			if ( preg_match($regex, $fenac) ) {  } else {
		 	   $request->session()->flash('errmess', 'Fecha/s incorrecta');
		 	   return redirect("Pacientes/$idpac/edit");
		 	}
			
			$pacientes = pacientes::find($idpac);
			  		
	    	$nompac = ucfirst(strtolower( $request->input('nompac') ) );
	    	$apepac = ucwords(strtolower( $request->input('apepac') ) );
	    	$notas = ucfirst(strtolower( $request->input('notas') ) );
	    	$direc = ucfirst(strtolower( $request->input('direc') ) );
	    	$pobla = ucfirst(strtolower( $request->input('pobla') ) );
	    	
			$pacientes->nompac = htmlentities (trim($nompac),ENT_QUOTES,"UTF-8");
			$pacientes->apepac = htmlentities (trim($apepac),ENT_QUOTES,"UTF-8");
			$pacientes->dni = htmlentities (trim($request->input('dni')),ENT_QUOTES,"UTF-8");
			$pacientes->tel1 = htmlentities (trim($request->input('tel1')),ENT_QUOTES,"UTF-8");
			$pacientes->tel2 = htmlentities (trim($request->input('tel2')),ENT_QUOTES,"UTF-8");
			$pacientes->tel3 = htmlentities (trim($request->input('tel3')),ENT_QUOTES,"UTF-8");
			$pacientes->sexo = htmlentities (trim($request->input('sexo')),ENT_QUOTES,"UTF-8");
			$pacientes->notas = htmlentities (trim($notas),ENT_QUOTES,"UTF-8");
			$pacientes->direc = htmlentities (trim($direc),ENT_QUOTES,"UTF-8");
			$pacientes->pobla = htmlentities (trim($pobla),ENT_QUOTES,"UTF-8");
			$pacientes->fenac = htmlentities (trim($request->input('fenac')),ENT_QUOTES,"UTF-8");
			
			$pacientes->save();

			$request->session()->flash('sucmess', 'Hecho!!!');

			return redirect("Pacientes/$idpac");
		}   
    }

    public function ficha(Request $request,$idpac)
    {   
        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $ficha = ficha::find($idpac);

        return view('pac.ficha', [
            'request' => $request,
            'idpac' => $idpac,
            'ficha' => $ficha
        ]);
    } 

    public function fiedit(Request $request,$idpac)
    {   
        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $ficha = ficha::find($idpac);

        return view('pac.fiedit', [
            'request' => $request,
            'idpac' => $idpac,
            'ficha' => $ficha
        ]);
    }

    public function fisave(Request $request,$idpac)
    {   
        if ( null === $idpac ) {
            return redirect('Pacientes');
        }

        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");     
       
        $ficha = ficha::find($idpac);
                
        $histo = ucfirst(strtolower( $request->input('histo') ) );
        $enfer = ucfirst(strtolower( $request->input('enfer') ) );
        $medic = ucfirst(strtolower( $request->input('medic') ) );
        $aler = ucfirst(strtolower( $request->input('aler') ) );
        $notas = ucfirst(strtolower( $request->input('notas') ) );
        
        $ficha->histo = htmlentities (trim($histo),ENT_QUOTES,"UTF-8");
        $ficha->enfer = htmlentities (trim($enfer),ENT_QUOTES,"UTF-8");
        $ficha->medic = htmlentities (trim($medic),ENT_QUOTES,"UTF-8");
        $ficha->aler = htmlentities (trim($aler),ENT_QUOTES,"UTF-8");
        $ficha->notas = htmlentities (trim($notas),ENT_QUOTES,"UTF-8");
        
        $ficha->save();

        $request->session()->flash('sucmess', 'Hecho!!!');

        return redirect("Pacientes/$idpac"); 
    }  

    public function file(Request $request,$idpac)
    {
    	if ( null === $idpac ) {
    		return redirect('Pacientes');
    	}

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $this->dircrea($idpac);

        $pacdir = "/pacdir/$idpac";

        $files = Storage::files($pacdir);

        $url = url("Pacientes/$idpac");

		return view('pac.file', [
            'request' => $request,
    	  	'idpac' => $idpac,
    	  	'files' => $files,
            'url' => $url
        ]);
    }

    public function upload(Request $request)
    {	
		$idpac = $request->input('idpac');
        $fotoper = $request->input('fotoper');

		if ( null === $idpac ) {
    		return redirect('Pacientes');
    	}  

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $pacdir = storage_path("app/pacdir/$idpac");

		$files = $request->file('files');

        if ($fotoper == 1) {
            $extension = $files->getClientOriginalExtension();

            if ($extension == 'jpg' || $extension == 'png') {

                Image::make($files)->encode('jpg', 80)
                    ->resize(150, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save("$pacdir/.fotoper.jpg");

                return redirect("Pacientes/$idpac");

            } else { 
                $request->session()->flash('errmess', 'Formato no soportado, suba una imagen jpg o png.');
                return redirect("Pacientes/$idpac/file");
            }

        } else {

            $ficount = count($files);

            $uploadcount = 0;

            foreach ($files as $file) {   		     		  
       		  	$filename = $file->getClientOriginalName();

                $filedisk = "/pacdir/$idpac/$filename";

                if ( Storage::exists($filedisk) ) {
                    $mess = "El archivo: $filedisk -- existe ya en su carpeta";
                    $request->session()->flash('errmess', $mess);
                    return redirect("Pacientes/$idpac/file");
                } else {
        		  	$file->move($pacdir, $filename);
        	        $upcount ++;
                }
            }
    	     
    	    if($uploadcount == $ficount){
    	      return redirect("Pacientes/$idpac/file");
    	    } else {
    	      $request->session()->flash('errmess', 'error!!!');
    	      return redirect("Pacientes/$idpac/file");
    	    }
        }
    }

    public function download(Request $request,$idpac,$file)
    {   
        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $pacdir = storage_path("app/pacdir/$idpac");

        $filedown = $pacdir.'/'.$file;

        return response()->download($filedown);
    } 

    public function filerem(Request $request)
    {  	  
    	$idpac = $request->input('idpac');
    	$filerem = $request->input('filerem');

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $pacdir = storage_path("app/pacdir/$idpac");

        $file = $request->input('filerem');

        $filerem = $pacdir.'/'.$file;
          
        unlink($filerem);    
    	  
    	return redirect("Pacientes/$idpac/file");
    }  
   
    public function odogram(Request $request,$idpac)
    {
		if ( null === $idpac ) {
    		return redirect('Pacientes');
    	} 

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $pacdir = "/app/pacdir/$idpac";

        $odogram = $pacdir."/.odogdir/odogram.jpg";

        return response()->view('pac.odogram',[
            'request' => $request,
            'idpac' => $idpac,
            'odogram' => $odogram
        ])
           ->header('Expires', 'Sun, 01 Jan 2004 00:00:00 GMT')
           ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
           ->header('Cache-Control', ' post-check=0, pre-check=0', FALSE)
           ->header('Pragma', 'no-cache');
    }

    public function upodog(Request $request)
    {   
        if ($request->file('upodog')->isValid()) {

            $idpac = $request->input('idpac');

            $upodog = $request->file('upodog');

            if ( null === $idpac ) {
                return redirect('Pacientes');
            }  

            $extension = $request->file('upodog')->getClientOriginalExtension();

            if ( $extension != 'jpg' ) {
                $request->session()->flash('errmess', 'No es una imagen jpg');
                return redirect("Pacientes/$idpac/odogram");
            } 

            $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

            $pacdir = storage_path("app/pacdir/$idpac");

            $odogram = $pacdir."/.odogdir/";

            $upodog->move($odogram,'odogram.jpg');

            return redirect("Pacientes/$idpac/odogram");

        } else {
            return redirect("Pacientes/$idpac/odogram");
        }    
    }   

    public function downodog(Request $request,$idpac)
    {   
        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $pacdir = storage_path("app/pacdir/$idpac");

        $odogram = $pacdir."/.odogdir/odogram.jpg";

        return response()->download($odogram);
    }    
    
    public function resodog(Request $request)
    {  
    	$idpac = $request->input('idpac');

		if ( null === $idpac ) {
    		return redirect('Pacientes');
    	}     	

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");
 
		$resodog = $request->input('resodog');
		 
		if ( $resodog == 1 ) {
            $pacdir = "/pacdir/$idpac";

            $odogram = "/$pacdir/.odogdir/odogram.jpg";
            $img = '/img/odogram.jpg'; 

            Storage::delete($odogram);           

	    	Storage::copy($img, $odogram);
	    	  
	    	return redirect("/Pacientes/$idpac/odogram");

		} else {
		 	$request->session()->flash('errmess', 'Error');
		 	return redirect("/Pacientes/$idpac/odogram");
		}	
    }

    public function presup(Request $request,$idpac)
    {
        if ( null === $idpac ) {
            return redirect('Pacientes');
        } 

        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        $presup = DB::table('presup')
                    ->join('servicios','presup.idser','=','servicios.idser')
                    ->select('presup.*','servicios.nomser')
                    ->where('idpac', $idpac)
                    ->orderBy('cod','ASC')
                    ->get(); 
          
        return view('pac.presup', [
            'request' => $request,
            'idpac' => $idpac,
            'presup' => $presup
        ]);
    }    

    public function del(Request $request,$idpac)
    {    	  
    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        if ( null === $idpac ) {
            return redirect('Pacientes');
        }

        $paciente = pacientes::find($idpac);

    	return view('pac.del', [
            'request' => $request,
            'paciente' => $paciente,
    		'idpac' => $idpac
        ]);
    }
 
    public function destroy(Request $request,$idpac)
    {             	
		if ( null === $idpac ) {
    		return redirect('Pacientes');
    	}    
        
        $idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");
        
        $pacientes = pacientes::find($idpac);
      
        $pacientes->delete();

        $pacdir = "/pacdir/$idpac";

        Storage::deleteDirectory($pacdir);

        $request->session()->flash('sucmess', 'Hecho!!!');
        
        return redirect('Pacientes');
    }

    public function dircrea($idpac)
    {               
        $pacdir = "/pacdir/$idpac";

        if ( ! Storage::exists($pacdir) ) { 
            Storage::makeDirectory($pacdir,0770,true);
        }

        $odogdir = $pacdir.'/.odogdir';

        if ( ! Storage::exists($odogdir) ) { 
            Storage::makeDirectory($odogdir,0770,true);
        }

        $odogram = "/$pacdir/.odogdir/odogram.jpg";
        $img = '/public/assets/img/odogram.jpg';
          
        if ( ! Storage::exists($odogram) ) { 
            Storage::copy($img,$odogram);
        }

        $fotoper = "/$pacdir/.fotoper.jpg";
        $foto = '/public/assets/img/fotoper.jpg';
          
        if ( ! Storage::exists($fotoper) ) { 
            Storage::copy($foto,$fotoper);
        }          

        $thumbdir = $pacdir.'/.thumbdir';

        if ( ! Storage::exists($thumbdir) ) { 
            Storage::makeDirectory($thumbdir,0770,true);
        }
    }    
}