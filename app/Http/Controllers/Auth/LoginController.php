<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Traits\BaseTrait;
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

    use AuthenticatesUsers,BaseTrait;

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
        $this->checkIfUserExists();
        
        $this->middleware('guest', ['except' => 'logout']);
    }

    private function checkIfUserExists()
    {
        if ( env('CREATE_DEFAULT_USERS') ) {
            $default_users = Config::get('aroaden.default_users');

            foreach ($default_users as $user) {

                $exits = User::where('username', $user["username"])->first();

                if ($exits == null) {

                    User::insert([
                        'username' => $user["username"],
                        'password' => bcrypt($user["password"]),
                        'type' => $user["type"],
                        'full_name' => $user["full_name"]
                    ]);                
                    
                }

            }

            return redirect("/login");
        }
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
