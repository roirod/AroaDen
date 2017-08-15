<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Pacientes;
use App\Models\Ficha;
use App\Models\Presup;
use App\Models\Tratampacien;

use Carbon\Carbon;
use Storage;
use Html;
use Image;
use Validator;
use Lang;
use Exception;

use Illuminate\Http\Request;
use App\Interfaces\BaseInterface;

class PacientesController extends BaseController implements BaseInterface
{
    /**
     * @var string $img_folder  img_folder
     */
    private $img_folder = '/public/assets/img';

    /**
     * @var string $odog_dir  odog_dir
     */
    private $odog_dir = '.odogdir';

    /**
     * @var string $odogram  odogram
     */
    private $odogram = 'odogram.jpg';

    /**
     * @var string $odogram  odogram
     */
    private $own_dir = 'pacdir';

    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Pacientes';
        $this->form_route = 'list';        
        $this->views_folder = 'pac';
        $this->files_dir = "app/".$this->own_dir;      

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
        $main_loop = Pacientes::AllOrderBySurname($this->num_paginate);
        $count = Pacientes::CountAll();

        $this->page_title = Lang::get('aroaden.patients').' - '.$this->page_title;

        $this->passVarsToViews();

        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['count'] = $count;
        $this->view_data['form_route'] = $this->form_route;

