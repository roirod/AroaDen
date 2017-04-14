<?php

namespace App\Http\Controllers;

use DB;
use App\Settings;
use Config;

class BaseController extends Controller
{
    
    public function __construct()
    {
        $this->checkIfSettingExists();
    }

    private function checkIfSettingExists()
    {
        $settings_fields = Config::get('settings_fields');

        foreach ($settings_fields as $field) {

            $exits = Settings::where('key', $field)->first();

            if ($exits === null) {

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

}