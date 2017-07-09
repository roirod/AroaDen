<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Pacientes;
use App\Models\Ficha;
use Carbon\Carbon;
use Storage;
use Html;
use Image;
use Validator;
use Lang;
use Illuminate\Http\Request;
use App\Interfaces\BaseInterface;

class PacientesController extends BaseController implements BaseInterface
{
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Pacientes';
        $this->views_folder = 'pac';

        $fields = [
            'surname' => true,
            'name' => true,
            'address' => true,
            'city' => true,
            'birth' => true,
            'dni' => true,
            'sex' => true,            
            'tel1' => true,
            'tel2' => true,
            'tel3' => true,
            'notes' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }

    public function index(Request $request)
    {  	
    	$numpag = 100;

    	$main_loop = DB::table('pacientes')
                        ->select('idpac', 'surname', 'name', 'dni', 'tel1', 'city')
                        ->whereNull('deleted_at')
                        ->orderBy('surname', 'ASC')
                        ->orderBy('name', 'ASC')
                        ->paginate($numpag);

        $count = $this->getPatientsCount();

    	return view($this->views_folder.'.index', [
    		'main_loop' => $main_loop,
         	'request' => $request,
            'count' => $count
        ]);   
    }
  
    public function list(Request $request)
    {   
        $data = [];

        $count = $this->getPatientsCount();

        if ($count == 0) {

            $data['main_loop'] = false;
            $data['count'] = false;       
            $data['msg'] = Lang::get('aroaden.no_patients_on_db');

        } else {

            $busca = $this->sanitizeData($request->input('busca'));
            $busen = $this->sanitizeData($request->input('busen'));

            $data = $this->getItems($busen, $busca);

        }

        header('Content-type: application/json; charset=utf-8');

        echo json_encode($data);

        exit();
    } 

    public function show(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);

        $this->createDir($id);

        $profile_photo = url("/app/pacdir/$id/.profile_photo.jpg");

	    $paciente = DB::table('pacientes')
                        ->where('idpac', $id)
                        ->whereNull('deleted_at')
                        ->first();

        if (is_null($paciente)) {
            $request->session()->flash($this->error_message_name, 'Has borrado a este paciente.');    
            return redirect($this->main_route);
        }

        $citas = Pacientes::find($id)
                    ->citas()
                    ->orderBy('day', 'DESC')
                    ->orderBy('hour', 'DESC')
                    ->get();

		$tratampacien = DB::table('tratampacien')
		            ->join('servicios','tratampacien.idser','=','servicios.idser')
		            ->select('tratampacien.*','servicios.name as servicios_name')
		            ->where('idpac', $id)
		            ->orderBy('date','DESC')
		            ->get(); 
	    
	    $suma = DB::table('tratampacien')
				    ->selectRaw('SUM(units*price) AS total_sum, SUM(paid) AS total_paid, SUM(units*price)-SUM(paid) AS rest')
				    ->where('idpac', $id)
				    ->get();

	  	$birth = trim($paciente->birth);

	  	if (isset($birth)) {
            $date = explode("-",$birth,3);

            $date0 = $date[0];
            $date1 = $date[1];
            $date2 = $date[2];
		  	  
            $edad = Carbon::createFromDate($date0,$date1,$date2)->age;
	  	} else {
            $edad = 0;
	  	}

