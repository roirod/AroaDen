<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;
use Auth;
use Lang;

class SettingsController extends BaseController
{
  public function __construct()
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['users'];
    $this->views_folder = $this->config['routes']['settings'];
  }   

  public function index(Request $request)
  {
    $this->setPageTitle(Lang::get('aroaden.settings'));

    return parent::index($request);
  }

  public function jsonSettings(Request $request)
  {
    $data = [];
    $data['page_title'] = $request->session()->get('page_title');

    $this->echoJsonOuptut($data);
  }

}
