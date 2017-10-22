<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Servicios;

use Auth;
use Validator;
use Lang;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Interfaces\BaseInterface;

class ServiciosController extends BaseController implements BaseInterface
{
    /**
     *  construct method
     */
    public function __construct(Servicios $servicios)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->tax_types = require(base_path().'/config/tax_types.php');        
        $this->main_route = 'Servicios';
        $this->form_route = 'list';
        $this->views_folder = 'serv';
        $this->model = $servicios;

        $fields = [
            'name' => true,
            'price' => true,
            'tax' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }

    /**
     *  get the index page
     *      
     *  @return view
     */
    public function index(Request $request)
    {
        $main_loop = $this->model::AllOrderByName($this->num_paginate);
        $count = $this->model::CountAll();       

        $this->page_title = Lang::get('aroaden.sevices').' - '.$this->page_title;

        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['request'] = $request;
        $this->view_data['count'] = $count;
        $this->view_data['form_fields'] = $this->form_fields;
        $this->view_data['form_route'] = $this->form_route;

        return parent::index($request);        
    }

    /**
     *  get query result, using ajax
     * 
     *  @param Request $request request object.
     * 
     *  @return string JSON
     */
    public function list(Request $request)
    {   
        $data = [];

        $count = $this->model::CountAll();

        $data['main_loop'] = false;
        $data['count'] = false;    
        $data['msg'] = false; 

        if ($count == 0) {
   
            $data['msg'] = Lang::get('aroaden.no_services_on_db');

        } else {

            try {

                $busca = $this->sanitizeData($request->input('busca'));          

                $data = $this->getItems($busca);

            } catch (Exception $e) {
    
                $data['msg'] = $e->getMessage();

            }
        }

        $this->echoJsonOuptut($data);
    }  

    /**
     *  create an item
     * 
     *  @param Request $request request object.
     * 
     *  @return view
     */
    public function create(Request $request, $id = false)
    {
        $this->autofocus = 'name';

        $this->view_data['request'] = $request;
        $this->view_data['tax_types'] = $this->tax_types;
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::create($request, $id);  
    }
   
    /**
     *  store an item
     * 
     *  @param Request $request request object.
     * 
     *  @return redirect
     */
    public function store(Request $request)
    {          
        $name = ucfirst(strtolower( $request->input('name') ) );

        $name = $this->sanitizeData($name);
        $price = $this->sanitizeData($request->input('price'));
        $tax = $this->sanitizeData($request->input('tax'));  

        $exists = $this->model::FirstByNameDeleted($name);

        if ( isset($exists->name) ) {
           $request->session()->flash($this->error_message_name, Lang::get('aroaden.name_in_use', ['name' => $name]) );
           return redirect($this->main_route.'/create')->withInput();
        }

    	$validator = Validator::make($request->all(), [
            'name' => 'required|unique:servicios|max:111',
            'price' => 'required',
            'tax' => ''
	    ]);
    	        
        if ($validator->fails()) {
	        return redirect($this->main_route.'/create')
	                     ->withErrors($validator)
	                     ->withInput();
	     } else {
	        	
		    $this->model::create([
                'name' => $name,
		        'price' => $price,
		        'tax' => $tax
		    ]);
		      
		    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );	
	        	        	
	        return redirect($this->main_route.'/create');
        }     
    }
 
    /**
     *  get query result, using an ajax request
     * 
     *  @param Request $request request object.
     *  @param int $idser The ID.
     * 
     *  @return json
     */
    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $object = $this->model::find($id);
        $this->autofocus = 'name';

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['object'] = $object;
        $this->view_data['tax_types'] = $this->tax_types;
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::edit($request, $id);  
    }

    /**
     *  get query result, using ajax
     *  @return json
     */
    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $name = ucfirst(strtolower( $request->input('name') ) );
        $name = $this->sanitizeData($name);
        $price = $this->sanitizeData($request->input('price'));
        $tax = $this->sanitizeData($request->input('tax'));  

        $exists = $this->model::CheckIfExistsOnUpdate($id, $name);

        if ( isset($exists->name) ) {
            $request->session()->flash($this->error_message_name, "Nombre: $name - ya en uso, use cualquier otro.");
            return redirect("$this->main_route/$id/edit")->withInput();
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:111',
            'price' => 'required',
            'tax' => 'required'
        ]);
            
        if ($validator->fails()) {
            return redirect("$this->main_route/$id/edit")
                         ->withErrors($validator);
        } else {        
            
            $object = $this->model::find($id);
                 
            $object->name = $name;
            $object->price = $price;
            $object->tax = $tax;         
            
            $object->save();

            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

            return redirect($this->main_route);
        }   
    }

     /**
     *  get query result, using ajax
     *  @return json
     */
    public function destroy(Request $request, $id)
    {          
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $this->model::destroy($id); 

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

    /**
     *  get query result, using ajax
     *  @return json
     */
    public function getItems($busca)
    {
        $data = [];

        $main_loop = $this->model::FindStringOnField($busca);
        $count = $this->model::CountFindStringOnField($busca);

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
