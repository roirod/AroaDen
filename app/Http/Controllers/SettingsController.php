<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class SettingsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->views_folder = Config::get('aroaden.routes.settings');
    }	

    public function index(Request $request)
    {  	 	  
        $username = Auth::user()->username;

        return view("$this->views_folder.index", [
            'request' => $request,
        	'username' => $username
        ]);
    }
}
