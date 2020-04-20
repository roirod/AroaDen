<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Traits\DefaultTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Config;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers,DefaultTrait;

    protected $redirectTo = '/home';
    protected $redirectAfterLogout = '/login';
    protected $loginPath = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (env('CREATE_DEFAULT_USERS'))
            $this->checkIfUserExists();
        
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function logout()
    {
        Auth::logout();

        return redirect($this->loginPath);
    }

    public function username()
    {
        return 'username';
    }

}
