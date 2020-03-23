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
        $this->view_data['username'] = Auth::user()->username;

        $this->setPageTitle(Lang::get('aroaden.settings'));

        return parent::index($request);
    }

    public function jsonSettings(Request $request)
    {
        $data = [];
        $data['page_title'] = $request->session()->get('page_title');

        $this->echoJsonOuptut($data);
    }

    public function checkPermissions(Request $request)
    {
        $data = [];
        $data['permission'] = false;

        $action = $request->input('action');
        $action = explode('.', $action);

        $type = Auth::user()->type;
        $username = Auth::user()->username;

        $permissions = Config::get('aroaden.permissions');
        $type = $permissions[$type];

        if ($username == 'admin') {

            $data['permission'] = true;

        } else {

            if ($type[$action[0]][$action[1]])
                $data['permission'] = true;

        }

        $this->echoJsonOuptut($data);
    }

}
