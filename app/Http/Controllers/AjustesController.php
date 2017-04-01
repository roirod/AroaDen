<?php

namespace App\Http\Controllers;

use Auth;

use Illuminate\Http\Request;
use App\Http\Requests;

class AjustesController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }	

    public function index(Request $request)
    {  	 	  
  	  
    	  $username = Auth::user()->username;
    	  
    	  return view('ajus.index', ['request' => $request,
            						 'username' => $username]);
    }
}
