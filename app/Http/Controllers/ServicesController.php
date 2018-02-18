<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exceptions\NoQueryResultException;
use App\Http\Controllers\Interfaces\BaseInterface;
use Illuminate\Http\Request;
use App\Models\Services;
use Exception;
use Validator;
use Lang;
use Auth;
use DB;

class ServicesController extends BaseController implements BaseInterface
{
    /**
     *  construct method
     */
    public function __construct(Services $services)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['services'];
        $this->views_folder = $this->config['routes']['services'];
        $this->tax_types = $this->config['tax_types'];
        $this->form_route = 'list';
        $this->table_name = 'services';
        $this->model = $services;     

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
        return $this->commonProcess($request, 'index');  
    }

    /**
     *  get index page, show the company data
     * 
     *  @return view       
     */
    public function ajaxIndex(Request $request)
    {
        return $this->commonProcess($request, 'ajaxIndex');
    }

    /**
     *  commonProcess
     * 
     *  @return object $obj get object          
     */
    private function commonProcess($request, $view_name)
    {
        $main_loop = $this->model::AllOrderByName($this->num_paginate);
        $count = $this->model::CountAll();       

        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['request'] = $request;
        $this->view_data['count'] = $count;
        $this->view_data['form_fields'] = $this->form_fields;

        $this->setPageTitle(Lang::get('aroaden.services'));

        return $this->loadView($this->views_folder.".$view_name", $this->view_data);
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
        $this->misc_array['string'] = $this->sanitizeData($request->input('string'));
        $data = [];

        $count = $this->model::CountAll();

        if ((int)$count === 0) {

            $data['error'] = true;   
            $data['msg'] = Lang::get('aroaden.no_services_on_db');
            $this->echoJsonOuptut($data);

        }

        try {  

            $data = $this->getQueryResult();

        } catch (NoQueryResultException $e) {

            $data['error'] = true;    
            $data['msg'] = $e->getMessage();

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

        $this->setPageTitle(Lang::get('aroaden.create_service'));

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
        $name = ucfirst($request->input('name'));

        $name = $this->sanitizeData($name);
        $price = $this->sanitizeData($request->input('price'));
        $tax = $this->sanitizeData($request->input('tax'));  

        $exists = $this->model::FirstByNameDeleted($name);

        if ( isset($exists->name) ) {
           $request->session()->flash($this->error_message_name, Lang::get('aroaden.name_in_use', ['name' => $name]));
           return redirect($this->main_route.'/create')->withInput();
        }

    	$validator = Validator::make($request->all(), [
            'name' => "required|unique:$this->table_name|max:111",
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

        $this->setPageTitle(Lang::get('aroaden.edit_service'));

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
     *
     *  @return json
     */
    private function getQueryResult()
    {
        $string = $this->misc_array['string'];

        $main_loop = $this->model::FindStringOnField($string);
        $count = $this->model::CountFindStringOnField($string);

        if ((int)$count === 0)
            throw new NoQueryResultException(Lang::get('aroaden.no_query_results'));

        $data = [];
        $data['main_loop'] = $main_loop;      
        $data['msg'] = $count;
        return $data;
    }
}
