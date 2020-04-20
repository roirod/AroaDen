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
        $uid = Auth::user()->uid;

    	if ($username != 'admin' && (int)$uid !== 1) {
            if($request->ajax())
                return response('Forbidden', 403);

            $request->session()->flash('error_message', Lang::get('aroaden.deny_access') );  
            return redirect()->back(); 
    	}

        return $next($request);       
    }
}
