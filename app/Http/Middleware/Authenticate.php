<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if($request->ajax())
            return header('HTTP/1.1 401 Unauthorized');

        if (empty(Auth::user()))
            return redirect()->guest('login');

        if (Auth::guard($guard)->guest())
            return redirect()->guest('login');  

        return $next($request);
    }
}
