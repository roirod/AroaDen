<?php

namespace App\Http\Controllers\Auth;

use App\User;
use DB;
use Validator;
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
        $this->checkIfAdminExists();

        $this->middleware('guest', ['except' => 'logout']);
    }

    private function checkIfAdminExists()
    {
        $admin = User::where('username', 'admin')->first();

        if ($admin === null) {
            DB::table('users')->insert([
                'username' => 'admin',
                'password' => bcrypt('admin'),
                'tipo' => 'medio'
            ]);
            
            DB::table('users')->insert([
                'username' => 'normal',
                'password' => bcrypt('normal'),
                'tipo' => 'normal'
            ]);

            DB::table('users')->insert([
                'username' => 'medio',
                'password' => bcrypt('medio'),
                'tipo' => 'medio'
            ]); 

            return redirect("/login");
        }
    }

    protected function validator(array $data)
    {
    }

    protected function create(array $data)
    {
    }
}
