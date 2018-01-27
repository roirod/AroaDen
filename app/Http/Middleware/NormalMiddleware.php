<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Lang;

class NormalMiddleware
{
    public function handle($request, Closure $next)
    {         
        $type = Auth::user()->type;
          
        if ($type != 'normal') {
    		$request->session()->flash('error_message', Lang::get('aroaden.deny_access') );  
	    	return redirect()->back(); 
    	} 
        
        return $next($request);
    }
}
