<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaffPositions;
use Validator;
use Exception;
use Lang;

class StaffPositionsController extends BaseController
{

    public function __construct(StaffPositions $staffPositions)
    {
        parent::__construct();

        $this->main_route = $this->config['routes']['staff_positions'];
        $this->other_route = $this->config['routes']['staff'];        
        $this->views_folder = $this->config['routes']['staff_positions'];
        $this->form_route = 'list';
        $this->model = $staffPositions;

        $fields = [
            'name' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->setPageTitle(Lang::get('aroaden.staff_positions'));

        $this->view_data['main_loop'] = StaffPositions::AllOrderByName();
        $this->view_data['count'] = StaffPositions::CountAll();        

        return parent::index($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id = false)
    {   
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::create($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $this->sanitizeData($request->input('name'));
        $this->view_name = 'create';

        $exists = $this->model::FirstByName($name);         

        if ( isset($exists->name) ) {
            $msg = Lang::get('aroaden.name_in_use', ['name' => $name]);
            $request->session()->flash($this->error_message_name, $msg);
            return redirect("$this->main_route/$this->view_name");
        }

        $this->model::create([
          'name' => $name
        ]);
      
        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        return redirect("$this->main_route");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $this->autofocus = 'name';

        $this->view_data['id'] = $id;
        $this->view_data['object'] = $this->model::find($id);
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::edit($request, $id);  
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);
        $name = $this->sanitizeData($request->input('name'));
        $this->view_name = 'edit';

        $exists = $this->model::CheckIfExistsOnUpdate($id, $name);         

        if ( isset($exists->name) ) {
            $msg = Lang::get('aroaden.name_in_use', ['name' => $name]);
            $request->session()->flash($this->error_message_name, $msg);
            return redirect("$this->main_route/$id/$this->view_name");
        }

        $object = $this->model::find($id);
        $object->name = $name;    
        $object->save();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        return redirect("$this->main_route");
    }

}
