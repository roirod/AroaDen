<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exceptions\NoQueryResultException;
use App\Http\Controllers\Interfaces\BaseInterface;
use App\Http\Controllers\Traits\DirFilesTrait;
use Illuminate\Http\Request;
use App\Models\Staff;
use Carbon\Carbon;
use Validator;
use Exception;
use Storage;
use Lang;
use DB;

class StaffController extends BaseController implements BaseInterface
{
    use DirFilesTrait;

    public function __construct(Staff $staff)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['staff'];
        $this->other_route = $this->config['routes']['patients'];        
        $this->views_folder = $this->config['routes']['staff'];
        $this->form_route = 'list';
        $this->own_dir = 'staff_dir';
        $this->files_dir = "app/".$this->own_dir;
        $this->model = $staff;

        $fields = [
            'surname' => true,
            'name' => true,
            'position' => true,
            'address' => true,
            'city' => true,
            'birth' => true,
            'dni' => true,
            'tel1' => true,
            'tel2' => true,
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

        $this->setPageTitle(Lang::get('aroaden.staff'));

        $this->setPageTitle(Lang::get('aroaden.staff'));

        return parent::index($request);
    }
  
    public function list(Request $request)
    {   
        return parent::list($request);        
    }

    public function show(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);
          
        $profile_photo = url("/$this->files_dir/$id/$this->profile_photo_name");

        $staff = $this->model::FirstById($id);

        if (is_null($staff)) {
            $request->session()->flash($this->error_message_name, 'Has borrado a este profesional.');    
            return redirect($this->main_route);
        }

        $this->createDir($id);

        $trabajos = $this->model::ServicesById($id);
            
        $this->view_data['request'] = $request;
        $this->view_data['object'] = $staff;
        $this->view_data['trabajos'] = $trabajos;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $staff->idper;
        $this->view_data['profile_photo'] = $profile_photo;
        $this->view_data['profile_photo_name'] = $this->profile_photo_name;

        $this->setPageTitle($staff->surname.', '.$staff->name);

        return parent::show($request, $id);
    }

    public function create(Request $request, $id = false)
    {     
        $this->view_data['request'] = $request;
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::create($request, $id);  
    }

    public function store(Request $request)
    {
        $dni = $this->sanitizeData($request->input('dni'));

        $exists = $this->model::FirstByDniDeleted($dni);
   
        if ( isset($exists->dni) ) {
            $messa = 'Repetido. El dni: '.$dni.', pertenece a: '.$exists->surname.', '.$exists->name;
            $request->session()->flash($this->error_message_name, $messa);
            return redirect("/$this->main_route/create")->withInput();
        }

        $validator = Validator::make($request->all(),[
           'name' => 'required|max:111',
           'surname' => 'required|max:111',
           'dni' => 'unique:staff|max:12',
           'tel1' => 'max:11',
           'tel2' => 'max:11',
           'position' => 'max:66',
           'address' => 'max:111',
           'city' => 'max:111',
           'birth' => 'date',
           'notes' => ''
        ]);
            
        if ($validator->fails()) {
             return redirect("/$this->main_route/create")
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
            $position = $this->sanitizeData($request->input('position'));
            $dni = $this->sanitizeData($request->input('dni'));
            $tel1 = $this->sanitizeData($request->input('tel1'));
            $tel2 = $this->sanitizeData($request->input('tel2'));
            $address = $this->sanitizeData($address);
            $city = $this->sanitizeData($city);
            $birth = $this->sanitizeData($request->input('birth'));
            $notes = $this->sanitizeData($notes);
                        
            $this->model::create([
              'name' => $name,
              'surname' => $surname,
              'dni' => $dni,
              'tel1' => $tel1,
              'tel2' => $tel2,
              'position' => $position,
              'address' => $address,
              'city' => $city,
              'birth' => $birth,
              'notes' => $notes
            ]);
          
            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );  
                        
            return redirect("/$this->main_route/create");
        }      
    }
  
    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);
         
        $object = $this->model::FirstById($id); 

        $this->view_data['request'] = $request;
        $this->view_data['object'] = $object;
        $this->view_data['form_fields'] = $this->form_fields;
        $this->view_data['idnav'] = $id;       
        $this->view_data['id'] = $id;

        $this->setPageTitle($object->surname.', '.$object->name);

        return parent::edit($request, $id);
    }

    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $input_dni = $this->sanitizeData($request->input('dni'));

        $person = $this->model::FirstById($id);
        $exists = $this->model::CheckIfExistsOnUpdate($id, $input_dni);

        if ( isset($exists->dni) ) {
            $msg = Lang::get('aroaden.dni_in_use', ['dni' => $exists->dni, 'surname' => $exists->surname, 'name' => $exists->name]);
            $request->session()->flash($this->error_message_name, $msg);
            return redirect("$this->main_route/$id/edit")->withInput();
        }
              
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:111',
            'surname' => 'required|max:111',
            'dni' => 'required|max:12',
            'tel1' => 'max:11',
            'tel2' => 'max:11',
            'position' => 'max:66',
            'notes' => '',
            'address' => 'max:111',
            'city' => 'max:111',
            'birth' => 'date'
        ]);
            
        if ($validator->fails()) {
             return redirect("/$this->main_route/$id/edit")
                         ->withErrors($validator)
                         ->withInput();
        } else {        

            $birth = $request->input('birth');
                      
            if ( $this->validateDate($birth) ) {
               $request->session()->flash($this->error_message_name, 'Fecha/s incorrecta');
               return redirect("/$this->main_route/$id/edit");
            }
          
            $id = $this->sanitizeData($id);
            
            $staff = $this->model::FirstById($id);
                    
            $name = ucfirst(strtolower( $request->input('name') ) );
            $surname = ucwords(strtolower( $request->input('surname') ) );
            $notes = ucfirst(strtolower( $request->input('notes') ) );
            $address = ucfirst(strtolower( $request->input('address') ) );
            $city = ucfirst(strtolower( $request->input('city') ) );
            
            $staff->name = $this->sanitizeData($name);
            $staff->surname = $this->sanitizeData($surname);
            $staff->dni = $this->sanitizeData($request->input('dni'));
            $staff->tel1 = $this->sanitizeData($request->input('tel1'));
            $staff->tel2 = $this->sanitizeData($request->input('tel2'));
            $staff->position = $this->sanitizeData($request->input('position'));
            $staff->notes = $this->sanitizeData($notes);
            $staff->address = $this->sanitizeData($address);
            $staff->city = $this->sanitizeData($city);
            $staff->birth = $this->sanitizeData($request->input('birth'));
            
            $staff->save();

            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

            return redirect("/$this->main_route/$id");
        }   
    }

    public function file(Request $request, $id)
    {
        return $this->loadFileView($request, $id);
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
    }

}
