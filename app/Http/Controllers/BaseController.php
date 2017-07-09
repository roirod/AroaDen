<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Settings;
use Config;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
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
     *  construct method
     */
    public function __construct()
    {
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
            'tax' => false,
            'hour' => false,
            'day' => false,
            'notes' => false,
            'save' => false,
        ];
    }

    public function create(Request $request, $id = false)
    {
        return view($this->views_folder.'.create', $this->view_data);   
    }

    public function edit(Request $request, $id, $idcit = false)
    {
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
        $data = htmlentities(trim($data),ENT_QUOTES,"UTF-8");

        return $data;
    }

    protected function redirectIfIdIsNull($id, $route)
    {   
        if ( null == $id )
            return redirect($route);
    }

}