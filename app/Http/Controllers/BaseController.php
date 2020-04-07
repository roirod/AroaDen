<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\DefaultTrait;
use App\Http\Controllers\Traits\BaseTrait;
use Illuminate\Http\Request;
use Exception;
use Config;
use View;
use Lang;

class BaseController extends Controller
{
  use DefaultTrait,BaseTrait;

  const APP_NAME = 'Aroa<small>Den</small>';
  const APP_NAME_TEXT = 'AroaDen';

  /**
   * @var array $config  config
   */
  protected $config;

  /**
   * @var array $config  config
   */
  protected $table_name;

  /**
   * @var array $tax_types  file contains that returns an array
   */
  protected $tax_types;

  /**
   * @var array $view_data  data that is sent to view
   */
  protected $view_data = [];

  /**
   * @var array $view_data  data that is sent to view
   */
  protected $default_img_type = 'jpg';

  /**
   * @var array $view_data  data that is sent to view
   */
  protected $img_extensions = [
    'jpg',
    'jpeg',        
    'png',
    'gif'
  ];

  /**
   * @var string $main_route  main_route
   */
  protected $main_route = '';

  /**
   * @var string $other_route  other_route
   */
  protected $other_route = '';

  /**
   * @var string $redirect_to  redirect_to
   */
  protected $redirect_to = NULL;

  /**
   * @var bool $delete_item  delete_item
   */
  protected $delete_item = false;

  /**
   * @var string $views_folder  views_folder name
   */
  protected $views_folder = '';

  /**
   * @var string $view_name  view name
   */
  protected $view_name = '';

  /**
   * @var bool $is_create_view
   */
  protected $is_create_view = true;

  /**
   * @var array $form_fields  input fields showed in form
   */
  protected $form_fields = [];

  /**
   * @var string $error_message_name  error_message_name
   */
  protected $error_message_name = 'error_message';

  /**
   * @var string $success_message_name  success_message_name
   */
  protected $success_message_name = 'success_message';

  /**
   * @var string $autofocus  autofocus
   */
  protected $autofocus = 'surname';

  /**
   * @var int $num_paginate  num_paginate
   */
  protected $num_paginate = 100;

  /**
   * @var string $page_title  page_title
   */
  protected $page_title = 'AroaDen';

  /**
   * @var string $error_message_name  error_message_name
   */
  protected $profile_photo_name = 'profile_photo';

  /**
   * @var string $profile_photo_dir  profile_photo_dir
   */
  protected $profile_photo_dir = '.profile_photo_dir';

  /**
   * @var string $files_dir  files_dir
   */
  protected $files_dir = '';

  /**
   * @var string $form_route  form_route
   */
  protected $form_route = '';

  /**
   * @var int $file_max_size  file_max_size in MB
   */
  protected $file_max_size;

  /**
   * @var string $img_folder  img_folder
   */
  protected $img_folder = '/public/assets/img';

  /**
   * @var string $img_folder  img_folder
   */
  protected $thumb_dir = '.thumbdir';

  /**
   * @var string $own_dir  own_dir
   */
  protected $own_dir = '';    

  /**
   * @var object $model  model
   */
  protected $model;    

  /**
   * @var object $model2  model
   */
  protected $model2;    

  /**
   * @var object $model3  model
   */
  protected $model3;    

  /**
   * @var object $main_object  main_object
   */
  protected $main_object;

  /**
   * @var object $request  request
   */
  protected $request;

  /**
   * @var bool $has_odontogram  si tiene odontograma o no
   */
  protected $has_odontogram = false;   

  /**
   * @var int $date_max_days
   */
  protected $date_max_days = 90;

  /**
   * @var array $misc_array  miscelaneus array
   */
  protected $misc_array = [];

  /**
   *  construct method
   */
  public function __construct()
  {
    //setlocale(LC_ALL, env('APP_LOCALE'));
    //date_default_timezone_set(env('APP_TIMEZONE'));

    $this->config = Config::get('aroaden');

    $file_max_size = (int)$this->config['files']['file_max_size'];
    $this->file_max_size = 1024 * 1024 * $file_max_size;

    if (env('CREATE_SYMLINKS'))
      $this->createSymlinks();

    if (env('CREATE_DEFAULT_COMPANY_DATA'))
      $this->createDefaultCompanyData();

    $this->form_fields = $this->config['form_fields'];
  }

  /**
   *  get index view
   * 
   *  @param object $request     
   *  @return string       
   */
  public function index(Request $request)
  {
    $this->view_name = 'index';
    $this->view_data['request'] = $request;

    return $this->loadView();
  }

