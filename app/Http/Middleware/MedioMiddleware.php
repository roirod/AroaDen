<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class MedioMiddleware
{
    public function handle($request, Closure $next)
    {         
        $type = Auth::user()->type;
          
        if ($type != 'medio')
            return redirect('/Settings');       
        
        return $next($request);
    }
}
