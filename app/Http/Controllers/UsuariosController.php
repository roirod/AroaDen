<?php

namespace App\Http\Controllers;

use App\User;
use DB;
use Validator;

use Illuminate\Http\Request;
use App\Http\Requests;

class UsuariosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(Request $request)
    { }
    
    public function create(Request $request)
    {
    	$users = DB::table('users')->orderBy('username','ASC')->get();

    	return view('user.create',[
            'request' => $request,
            'users' => $users
        ]);	
    }    

    public function store(Request $request)
    {    	
    	$users = DB::table('users')->get();
    	  
    	$password = trim( $request->input('password') );
    	$username = htmlentities (trim($request->input('username')),ENT_QUOTES,"UTF-8");
        $tipo = htmlentities (trim($request->input('tipo')),ENT_QUOTES,"UTF-8");
    	  
    	foreach ($users as $user) {
    	   if ($user->username == $username) {
    	       $request->session()->flash('errmess', 'Nombre de usuario en uso, use cualquier otro.');
    	       return redirect('Usuarios/create');
    	   }
    	}	 
    	 	
        $validator = Validator::make($request->all(),[
            'username' => 'required|unique:users|max:44',
            'password' => 'required|max:44',
            'tipo' => 'required|max:44'
        ]);
            
        if ($validator->fails()) {
	         return redirect('/Usuarios/create')
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {
	     		
	    	if ( $username == 'admin' ) {
	    		$request->session()->flash('errmess', 'Nombre de usuario no permitido, use cualquier otro.');	
	    	  	return redirect('Usuarios/create');
	    	}	     		
        	     	        	
	        User::create([
	          'username' => $username,
	          'password' => bcrypt($password),
              'tipo' => $tipo
            ]);
	      
            $request->session()->flash('sucmess', 'Hecho!!!');	
        	        	
        	return redirect('Usuarios/create');
        }      
    }
    
    public function usuedit(Request $request)
    {
    	$users = DB::table('users')->get();

    	return view('user.usuedit', [
            'request' => $request,
    	    'users' => $users
        ]);  
    }

    public function usudel(Request $request)
    {
        $users = DB::table('users')->get();

        return view('user.usudel', [
            'request' => $request,
            'users' => $users
        ]);
    }

    public function show($id)
    { }

    public function edit($id)
    { }

    public function saveup(Request $request)
    {
        $uid = htmlentities (trim($request->input('uid')),ENT_QUOTES,"UTF-8");
        $password = $request->input('password');

        if ( null === $uid ) {
            return redirect('Ajustes');
        }  

        $validator = Validator::make($request->all(),[
            'uid' => 'required',
            'password' => 'required|max:44'
        ]);
            
        if ($validator->fails()) {
             return redirect('/Usuarios/usuedit')
                         ->withErrors($validator)
                         ->withInput();
        } else {
              
            $User = User::find($uid);
              
            $User->password = bcrypt($password);
              
            $User->save();
              
            $request->session()->flash('sucmess', 'Hecho!!!');
                        
            return redirect('/Usuarios/create');
        }  
    }
     
    public function update(Request $request,$uid)
    { }

    public function delete(Request $request)
    {
        $uid = htmlentities (trim($request->input('uid')),ENT_QUOTES,"UTF-8");

        if ( null === $uid ) {
            return redirect('Ajustes');
        }  
        
        $User = User::find($uid);
      
        $User->delete();

        $request->session()->flash('sucmess', 'Hecho!!!');
        
        return redirect('/Usuarios/create');
    }

    public function destroy(Request $request,$uid)
    { }
}
