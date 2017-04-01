<?php

namespace App\Http\Controllers;

class BaseController extends Controller
{ 
    protected function convertYmdToDmY($date)
    {   
        $date = date('d-m-Y', strtotime($date));

        return $date;
    }

}