<?php

namespace App\Http\Middleware;

use Closure;

use Auth;

class MedioMiddleware
{
    public function handle($request, Closure $next)
    {         
        $tipo = Auth::user()->tipo;
          
        if ($tipo != 'medio') {
            return redirect('/Ajustes');
        }             
        
        return $next($request);
    }
}
