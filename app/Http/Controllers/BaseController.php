<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\BaseTrait;
use Illuminate\Http\Request;
use App\Models\Settings;
use Config;
use Redis;
use View;
use DB;

class BaseController extends Controller
{
    use BaseTrait;

    const APP_NAME = 'Aroa<small>Den</small>';

    /**
     * @var array $config  config
     */
    protected $config;

    /**
     * @var array $tax_types  file contains that returns an array
     */
    protected $tax_types;

    /**
     * @var array $view_data  data that is sent to view
     */
    protected $view_data = [];

    /**
     * @var string $main_route  main_route
     */
    protected $main_route = '';

    /**
     * @var string $other_route  other_route
     */
    protected $other_route = '';

    /**
     * @var string $views_folder  views_folder name
     */
    protected $views_folder = '';

    /**
     * @var string $view_name  view name
     */
    protected $view_name = '';

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
    protected $profile_photo_name = '.profile_photo.jpg';

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
     * @var bool $has_odogram  si tiene odontograma o no
     */
    protected $has_odogram = false;   

    /**
     * @var bool $has_odogram  si tiene odontograma o no
     */
    protected $date_max_days = 60;

    /**
     *  construct method
     */
    public function __construct()
    {
        setlocale( LC_ALL, env('APP_LC_ALL') );
        date_default_timezone_set( env('APP_TIMEZONE') );

        $this->config = Config::get('aroaden');

        $file_max_size = (int)$this->config['files']['file_max_size'];
        $this->file_max_size = 1024 * 1024 * $file_max_size;

        $this->checkIfSettingExists();

        $this->form_fields = [
            'surname' => false,
            'name' => false,
            'position' => false,
            'address' => false,
            'city' => false,
            'birth' => false,
            'dni' => false,
            'sex' => false,
            'tel1' => false,
            'tel2' => false,
            'tel3' => false,
            'units' => false,
            'price' => false,
            'paid' => false,            
            'tax' => false,
            'hour' => false,
            'day' => false,
            'issue_date' => false,
            'no_tax_msg' => false,
            'per' => false,            
            'notes' => false,
            'save' => false,
        ];
    }

    /**
     *  get index view
     * 
     *  @param object $request     
     *  @return string       
     */
    public function index(Request $request)
    {
        return $this->loadView($this->views_folder.'.index', $this->view_data);
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
        return $this->loadView($this->views_folder.'.create', $this->view_data);
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
        return $this->loadView($this->views_folder.'.show', $this->view_data);
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
        return $this->loadView($this->views_folder.'.edit', $this->view_data);
    }

    /**
     *  costumize load View
     * 
     *  @param string $view
     *  @param array $view_data     
     */
    protected function loadView($view, $view_data, $response = false)
    {       
        $this->passVarsToViews();

        if ($response) {
            return response()->view($view, $view_data)
               ->header('Expires', 'Sun, 01 Jan 2004 00:00:00 GMT')
               ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
               ->header('Cache-Control', ' post-check=0, pre-check=0', FALSE)
               ->header('Pragma', 'no-cache');
        }

        return view($view, $view_data);
    }

    /**
     *  check If Setting Exists 
     */
    private function checkIfSettingExists()
    {
        $settings_fields = $this->config['settings_fields'];

        foreach ($settings_fields as $field) {
            $exits = Settings::getValueByKey($field['name']);

            if ($exits == null) {
                DB::table('settings')->insert([
                    'key' => $field['name'],
                    'value' => ''
                ]);                
            }
        }

        $exists = Redis::exists('settings');

        if (!$exists) {
            $settings = Settings::getArray();

            Redis::set('settings', json_encode($settings));
        }

        return redirect()->back(); 
    }

    /**
     *  pass Vars To Views
     */
    protected function passVarsToViews()
    {
        View::share('app_name', self::APP_NAME);
        View::share('page_title', $this->page_title);
        View::share('autofocus', $this->autofocus);
        View::share('main_route', $this->main_route);
        View::share('other_route', $this->other_route);
        View::share('form_route', $this->form_route);

        View::share('patients_route', $this->config['routes']['patients']);
        View::share('invoices_route', $this->config['routes']['invoices']);
        View::share('budgets_route', $this->config['routes']['budgets']);
        View::share('company_route', $this->config['routes']['company']);
        View::share('appointments_route', $this->config['routes']['appointments']);
        View::share('staff_route', $this->config['routes']['staff']);
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
        $this->page_title = $data.' - '.$this->page_title;
    }

}