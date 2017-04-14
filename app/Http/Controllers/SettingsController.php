<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Http\Requests;

class SettingsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }	

    public function index(Request $request)
    {  	 	  
        $username = Auth::user()->username;

        return view('set.index', [
            'request' => $request,
        	'username' => $username
        ]);
    }
}
