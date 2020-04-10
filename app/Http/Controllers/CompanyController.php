<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Redis;
use Lang;

class CompanyController extends BaseController
{
  /**
   *  __construct method          
   */
  public function __construct(Settings $settings)
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['company'];
    $this->views_folder = $this->config['routes']['company'];
    $this->model = $settings;        
  }

  /**
   *  get index page, show the company data
   * 
   *  @return view       
   */
  public function index(Request $request)
  {
    $this->view_name = 'index';
    $this->request = $request;

    return $this->commonProcess();
  }

  /**
   *  get index page, show the company data
   * 
   *  @return view       
   */
  public function ajaxIndex(Request $request)
  {
    $this->view_name = 'ajaxIndex';
    $this->request = $request;

    return $this->commonProcess();
  }

  /**
   *  edit the company data
   * 
   *  @return view         
   */
  public function editData(Request $request)
  {
    $this->view_name = 'edit';
    $this->request = $request;

    return $this->commonProcess();
  }

  /**
   *  create object from key value pair array in this format $obj->obj_key
   * 
   *  @return object $obj get object          
   */
  private function commonProcess()
  {
    $this->view_data['obj'] = $this->getSettings();
    $this->view_data['request'] = $this->request;
    $this->view_data['main_loop'] = $this->config['settings_fields'];

    if ($this->view_name == 'edit') {

      $this->view_data['form_route'] = 'saveData';
      $this->setPageTitle(Lang::get('aroaden.company_edit_data'));

    } else {

      $this->view_data['form_route'] = 'editData';
      $this->setPageTitle(Lang::get('aroaden.company_data'));

    }

    return $this->loadView();
  }

  /**
   *  save the company data
   *  @param  object  $request   
   *  @return view         
   */
  public function saveData(Request $request)
  {
    $company_data = $this->model::select('key', 'value')->get()->toArray();

    foreach ($company_data as $arr => $value) {
      foreach ($request->input() as $request_key => $request_value) {
        $request_value = $this->sanitizeData($request_value);

        if ($value["key"] == $request_key)
          $this->model::where('key', $request_key)->update(['value' => $request_value]);
      }
    }

    if (env('REDIS_SERVER_IS_ON')) {
      $settings = Settings::getArray();

      Redis::set('settings', json_encode($settings));               
    }

    return $this->ajaxIndex($request);
  }

}
