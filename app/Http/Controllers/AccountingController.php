<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lang;

class AccountingController extends BaseController
{

  public function __construct()
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['accounting'];
    $this->views_folder = $this->config['routes']['accounting'];        
  }

  public function index(Request $request)
  {
    $this->setPageTitle(Lang::get('aroaden.accounting'));

    return parent::index($request);
  }

}
