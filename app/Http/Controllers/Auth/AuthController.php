<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\Http\Controllers\Controller;
use App\Models\User;
use Validator;
use Config;
use Auth;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $username = 'username';  
    protected $redirectTo = 'home';
    protected $redirectAfterLogout = 'login';
    protected $loginPath = 'login';
    
    
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
                        'type' => $user["type"]
                    ]);                
                    
                }

            }

            return redirect("/login");
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : 'login');
    }

    protected function validator(array $data) {}

    public function create(array $data) {}

}
