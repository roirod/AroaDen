<?php

namespace App\Http\Controllers\Facturas;

use DB;
use Validator;
use App\Models\Factutex;
use App\Models\Facturas;
use App\Models\Empre;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class FacturaFactory extends BaseController
{

    public static function chose($type){
        switch($type){
            case 'Completa':
                return new Completa();
            break;
            case 'Rectificativa':
                return new Rectificativa();
            break;
            default:
                throw new Exception('Fail');
            break;
        }
    }
}