        return parent::index($request);    
    }
  
    public function list(Request $request)
    {   
        $data = [];

        $count = Pacientes::CountAll();

        $data['main_loop'] = false;
        $data['count'] = false;    
        $data['msg'] = false; 

        if ($count == 0) {
   
            $data['msg'] = Lang::get('aroaden.no_patients_on_db');

        } else {

            $busca = $this->sanitizeData($request->input('busca'));
            $busen = $this->sanitizeData($request->input('busen'));

            try {

                $data = $this->getItems($busen, $busca);

            } catch (Exception $e) {
    
                $data['msg'] = $e->getMessage();

            }
        }

        $this->echoJsonOuptut($data);
    } 

    public function show(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);

        $this->createDir($id);

        $profile_photo = url("/$this->files_dir/$id/$this->profile_photo_name");

        $paciente = Pacientes::FirstById($id);

        if ( !isset($paciente->idpac) ) {
            $request->session()->flash($this->error_message_name, Lang::get('aroaden.no_patient_or_deleted'));    
            return redirect($this->main_route);
        }

        $citas = Pacientes::AllCitasById($id);
        $tratampacien = Tratampacien::ServicesById($id);
        $suma = Pacientes::ServicesSumById($id);

	  	$birth = trim($paciente->birth);

	  	if (isset($birth)) {
            $date = explode("-", $birth, 3);	  	  
            $edad = Carbon::createFromDate($date[0], $date[1], $date[2])->age;
	  	} else {
            $edad = 0;
	  	}

        $this->page_title = $paciente->surname.', '.$paciente->name.' - '.$this->page_title;

        $this->passVarsToViews();

        $this->view_data['request'] = $request;
        $this->view_data['paciente'] = $paciente;
        $this->view_data['citas'] = $citas;
        $this->view_data['tratampacien'] = $tratampacien;
        $this->view_data['suma'] = $suma;
        $this->view_data['id'] = $id;
        $this->view_data['idpac'] = $id;        
        $this->view_data['edad'] = $edad;
        $this->view_data['profile_photo'] = $profile_photo;
        $this->view_data['profile_photo_name'] = $this->profile_photo_name;

        return response()->view($this->views_folder.'.show', $this->view_data)
           ->header('Expires', 'Sun, 01 Jan 1966 00:00:00 GMT')
           ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
           ->header('Cache-Control', ' post-check=0, pre-check=0', FALSE)
           ->header('Pragma', 'no-cache');     
    }

    public function create(Request $request, $id = false)
    {
        $this->page_title = Lang::get('aroaden.create_patient').' - '.$this->page_title;

        $this->view_data['request'] = $request;
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::create($request, $id);  
    }

    public function store(Request $request)
    {        
        $dni = $this->sanitizeData($request->input('dni'));
    
        $exists = Pacientes::FirstByDniDeleted($dni);

        if ( isset($exists->dni) ) {
            $msg = Lang::get('aroaden.dni_in_use', ['dni' => $dni, 'surname' => $exists->surname, 'name' => $exists->name]);
            $request->session()->flash($this->error_message_name, $msg);
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

    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $object = Pacientes::find($id);

        $this->page_title = $object->surname.', '.$object->name.' - '.$this->page_title;

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idpac'] = $id;        
        $this->view_data['object'] = $object;
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::edit($request, $id);
    }

    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);   
        $dni = $this->sanitizeData($dni);   

        $patient = Pacientes::find($id);
    
        $exists = Pacientes::CheckIfExistsOnUpdate($id, $patient->dni);

        if ( isset($exists->dni) ) {
            $msg = Lang::get('aroaden.dni_in_use', ['dni' => $dni, 'surname' => $exists->surname, 'name' => $exists->name]);
            $request->session()->flash($this->error_message_name, $msg);
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

        $this->view_data['request'] = $request;
        $this->view_data['idpac'] = $id;
        $this->view_data['ficha'] = $ficha;

        return view($this->views_folder.'.ficha', $this->view_data);
    } 

    public function fiedit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $ficha = ficha::find($id);

        $this->view_data['request'] = $request;
        $this->view_data['idpac'] = $id;
        $this->view_data['ficha'] = $ficha;

        return view($this->views_folder.'.fiedit', $this->view_data);
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

        $pacdir = "/$this->own_dir/$id";

        $files = Storage::files($pacdir);

        $url = url("$this->main_route/$id");

        $this->view_data['request'] = $request;
        $this->view_data['idpac'] = $id;
        $this->view_data['files'] = $files;
        $this->view_data['url'] = $url;

        return view($this->views_folder.'.file', $this->view_data);
    }

    public function upload(Request $request)
    {	
		$id = $request->input('idpac');
        $profile_photo = $request->input('profile_photo');

        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);

        $pacdir = storage_path("$this->files_dir/$id");

		$files = $request->file('files');

        if ($profile_photo == 1) {
            $extension = $files->getClientOriginalExtension();

            if ($extension == 'jpg' || $extension == 'png') {
                Image::make($files)->encode('jpg', 60)
                    ->resize(150, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save("$pacdir/$this->profile_photo_name");

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
 
                $filedisk = storage_path("$this->files_dir/$id/$filename");

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
        
        $pacdir = storage_path("$this->files_dir/$id");

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

        $pacdir = storage_path("$this->files_dir/$id");

        $file = $request->input('filerem');

        $filerem = $pacdir.'/'.$file;
          
        unlink($filerem);    
    	  
    	return redirect("$this->main_route/$id/file");
    }  
   
    public function odogram(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

    	$id = $this->sanitizeData($id);

        $pacdir = "/$this->files_dir/$id";
        $odogram = "$pacdir/$this->odog_dir/$this->odogram";

        $this->view_data['request'] = $request;
        $this->view_data['idpac'] = $id;
        $this->view_data['odogram'] = $odogram;

        return response()->view($this->views_folder.'.odogram', $this->view_data)
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

            $pacdir = storage_path("$this->files_dir/$id");

            $odogram = "$pacdir/$this->odog_dir/";

            $upodog->move($odogram, $this->odogram);

            return redirect("$this->main_route/$id/odogram");

        } else {

            $request->session()->flash($this->error_message_name, 'Error');
            return redirect("$this->main_route/$id/odogram");
        }    
    }   

    public function downodog(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $pacdir = storage_path("$this->files_dir/$id");

        $odogram = "$pacdir/$this->odog_dir/$this->odogram";

        return response()->download($odogram);
    }    
    
    public function resodog(Request $request)
    {  
    	$id = $request->input('idpac');

        $this->redirectIfIdIsNull($id, $this->main_route); 	

    	$id = $this->sanitizeData($id);
 
		$resodog = $request->input('resodog');
		 
		if ( $resodog == 1 ) {
            $pacdir = "/$this->own_dir/$id";

            $odogram = "/$pacdir/$this->odog_dir/$this->odogram";
            $img = "/img/$this->odogram"; 

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

        $presup = Presup::AllById($query, $id);

        $this->view_data['request'] = $request;
        $this->view_data['idpac'] = $id;
        $this->view_data['presup'] = $presup;

        return view($this->views_folder.'.presup', $this->view_data);
    }    

    public function del(Request $request, $id)
    {    	  
        $this->redirectIfIdIsNull($id, $this->main_route);
        
    	$id = $this->sanitizeData($id);

        $paciente = Pacientes::find($id);

        $this->view_data['request'] = $request;
        $this->view_data['idpac'] = $id;
        $this->view_data['paciente'] = $paciente;

        return view($this->views_folder.'.del', $this->view_data);
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
        $pacdir = "/$this->own_dir/$id";

        if ( ! Storage::exists($pacdir) ) { 
            Storage::makeDirectory($pacdir,0770,true);
        }

        $odogdir = "$pacdir/$this->odog_dir";

        if ( ! Storage::exists($odogdir) ) { 
            Storage::makeDirectory($odogdir,0770,true);
        }

        $odogram = "/$pacdir/$this->odog_dir/$this->odogram";
        $img = "$this->img_folder/$this->odogram";
          
        if ( ! Storage::exists($odogram) ) { 
            Storage::copy($img,$odogram);
        }

        $profile_photo = "/$pacdir/$this->profile_photo_name";
        $foto = "$this->img_folder/profile_photo.jpg";
          
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

        $main_loop = Pacientes::FindStringOnField($busen, $busca);
        $count = Pacientes::CountFindStringOnField($busen, $busca);

        if ($count == 0) {

            throw new Exception( Lang::get('aroaden.no_query_results') );

        } else {

            $data['main_loop'] = $main_loop;
            $data['count'] = $count;        
            $data['msg'] = false;
            return $data;
        }

        throw new Exception( Lang::get('aroaden.db_query_error') );
    }

}