        return response()->view($this->views_folder.'.show',[
            'request' => $request,
            'paciente' => $paciente,
            'citas' => $citas,
            'tratampacien' => $tratampacien,
            'suma' => $suma,
            'idpac' => $id,
            'profile_photo' => $profile_photo,
            'edad' => $edad
        ])
           ->header('Expires', 'Sun, 01 Jan 1966 00:00:00 GMT')
           ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
           ->header('Cache-Control', ' post-check=0, pre-check=0', FALSE)
           ->header('Pragma', 'no-cache');     
    }

    public function create(Request $request, $id = false)
    {
        $this->autofocus = 'surname';

        $this->view_data = [
            'request' => $request,
            'main_route' => $this->main_route,
            'autofocus' => $this->autofocus,
            'form_fields' => $this->form_fields
        ];

        return parent::create($request, $id);  
    }

    public function store(Request $request)
    {        
        $dni = $this->sanitizeData($request->input('dni'));
    
        $patient = DB::table('pacientes')
                        ->where('dni', $dni)
                        ->first();

        if ($patient != null) {
            $messa = 'Repetido. El dni: '.$dni.', pertenece a: '.$patient->surname.', '.$patient->name.'.';
            $request->session()->flash($this->error_message_name, $messa);
            return redirect($this->main_route.'/create')->withInput();
        }

        $validator = Validator::make($request->all(),[
    		'name' => 'required|max:111',
            'surname' => 'required|max:111',
            'address' => 'max:111',
            'city' => 'max:111',
            'dni' => 'unique:pacientes|max:12',
            'tel1' => 'max:11',
            'tel2' => 'max:11',
            'tel3' => 'max:11',
            'sex' => 'max:12',
            'birth' => 'date',
            'notes' => ''
	    ]);
            
        if ($validator->fails()) {
	         return redirect($this->main_route.'/create')
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {

        	$name = ucfirst(strtolower( $request->input('name') ) );
        	$surname = ucwords(strtolower( $request->input('surname') ) );
        	$address = ucfirst(strtolower( $request->input('address') ) );
        	$city = ucfirst(strtolower( $request->input('city') ) );
            $notes = ucfirst(strtolower( $request->input('notes') ) );

			$name = $this->sanitizeData($name);
            $surname = $this->sanitizeData($surname);
			$dni = $this->sanitizeData($request->input('dni'));
            $tel1 = $this->sanitizeData($request->input('tel1'));
            $tel2 = $this->sanitizeData($request->input('tel2'));
            $tel3 = $this->sanitizeData($request->input('tel3'));
            $sex = $this->sanitizeData($request->input('sex'));
            $address = $this->sanitizeData($address);
            $city = $this->sanitizeData($city);
            $birth = $this->sanitizeData($request->input('birth'));
            $notes = $this->sanitizeData($notes);
            $created_at = date('Y-m-d H:i:s');
               
            $insertGetId = Pacientes::insertGetId([
	          'name' => $name,
	          'surname' => $surname,
	          'dni' => $dni,
	          'tel1' => $tel1,
	          'tel2' => $tel2,
	          'tel3' => $tel3,
	          'sex' => $sex,
	          'notes' => $notes,
	          'address' => $address,
	          'city' => $city,
	          'birth' => $birth,
              'created_at' => $created_at,
		    ]);

            ficha::create([
              'idpac' => $insertGetId
            ]);
	      
	        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );	
        	        	
        	return redirect($this->main_route.'/create');
        }      
    }

    public function edit(Request $request, $id, $idcit = false)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $object = Pacientes::find($id);

        $this->view_data = [
            'request' => $request,
            'id' => $id,
            'object' => $object,
            'main_route' => $this->main_route,
            'autofocus' => $this->autofocus,
            'form_fields' => $this->form_fields
        ];

        return parent::edit($request, $id);
    }

    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);   
        $dni = $this->sanitizeData($dni);   

        $patient = Pacientes::find($id);

        $patient_exits = DB::table('pacientes')
                        ->where('dni', $patient->dni)
                        ->where('idpac', '!=', $id)
                        ->first();
        
        if ($dni != null) {
            $messa = 'Repetido. El dni: '.$dni.', pertenece a: '.$patient_exits->surname.', '.$patient_exits->name;
            $request->session()->flash($this->error_message_name, $messa);
            return redirect("$this->main_route/$id/edit")->withInput();
        }

        $validator = Validator::make($request->all(),[
    		'name' => 'required|max:111',
            'surname' => 'required|max:111',
            'address' => 'max:111',
            'city' => 'max:111',
            'dni' => 'max:12',
            'tel1' => 'max:11',
            'tel2' => 'max:11',
            'tel3' => 'max:11',
            'sex' => 'max:12',
            'birth' => 'date',
            'notes' => ''
	    ]);
            
        if ($validator->fails()) {
	        return redirect("$this->main_route/$id/edit")
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {		
					  		
            $name = ucfirst(strtolower( $request->input('name') ) );
            $surname = ucwords(strtolower( $request->input('surname') ) );
            $address = ucfirst(strtolower( $request->input('address') ) );
            $city = ucfirst(strtolower( $request->input('city') ) );
            $notes = ucfirst(strtolower( $request->input('notes') ) );

            $patient->name = $this->sanitizeData($name);
            $patient->surname = $this->sanitizeData($surname);
            $patient->dni = $this->sanitizeData($request->input('dni'));
            $patient->tel1 = $this->sanitizeData($request->input('tel1'));
            $patient->tel2 = $this->sanitizeData($request->input('tel2'));
            $patient->tel3 = $this->sanitizeData($request->input('tel3'));
            $patient->sex = $this->sanitizeData($request->input('sex'));
            $patient->address = $this->sanitizeData($address);
            $patient->city = $this->sanitizeData($city);
            $patient->birth = $this->sanitizeData($request->input('birth'));
            $patient->notes = $this->sanitizeData($notes);
            $patient->updated_at = date('Y-m-d H:i:s');
			$patient->save();

			$request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

			return redirect("$this->main_route/$id");
		}    
    }

    public function ficha(Request $request, $id)
    {  
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $ficha = ficha::find($id);

        if (is_null($ficha)) {
            ficha::create([
              'idpac' => $id
            ]);

            return redirect("$this->main_route/$id/ficha");
        }

        return view($this->views_folder.'.ficha', [
            'request' => $request,
            'idpac' => $id,
            'ficha' => $ficha
        ]);
    } 

    public function fiedit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $ficha = ficha::find($id);

        return view($this->views_folder.'.fiedit', [
            'request' => $request,
            'idpac' => $id,
            'ficha' => $ficha
        ]);
    }

    public function fisave(Request $request, $id)
    {   
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);     
       
        $ficha = ficha::find($id);
                
        $histo = ucfirst(strtolower( $request->input('histo') ) );
        $enfer = ucfirst(strtolower( $request->input('enfer') ) );
        $medic = ucfirst(strtolower( $request->input('medic') ) );
        $aler = ucfirst(strtolower( $request->input('aler') ) );
        $notes = ucfirst(strtolower( $request->input('notes') ) );
        
        $ficha->histo = $this->sanitizeData($histo);   
        $ficha->enfer = $this->sanitizeData($enfer);   
        $ficha->medic = $this->sanitizeData($medic);   
        $ficha->aler = $this->sanitizeData($aler);   
        $ficha->notes = $this->sanitizeData($notes);   
        
        $ficha->save();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

        return redirect("$this->main_route/$id/ficha"); 
    }  

    public function file(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);

        $this->createDir($id);

        $pacdir = "/pacdir/$id";

        $files = Storage::files($pacdir);

        $url = url("$this->main_route/$id");

		return view($this->views_folder.'.file', [
            'request' => $request,
    	  	'idpac' => $id,
    	  	'files' => $files,
            'url' => $url
        ]);
    }

    public function upload(Request $request)
    {	
		$id = $request->input('idpac');
        $profile_photo = $request->input('profile_photo');

        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);

        $pacdir = storage_path("app/pacdir/$id");

		$files = $request->file('files');

        if ($profile_photo == 1) {
            $extension = $files->getClientOriginalExtension();

            if ($extension == 'jpg' || $extension == 'png') {

                Image::make($files)->encode('jpg', 60)
                    ->resize(150, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save("$pacdir/.profile_photo.jpg");

                return redirect("$this->main_route/$id");

            } else { 
                $request->session()->flash($this->error_message_name, 'Formato no soportado, suba una imagen jpg o png.');
                return redirect("$this->main_route/$id/file");
            }

        } else {

            $ficount = count($files);
            $upcount = 0;

            foreach ($files as $file) {                       
                $filename = $file->getClientOriginalName();
                $size = $file->getClientSize();

                $max_size = 2;
                $max = 1024 * 1024 * $max_size;
 
                $filedisk = storage_path("app/pacdir/$id/$filename");

                if ( $size > $max ) {
                    $mess = "El archivo: - $filename - es superior a $max_size MB";
                    $request->session()->flash($this->error_message_name, $mess);
                    return redirect("$this->main_route/$id/file");
                }                

                if ( file_exists($filedisk) ) {
                    $mess = "El archivo: $filename -- existe ya en su carpeta";
                    $request->session()->flash($this->error_message_name, $mess);
                    return redirect("$this->main_route/$id/file");

                } else {
                    $file->move($pacdir, $filename);
                    $upcount ++;
                }
            }            
    	     
    	    if($upcount == $ficount){
    	      return redirect("$this->main_route/$id/file");
    	    } else {
    	      $request->session()->flash($this->error_message_name, 'error!!!');
    	      return redirect("$this->main_route/$id/file");
    	    }
        }
    }

    public function download(Request $request, $id, $file)
    {   
        $id = $this->sanitizeData($id);

        $this->redirectIfIdIsNull($id, $this->main_route);
        $this->redirectIfIdIsNull($file, $this->main_route);
        
        $pacdir = storage_path("app/pacdir/$id");

        $filedown = $pacdir.'/'.$file;

        return response()->download($filedown);
    } 

    public function filerem(Request $request)
    {  	  
    	$id = $request->input('idpac');
    	$filerem = $request->input('filerem');

        $this->redirectIfIdIsNull($id, $this->main_route);
        $this->redirectIfIdIsNull($filerem, $this->main_route);

    	$id = $this->sanitizeData($id);

        $pacdir = storage_path("app/pacdir/$id");

        $file = $request->input('filerem');

        $filerem = $pacdir.'/'.$file;
          
        unlink($filerem);    
    	  
    	return redirect("$this->main_route/$id/file");
    }  
   
    public function odogram(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);

        $pacdir = "/app/pacdir/$id";

        $odogram = $pacdir."/.odogdir/odogram.jpg";

        return response()->view($this->views_folder.'.odogram',[
            'request' => $request,
            'idpac' => $id,
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

            $id = $request->input('idpac');

            $upodog = $request->file('upodog');

            $this->redirectIfIdIsNull($id, $this->main_route);

            $extension = $request->file('upodog')->getClientOriginalExtension();

            if ( $extension != 'jpg' ) {
                $request->session()->flash($this->error_message_name, 'No es una imagen jpg');
                return redirect("$this->main_route/$id/odogram");
            } 

            $id = $this->sanitizeData($id);

            $pacdir = storage_path("app/pacdir/$id");

            $odogram = $pacdir."/.odogdir/";

            $upodog->move($odogram,'odogram.jpg');

            return redirect("$this->main_route/$id/odogram");

        } else {
            return redirect("$this->main_route/$id/odogram");
        }    
    }   

    public function downodog(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $pacdir = storage_path("app/pacdir/$id");

        $odogram = $pacdir."/.odogdir/odogram.jpg";

        return response()->download($odogram);
    }    
    
    public function resodog(Request $request)
    {  
    	$id = $request->input('idpac');

        $this->redirectIfIdIsNull($id, $this->main_route); 	

    	$id = $this->sanitizeData($id);
 
		$resodog = $request->input('resodog');
		 
		if ( $resodog == 1 ) {
            $pacdir = "/pacdir/$id";

            $odogram = "/$pacdir/.odogdir/odogram.jpg";
            $img = '/img/odogram.jpg'; 

            Storage::delete($odogram);           

	    	Storage::copy($img, $odogram);
	    	  
	    	return redirect("/Pacientes/$id/odogram");

		} else {
		 	$request->session()->flash($this->error_message_name, 'Error');
		 	return redirect("/Pacientes/$id/odogram");
		}	
    }

    public function presup(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $presup = DB::table('presup')
                    ->join('servicios','presup.idser','=','servicios.idser')
                    ->select('presup.*','servicios.name')
                    ->where('idpac', $id)
                    ->orderBy('cod','ASC')
                    ->get(); 
          
        return view($this->views_folder.'.presup', [
            'request' => $request,
            'idpac' => $id,
            'presup' => $presup
        ]);
    }    

    public function del(Request $request, $id)
    {    	  
        $this->redirectIfIdIsNull($id, $this->main_route);
        
    	$id = $this->sanitizeData($id);

        $paciente = Pacientes::find($id);

    	return view($this->views_folder.'.del', [
            'request' => $request,
            'paciente' => $paciente,
    		'idpac' => $id
        ]);
    }
 
    public function destroy(Request $request, $id)
    {             	
        $this->redirectIfIdIsNull($id, $this->main_route);
        
        $id = $this->sanitizeData($id);
        
        Pacientes::destroy($id);
      
        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

    public function createDir($id)
    {               
        $pacdir = "/pacdir/$id";

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

        $profile_photo = "/$pacdir/.profile_photo.jpg";
        $foto = '/public/assets/img/profile_photo.jpg';
          
        if ( ! Storage::exists($profile_photo) ) { 
            Storage::copy($foto,$profile_photo);
        }          

        $thumbdir = $pacdir.'/.thumbdir';

        if ( ! Storage::exists($thumbdir) ) { 
            Storage::makeDirectory($thumbdir,0770,true);
        }
    }

    public function getItems($busen, $busca)
    {
        $data = [];

        $main_loop = DB::table('pacientes')
                        ->select('idpac', 'surname', 'name', 'dni', 'tel1', 'city')
                        ->whereNull('deleted_at')
                        ->where($busen,'LIKE','%'.$busca.'%')
                        ->orderBy('surname','ASC')
                        ->orderBy('name','ASC')
                        ->get();

        $count = DB::table('pacientes')
                    ->whereNull('deleted_at')
                    ->where($busen,'LIKE','%'.$busca.'%')
                    ->count();

        if ($count == 0) {

            $data['main_loop'] = false;
            $data['count'] = false;       
            $data['msg'] = ' No hay resultados para la búsqueda. ';

        } else {

            $data['main_loop'] = $main_loop;
            $data['count'] = $count;        
            $data['msg'] = false;

        }

        return $data;
    }

    public function getPatientsCount()
    {
        $patients_count = DB::table('pacientes')
                            ->whereNull('deleted_at')
                            ->count();

        return $patients_count;
    }

}