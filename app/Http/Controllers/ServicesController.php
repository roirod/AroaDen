<?php

namespace App\Http\Controllers;

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

    $this->main_route = $this->config['routes']['services'];
    $this->views_folder = $this->config['routes']['services'];
    $this->tax_types = $this->config['tax_types'];
    $this->form_route = 'list';
    $this->table_name = 'services';
    $this->model = $services;     

    $fields = [
      'name' => true,
      'tax' => true,
      'price' => true,
      'pricetax' => true,
      'save' => true
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
    $this->view_name = 'index';

    return $this->commonProcess($request);
  }

  /**
   *  get index page, show the company data
   * 
   *  @return view       
   */
  public function ajaxIndex(Request $request)
  {
    $this->view_name = 'ajaxIndex';

    return $this->commonProcess($request);
  }

  /**
   *  commonProcess
   * 
   *  @return object $obj get object          
   */
  private function commonProcess($request)
  {
    $this->view_data['request'] = $request;
    $this->view_data['main_loop'] = $this->model::AllOrderByName();
    $this->view_data['count'] = $this->model::CountAll();

    $this->setPageTitle(Lang::get('aroaden.services'));

    return $this->loadView();
  }

  /**
   *  get query result, using ajax
   * 
   *  @param Request $request request object.
   * 
   *  @return string JSON
   */
  public function search(Request $request)
  {
    $string = $this->sanitizeData($request->input('string'));
    $main_loop = $this->model::FindStringOnField($string);
    $count = $this->model::CountFindStringOnField($string);

    $this->view_data['main_loop'] = $main_loop;
    $this->view_data['request'] = $request;
    $this->view_data['count'] = $count;
    $this->view_data['searched_text'] = $string;

    $this->view_name = 'servicesList';

    return $this->loadView();
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
    $this->view_data['tax_types'] = $this->tax_types;
    $this->view_data["legend"] = Lang::get('aroaden.create_service');

    $this->setPageTitle(Lang::get('aroaden.create_service'));

    return parent::create($request);
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
    $data = [];
    $data['error'] = false;

    $this->request = $request;
    $this->validateInputs();

    try {

      extract($this->sanitizeRequest($request->all()));

      $this->validateCurrency($price);

      $exists = $this->model::FirstByName($name);

      if (isset($exists->name))
        throw new Exception(Lang::get('aroaden.name_in_use', ['name' => $name]));

      $price = $this->formatCurrencyDB($price);

      $this->model::create([
        'name' => $name,
        'price' => $price,
        'tax' => $tax
      ]);

    } catch (Exception $e) {

      $data['error'] = true; 
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
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
    $id = $this->sanitizeData($id);
    $object = $this->model::find($id);

    $this->autofocus = 'name';
    $this->view_data['id'] = $id;
    $this->view_data['object'] = $object;
    $this->view_data['tax_types'] = $this->tax_types;
    $this->view_data["legend"] = Lang::get('aroaden.edit_service');

    $this->setPageTitle(Lang::get('aroaden.edit_service'));

    return parent::edit($request, $id);  
  }

  /**
   *  get query result, using ajax
   *  @return json
   */
  public function update(Request $request, $id)
  {
    $data = [];
    $data['error'] = false;

    $this->request = $request;
    $this->validateInputs();

    try {

      extract($this->sanitizeRequest($request->all()));

      $this->validateCurrency($price);

      $id = $this->sanitizeData($id);
      $exists = $this->model::CheckIfExistsOnUpdate($id, $name);         

      if (isset($exists->name))
        throw new Exception(Lang::get('aroaden.name_in_use', ['name' => $name]));

      $object = $this->model::find($id);
      $object->name = $name;
      $object->price = $this->formatCurrencyDB($price);
      $object->tax = $tax;            
      $object->save();

    } catch (Exception $e) {

      $data['error'] = true; 
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
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
