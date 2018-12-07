<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Lang;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {    	  
    	$username = Auth::user()->username;
    	  
    	if ( $username != 'admin' ) {

            if($request->ajax()) {
                
                header('HTTP/1.1 403 Forbidden');
                exit();

            } else {

                $request->session()->flash('error_message', Lang::get('aroaden.deny_access') );  
                return redirect()->back(); 

            }
    	}

        return $next($request);       
    }
}
