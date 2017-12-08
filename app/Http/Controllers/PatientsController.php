<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exceptions\NoPatientsFoundException;
use App\Http\Controllers\Interfaces\BaseInterface;
use App\Http\Controllers\Traits\DirFilesTrait;
use Illuminate\Http\Request;
use App\Models\Appointments;
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
        $string = $this->sanitizeData($request->input('string'));
        $search_in = $this->sanitizeData($request->input('search_in')); 

        $data = [];
        $data['main_loop'] = false; 
        $data['error'] = false; 
        $data['msg'] = false;

        $count = $this->model::CountAll();

        if ((int)$count === 0) {

            $data['error'] = true;  
            $data['msg'] = Lang::get('aroaden.no_patients_on_db');
            $this->echoJsonOuptut($data);

        }

        try {               

            $data = $this->getQueryResult($search_in, $string);
            $this->echoJsonOuptut($data);

        } catch (NoPatientsFoundException $e) {

            $data['error'] = true;  
            $data['msg'] = $e->getMessage();
            $this->echoJsonOuptut($data);

        }
    } 

    public function show(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
    	$id = $this->sanitizeData($id);

        $this->view_name = 'show';

        $this->createDir($id, true);

        $patient = $this->model::FirstById($id);

        if ( !isset($patient->idpac) ) {
            $request->session()->flash($this->error_message_name, Lang::get('aroaden.no_patient_or_deleted'));    
            return redirect($this->main_route);
        }

        $appointments = Appointments::AllByPatientId($id);
        $treatments = Treatments::AllByPatientId($id);
        $treatments_sum = Treatments::SumByPatientId($id);
	  	$birth = trim($patient->birth);
        $profile_photo = url("/$this->files_dir/$id/$this->profile_photo_name");

	  	if (isset($birth)) {
            $date = explode("-", $birth, 3);	  	  
            $age = Carbon::createFromDate($date[0], $date[1], $date[2])->age;
	  	} else {
            $age = 0;
	  	}

        $this->setPageTitle($patient->surname.', '.$patient->name);

        $this->view_data['request'] = $request;
        $this->view_data['object'] = $patient;
        $this->view_data['appointments'] = $appointments;
        $this->view_data['treatments'] = $treatments;
        $this->view_data['treatments_sum'] = $treatments_sum;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;        
        $this->view_data['age'] = $age;
        $this->view_data['profile_photo'] = $profile_photo;
        $this->view_data['profile_photo_name'] = $this->profile_photo_name;

        return $this->loadView($this->views_folder.".$this->view_name", $this->view_data, true);
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

    public function record(Request $request, $id)
    {  
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $this->view_name = 'record';

        $record = Record::find($id);

        if (is_null($record)) {
            Record::create([
              'idpac' => $id
            ]);

            return redirect("/$this->main_route/$id/record");
        }

        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name);

        $this->form_route = 'recordEdit';

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
        $this->view_data['record'] = $record;

        return $this->loadView($this->views_folder.".$this->view_name", $this->view_data);
    } 

    public function recordEdit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $this->view_name = 'recordEdit';

        $record = Record::find($id);
        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name);

        $this->form_route = 'recordSave';

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
        $this->view_data['record'] = $record;

        return $this->loadView($this->views_folder.".$this->view_name", $this->view_data);
    }

    public function recordSave(Request $request, $id)
    {   
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);     
       
        $this->view_name = 'record';

        $record = Record::find($id);
                
        $histo = ucfirst(strtolower( $request->input('histo') ) );
        $enfer = ucfirst(strtolower( $request->input('enfer') ) );
        $medic = ucfirst(strtolower( $request->input('medic') ) );
        $aler = ucfirst(strtolower( $request->input('aler') ) );
        $notes = ucfirst(strtolower( $request->input('notes') ) );
        
        $record->histo = $this->sanitizeData($histo);   
        $record->enfer = $this->sanitizeData($enfer);   
        $record->medic = $this->sanitizeData($medic);   
        $record->aler = $this->sanitizeData($aler);   
        $record->notes = $this->sanitizeData($notes);   
        
        $record->save();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

        return redirect("$this->main_route/$id/$this->view_name");
    }  

    public function file(Request $request, $id)
    {
        return $this->loadFileView($request, $id);
    }
   
    public function odogram(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
    	$id = $this->sanitizeData($id);

        $this->view_name = 'odogram';

        $odogram = "/$this->files_dir/$id/$this->odog_dir/$this->odogram";

        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name); 

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
        $this->view_data['odogram'] = $odogram;

        return $this->loadView($this->views_folder.".$this->view_name", $this->view_data, true);
    }

    public function upodog(Request $request)
    {   
        if ($request->file('upodog')->isValid()) {
            $id = $request->input('id');
            $upodog = $request->file('upodog');

            $this->redirectIfIdIsNull($id, $this->main_route);
            $id = $this->sanitizeData($id);

            $this->view_name = 'odogram';

            $extension = $request->file('upodog')->getClientOriginalExtension();

            if ( $extension != 'jpg' ) {
                $request->session()->flash($this->error_message_name, Lang::get('aroaden.no_jpg_img'));
                return redirect("$this->main_route/$id/$this->view_name");
            } 

            $odogram = storage_path("$this->files_dir/$id")."/$this->odog_dir/";

            $upodog->move($odogram, $this->odogram);

            return redirect("$this->main_route/$id/$this->view_name");

        } else {

            $request->session()->flash($this->error_message_name, Lang::get('aroaden.error_message'));
            return redirect("$this->main_route/$id/$this->view_name");
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
 		 
        $this->view_name = 'odogram';

		if ( $resodog == 1 ) {

            $odogram = "/$this->own_dir/$id/$this->odog_dir/$this->odogram";
            $img = "/img/$this->odogram"; 

            Storage::delete($odogram);           
	    	Storage::copy($img, $odogram);
	    	  
	    	return redirect("/$this->main_route/$id/$this->view_name");

		} else {

		 	$request->session()->flash($this->error_message_name, Lang::get('aroaden.error_message'));
		 	return redirect("/$this->main_route/$id/$this->view_name");
		}	
    }

    public function budgets(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $this->view_name = 'budgets';

        $budgets = Budgets::AllById($query, $id);
        $object = $this->model::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name);

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $id;
        $this->view_data['budgets'] = $budgets;

        return $this->loadView($this->views_folder.".$this->view_name", $this->view_data);
    }    

    public function destroy(Request $request, $id)
    {             	
        $this->redirectIfIdIsNull($id, $this->main_route); 
        $id = $this->sanitizeData($id);
        
        $this->model::destroy($id);
      
        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

    public function getQueryResult($search_in, $string)
    {
        $main_loop = $this->model::FindStringOnField($search_in, $string);
        $count = $this->model::CountFindStringOnField($search_in, $string);

        if ((int)$count === 0)
            throw new NoPatientsFoundException(Lang::get('aroaden.no_query_results'));

        $data = [];
        $data['main_loop'] = $main_loop;      
        $data['msg'] = $count;
        return $data;
    }

}