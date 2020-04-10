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
      'save' => true
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
    $name = $this->sanitizeData($request->name);
    $route = "$this->main_route/create";

    try {

      $exists = $this->model::FirstByName($name);         

      if ( isset($exists->name) )
        throw new Exception(Lang::get('aroaden.name_in_use', ['name' => $name]));

      $this->model::create([
        'name' => $name
      ]);
    
    } catch (Exception $e) {

      $request->session()->flash($this->error_message_name, $e->getMessage());
      return redirect($route);

    }
    
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
    $id = $this->sanitizeData($id);    
    $this->redirectIfIdIsNull($id, $this->main_route);
    $name = $this->sanitizeData($request->name);
    $route = "$this->main_route/$id/edit";

    try {

      $exists = $this->model::CheckIfExistsOnUpdate($id, $name);         

      if ( isset($exists->name) )
        throw new Exception(Lang::get('aroaden.name_in_use', ['name' => $name]));

      $object = $this->model::find($id);
      $object->name = $name;    
      $object->save();

    } catch (Exception $e) {

      $request->session()->flash($this->error_message_name, $e->getMessage());
      return redirect($route);

    }

    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
    return redirect("$this->main_route");
  }

  /**
   *  destroy
   */
  public function destroy(Request $request, $id)
  {
    $this->misc_array['checkDestroy'] = true;
    $this->misc_array['count'] = true;

    return parent::destroy($request, $id);  
  }

}
