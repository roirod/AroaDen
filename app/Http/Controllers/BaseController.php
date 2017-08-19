<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Settings;
use Config;
use View;
use Illuminate\Http\Request;

class BaseController extends Controller
{
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
    protected $file_max_size = 1024 * 1024 * 3;

    /**
     * @var string $img_folder  img_folder
     */
    protected $img_folder = '/public/assets/img';

    /**
     * @var string $own_dir  own_dir
     */
    protected $own_dir = '';    

    /**
     *  construct method
     */
    public function __construct()
    {
        setlocale( LC_ALL, env('APP_LC_ALL') );
        date_default_timezone_set( env('APP_TIMEZONE') );

        $this->checkIfSettingExists();
        $this->passVarsToViews();

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
            'per' => false,
            'notes' => false,
            'save' => false,
        ];
    }

    public function index(Request $request)
    {
        $this->passVarsToViews();

        return view($this->views_folder.'.index', $this->view_data);
    }

    public function create(Request $request, $id = false)
    {
        $this->passVarsToViews();

        return view($this->views_folder.'.create', $this->view_data);   
    }

    public function edit(Request $request, $id)
    {
        $this->passVarsToViews();

        return view($this->views_folder.'.edit', $this->view_data);   
    }

    private function checkIfSettingExists()
    {
        $settings_fields = Config::get('settings_fields');

        foreach ($settings_fields as $field) {

            $exits = Settings::where('key', $field)->first();

            if ($exits == null) {

                DB::table('settings')->insert([
                    'key' => $field,
                    'value' => 'none'
                ]);                
                
            }

        }

        return redirect()->back(); 
    }

    protected function convertYmdToDmY($date)
    {   
        $date = date('d-m-Y', strtotime($date));

        return $date;
    }

    protected function validateDate($date)
    {   
        list($y, $m, $d) = array_pad(explode('-', $date, 3), 3, 0);

        return ctype_digit("$y$m$d") && checkdate($m, $d, $y);
    }

    protected function validateTime($time)
    {   
        if ( preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $time) )
            return true;

        return false;
    }

    protected function sanitizeData($data)
    {   
        $data = htmlentities(trim($data), ENT_QUOTES, "UTF-8");

        return $data;
    }

    protected function redirectIfIdIsNull($id, $route)
    {   
        if ( null == $id )
            return redirect($route);
    }

    protected function passVarsToViews()
    {   
        View::share('page_title', $this->page_title);
        View::share('autofocus', $this->autofocus);
        View::share('main_route', $this->main_route);        
    }

    protected function echoJsonOuptut($data)
    {   
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($data);
        exit();    
    }

}