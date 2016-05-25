<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TestController extends Controller
{
	
    public function __construct()
    {
        $this->middleware('auth');    
    }   

    public function test(Request $request)
    {
	      return view('test', ['request' => $request]);
    }
    
}
