<?php

namespace App\Http\Controllers\Auth;

use App\User;
use DB;
use Validator;
use Config;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $username = 'username';  
    protected $redirectTo = '/home';
    protected $redirectAfterLogout = '/login';
    protected $loginPath = '/login';
    
    
    public function __construct()
    {
        $this->checkIfUserExists();

        $this->middleware('guest', ['except' => 'logout']);
    }

    private function checkIfUserExists()
    {
        $default_users = Config::get('default_users');

        foreach ($default_users as $user) {

            $exits = User::where('username', $user["username"])->first();

            if ($exits === null) {

                DB::table('users')->insert([
                    'username' => $user["username"],
                    'password' => bcrypt($user["password"]),
                    'tipo' => $user["tipo"]
                ]);                
                
            }

        }

        return redirect("/login");

    }

    protected function validator(array $data) {}

    protected function create(array $data) {}

}
