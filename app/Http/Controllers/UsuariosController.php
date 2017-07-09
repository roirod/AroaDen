<?php

namespace App\Http\Controllers;

use App\Models\User;
use DB;
use Validator;

use Illuminate\Http\Request;

class UsuariosController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Usuarios';
        $this->views_folder = 'user';
    }
      
    public function create(Request $request, $id = false)
    {
        $main_loop = User::orderBy('username','ASC')->get();

    	return view($this->views_folder.'.create',[
            'request' => $request,
            'main_loop' => $main_loop
        ]);	
    }    

    public function store(Request $request)
    {    	  	  
    	$password = trim( $request->input('password') );
    	$username = $this->sanitizeData($request->input('username'));
        $type = $this->sanitizeData($request->input('type'));

        if ( $username == 'admin' ) {
            $request->session()->flash($error_message_name, 'Nombre de usuario no permitido, use cualquier otro.');   
            return redirect($this->main_route.'/create');
        }   

        $exists = User::where('username', $username)->exists();

        if ($exists) {
           $request->session()->flash($error_message_name, 'Nombre de usuario: $username - en uso, use cualquier otro.');
           return redirect($this->main_route.'/create');
        }

        $validator = Validator::make($request->all(),[
            'username' => 'required|unique:users|max:44',
            'password' => 'required|max:44',
            'type' => 'required|max:44'
        ]);
            
        if ($validator->fails()) {
	         return redirect($this->main_route.'/create')
	                     ->withErrors($validator)
	                     ->withInput();
	    } else { 		
        	     	        	
	        User::create([
	          'username' => $username,
	          'password' => bcrypt($password),
              'type' => $type
            ]);
	      
            $request->session()->flash($success_message_name, Lang::get('aroaden.success_message') );	
        	        	
        	return redirect($this->main_route);
        }      
    }
    
    public function userEdit(Request $request)
    {
        $main_loop = User::orderBy('username','ASC')->get();

    	return view($this->views_folder.'.userEdit', [
            'request' => $request,
            'main_loop' => $main_loop
        ]);  
    }

    public function userDeleteViev(Request $request)
    {
        $main_loop = User::orderBy('username','ASC')->get();
        
        return view($this->views_folder.'.userDeleteViev', [
            'request' => $request,
            'main_loop' => $main_loop
        ]);
    }

    public function userUpdate(Request $request)
    {
        $uid = $this->sanitizeData($request->input('uid'));
        $password = trim( $request->input('password') );

        $this->redirectIfIdIsNull($uid, 'Settings');

        $validator = Validator::make($request->all(),[
            'uid' => 'required',
            'password' => 'required|max:44'
        ]);
            
        if ($validator->fails()) {
             return redirect($this->main_route.'/userEdit')
                         ->withErrors($validator)
                         ->withInput();
        } else {
              
            $user = User::find($uid);
            $user->password = bcrypt($password);
            $user->save();
              
            $request->session()->flash($success_message_name, Lang::get('aroaden.success_message') );
                        
            return redirect($this->main_route);
        }  
    }

    public function userDelete(Request $request)
    {
        $uid = $this->sanitizeData($request->input('uid'));

        $this->redirectIfIdIsNull($uid, 'Settings');
        
        $user = User::find($uid);
        $user->delete();

        $request->session()->flash($success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

}
