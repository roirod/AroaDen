<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Interfaces\BaseInterface;
use App\Http\Controllers\Traits\DirFilesTrait;
use Illuminate\Http\Request;
use App\Models\Treatments;
use App\Models\Patients;
use App\Models\Budgets;
use App\Models\Record;
use Carbon\Carbon;
use Validator;
use Exception;
use Storage;
use Image;
use Lang;
use Html;
use DB;

class PatientsController extends BaseController implements BaseInterface
{
    use DirFilesTrait;

    /**
     * @var string $odog_dir  odog_dir
     */
    private $odog_dir = '.odogdir';

    /**
     * @var string $odogram  odogram
     */
    private $odogram = 'odogram.jpg';

    /**
     * 
     */
    public function __construct(Patients $patients)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['patients'];    
        $this->views_folder = $this->config['routes']['patients'];        
        $this->model = $patients;
        $this->form_route = 'list';
        $this->own_dir = 'patients_dir';
        $this->files_dir = "app/".$this->own_dir;
        $this->has_odogram = true;

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
        $main_loop = $this->model::AllOrderBySurname($this->num_paginate);
        $count = $this->model::CountAll();

        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['count'] = $count;

        $this->setPageTitle(Lang::get('aroaden.patients'));

        return parent::index($request);    
    }
  
    public function list(Request $request)
    {   
        $data = [];

        $count = $this->model::CountAll();

        $data['main_loop'] = false;
        $data['count'] = false;    
        $data['msg'] = false; 

        if ($count == 0) {
   
            $data['msg'] = Lang::get('aroaden.no_patients_on_db');

        } else {

            try {

                $busca = $this->sanitizeData($request->input('busca'));
                $busen = $this->sanitizeData($request->input('busen'));                

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

        $this->createDir($id, true);

        $profile_photo = url("/$this->files_dir/$id/$this->profile_photo_name");

        $paciente = $this->model::FirstById($id);

        if ( !isset($paciente->idpac) ) {
            $request->session()->flash($this->error_message_name, Lang::get('aroaden.no_patient_or_deleted'));    
            return redirect($this->main_route);
        }

        $citas = $this->model::AllCitasById($id);
        $tratampacien = Treatments::ServicesById($id);
        $suma = $this->model::ServicesSumById($id);

	  	$birth = trim($paciente->birth);

	  	if (isset($birth)) {
            $date = explode("-", $birth, 3);	  	  
            $age = Carbon::createFromDate($date[0], $date[1], $date[2])->age;
	  	} else {
            $age = 0;
	  	}

        $this->setPageTitle($paciente->surname.', '.$paciente->name);

        $this->view_data['request'] = $request;
        $this->view_data['object'] = $paciente;
        $this->view_data['citas'] = $citas;
        $this->view_data['tratampacien'] = $tratampacien;
        $this->view_data['suma'] = $suma;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;        
        $this->view_data['age'] = $age;
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
        $this->setPageTitle(Lang::get('aroaden.create_patient'));

        $this->view_data['request'] = $request;
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::create($request, $id);  
    }

    public function store(Request $request)
    {        
        $dni = $this->sanitizeData($request->input('dni'));
    
        $exists = $this->model::FirstByDniDeleted($dni);

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
              
            $insertGetId = $this->model::insertGetId([
	          'name' => $this->sanitizeData($name),
	          'surname' => $this->sanitizeData($surname),
	          'dni' => $this->sanitizeData($request->input('dni')),
              'tel1' => $this->sanitizeData($request->input('tel1')),
              'tel2' => $this->sanitizeData($request->input('tel2')),
              'tel3' => $this->sanitizeData($request->input('tel3')),
              'sex' => $this->sanitizeData($request->input('sex')),
              'address' => $this->sanitizeData($address),
              'city' => $this->sanitizeData($city),
              'birth' => $this->sanitizeData($request->input('birth')),
              'notes' => $this->sanitizeData($notes),
              'created_at' => date('Y-m-d H:i:s'),
		    ]);

            Record::create([
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

        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name);

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;        
        $this->view_data['object'] = $object;
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::edit($request, $id);
    }

    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $input_dni = $this->sanitizeData($request->input('dni'));

        $patient = $this->model::FirstById($id);
        $exists = $this->model::CheckIfExistsOnUpdate($id, $input_dni);

        if ( isset($exists->dni) ) {
            $msg = Lang::get('aroaden.dni_in_use', ['dni' => $exists->dni, 'surname' => $exists->surname, 'name' => $exists->name]);
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

        $ficha = Record::find($id);

        if (is_null($ficha)) {
            Record::create([
              'idpac' => $id
            ]);

            return redirect("/$this->main_route/$id/ficha");
        }

        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name);

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
        $this->view_data['ficha'] = $ficha;

        return view($this->views_folder.'.ficha', $this->view_data);
    } 

    public function fiedit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $ficha = Record::find($id);
        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name);

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
        $this->view_data['ficha'] = $ficha;

        return view($this->views_folder.'.fiedit', $this->view_data);
    }

    public function fisave(Request $request, $id)
    {   
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);     
       
        $ficha = Record::find($id);
                
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
        return $this->loadFileView($request, $id);
    }
   
    public function odogram(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
    	$id = $this->sanitizeData($id);

        $odogram = "/$this->files_dir/$id/$this->odog_dir/$this->odogram";

        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name); 

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
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
            $id = $request->input('id');
            $upodog = $request->file('upodog');

            $this->redirectIfIdIsNull($id, $this->main_route);
            $id = $this->sanitizeData($id);

            $extension = $request->file('upodog')->getClientOriginalExtension();

            if ( $extension != 'jpg' ) {
                $request->session()->flash($this->error_message_name, 'No es una imagen jpg');
                return redirect("$this->main_route/$id/odogram");
            } 

            $odogram = storage_path("$this->files_dir/$id")."/$this->odog_dir/";

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

        $odogram = storage_path("$this->files_dir/$id")."/$this->odog_dir/$this->odogram";

        return response()->download($odogram);
    }    
    
    public function resodog(Request $request)
    {  
    	$id = $request->input('id');
        $resodog = $request->input('resodog');

        $this->redirectIfIdIsNull($id, $this->main_route); 	
    	$id = $this->sanitizeData($id);
 		 
		if ( $resodog == 1 ) {

            $odogram = "/$this->own_dir/$id/$this->odog_dir/$this->odogram";
            $img = "/img/$this->odogram"; 

            Storage::delete($odogram);           
	    	Storage::copy($img, $odogram);
	    	  
	    	return redirect("/$this->main_route/$id/odogram");

		} else {

		 	$request->session()->flash($this->error_message_name, 'Error');
		 	return redirect("/$this->main_route/$id/odogram");
		}	
    }

    public function presup(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $presup = Budgets::AllById($query, $id);

        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name);

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
        $this->view_data['presup'] = $presup;

        return view($this->views_folder.'.presup', $this->view_data);
    }    

    public function destroy(Request $request, $id)
    {             	
        $this->redirectIfIdIsNull($id, $this->main_route); 
        $id = $this->sanitizeData($id);
        
        $this->model::destroy($id);
      
        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

    public function getItems($busen, $busca)
    {
        $data = [];

        $main_loop = $this->model::FindStringOnField($busen, $busca);
        $count = $this->model::CountFindStringOnField($busen, $busca);

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