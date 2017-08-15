<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Pacientes;
use App\Models\Ficha;
use App\Models\Presup;
use App\Models\Tratampacien;

use Carbon\Carbon;
use Storage;
use Html;
use Image;
use Validator;
use Lang;
use Exception;

use Illuminate\Http\Request;
use App\Interfaces\BaseInterface;

trait Dir {

  public function getInstance() {

  }
  
}