  /**
   *  get create view
   * 
   *  @param object $request     
   *  @param int $id
   *  @return string       
   */
  public function create(Request $request, $id = false)
  {
    $this->view_name = 'create';
    $this->view_data['request'] = $request;

    return $this->loadView();
  }

  /**
   *  get show view
   * 
   *  @param object $request     
   *  @param int $id
   *  @return string       
   */
  public function show(Request $request, $id)
  {
    $this->view_name = 'show';
    $this->view_data['request'] = $request;
    
    return $this->loadView();
  }

  /**
   *  get edit view
   * 
   *  @param object $request     
   *  @param int $id
   *  @return string       
   */
  public function edit(Request $request, $id)
  {
    $this->view_name = 'edit';
    $this->is_create_view = false;
    $this->view_data['request'] = $request;

    return $this->loadView();
  }

  /**
   *  get list
   * 
   *  @param object $request     
   *  @param int $id
   *  @return string       
   */
  public function list(Request $request)
  {
    $string = $request->input('string');
    $this->misc_array['string'] = $this->sanitizeData($string);

    $data = [];

    try {               

      $data = $this->getArrayResult();

    } catch (Exception $e) {

      $data['error'] = true; 
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
  }

  /**
   *  costumize load View
   *
   *  @return string html code
   */
  protected function loadView()
  {       
    $this->passVarsToViews();

    $view = $this->views_folder.".".$this->view_name;

    return view($view, $this->view_data);
  }

  /**
   *  return View String
   *
   *  @return string html code
   */
  protected function returnViewString()
  {
    $view = $this->views_folder.".".$this->view_name;

    return (string)View::make($view, $this->view_data);
  }

  /**
   *  pass Vars To Views
   */
  protected function passVarsToViews()
  {
    View::share('app_name', self::APP_NAME);
    View::share('app_name_text', self::APP_NAME_TEXT);
    View::share('page_title', $this->page_title);
    View::share('autofocus', $this->autofocus);
    View::share('is_create_view', $this->is_create_view);

    View::share('main_route', $this->main_route);
    View::share('other_route', $this->other_route);
    View::share('form_route', $this->form_route);        

    View::share('patients_route', $this->config['routes']['patients']);
    View::share('invoices_route', $this->config['routes']['invoices']);
    View::share('budgets_route', $this->config['routes']['budgets']);
    View::share('company_route', $this->config['routes']['company']);
    View::share('appointments_route', $this->config['routes']['appointments']);
    View::share('staff_route', $this->config['routes']['staff']);
    View::share('staff_positions_route', $this->config['routes']['staff_positions']);
    View::share('services_route', $this->config['routes']['services']);
    View::share('accounting_route', $this->config['routes']['accounting']);
    View::share('treatments_route', $this->config['routes']['treatments']);        
    View::share('settings_route', $this->config['routes']['settings']);
  }

  /**
   *  set Page Title
   * 
   *  @param string $data
   */
  protected function setPageTitle($data)
  {
    $data = $data.' - '.$this->page_title;

    $this->page_title = $data;

    session(['page_title' => $data]);
  }

  /**
   *  get Array Result
   *  
   *  @return array data
   */
  protected function getArrayResult()
  {   
    $count = $this->model::CountAll();  

    if ((int)$count === 0)
      throw new Exception(Lang::get('aroaden.empty_db'));

    return $this->getQueryResult();
  } 

  /**
   *  get Query Result
   *  
   *  @throws Exception
   *  @return array data
   */
  private function getQueryResult()
  {
    $string = $this->misc_array['string'];

    $main_loop = $this->model::FindStringOnField($string);
    $count = $this->model::CountFindStringOnField($string);

    if ((int)$count === 0)
      throw new Exception(Lang::get('aroaden.no_query_results'));

    $data = [];
    $data['main_loop'] = $main_loop;      
    $data['msg'] = $count;

    return $data;
  }

  /**
   *  destroy
   * 
   *  @param object $request     
   *  @param int $id 
   */
  public function destroy(Request $request, $id)
  {
    $data['error'] = false;

    try {

      if (isset($this->misc_array['checkDestroy']) && $this->misc_array['checkDestroy'])
        $this->model::checkDestroy($id);           

      $this->model::destroy($id);

      if (isset($this->misc_array['count']) && $this->misc_array['count'])
        $data['count'] = $this->model::CountAll();

    } catch (Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
  }

}