<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Lang;
use DB;

class UsersController extends BaseController
{
    public function __construct(User $users)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['users'];
        $this->other_route = $this->config['routes']['settings'];      
        $this->views_folder = $this->config['routes']['users'];
        $this->view_data['user_edit'] = 'userEdit';
        $this->view_data['user_delete'] = 'userDeleteViev';
        $this->model = $users;
    }
      
    public function index(Request $request)
    {
        $main_loop = $this->model::AllOrderByUsername();

        $this->setPageTitle(Lang::get('aroaden.users'));

        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;

        return parent::index($request);
    }    

    public function store(Request $request)
    {    	  	  
    	$password = trim( $request->input('password') );
    	$username = $this->sanitizeData($request->input('username'));
        $type = $this->sanitizeData($request->input('type'));

        if ( $username == 'admin' ) {
            $request->session()->flash($this->error_message_name, 'Nombre de usuario no permitido, use cualquier otro.');   
            return redirect($this->main_route);
        }   

        $exists = $this->model::CheckIfExists($username);

        if ($exists) {
           $request->session()->flash($this->error_message_name, "Nombre de usuario: $username - en uso, use cualquier otro.");
           return redirect($this->main_route);
        }

        $validator = Validator::make($request->all(),[
            'username' => 'required|unique:users|max:44',
            'password' => 'required|max:44',
            'type' => 'required|max:44'
        ]);
            
        if ($validator->fails()) {
	         return redirect($this->main_route)
	                     ->withErrors($validator)
	                     ->withInput();
	    } else { 		
        	     	        	
	        $this->model::create([
	          'username' => $username,
	          'password' => bcrypt($password),
              'type' => $type
            ]);
	      
            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );	
        	        	
        	return redirect($this->main_route);
        }      
    }
    
    public function userEdit(Request $request)
    {
        $main_loop = $this->model::AllOrderByUsername();

        $this->form_route = 'userUpdate';
        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;

        $this->setPageTitle(Lang::get('aroaden.users'));

        $this->view_name = 'userEdit';

        return $this->loadView();
    }

    public function userDeleteViev(Request $request)
    {
        $main_loop = $this->model::AllOrderByUsername();
        
        $this->form_route = 'userDelete';
        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;

        $this->setPageTitle(Lang::get('aroaden.users'));

        $this->view_name = 'userDeleteViev';

        return $this->loadView();
    }

    public function userUpdate(Request $request)
    {
        $uid = $this->sanitizeData($request->input('uid'));
        $password = trim( $request->input('password') );

        $this->redirectIfIdIsNull($uid, $this->main_route);

        $validator = Validator::make($request->all(),[
            'uid' => 'required',
            'password' => 'required|max:44'
        ]);
            
        if ($validator->fails()) {
             return redirect($this->main_route.'/userEdit')
                         ->withErrors($validator)
                         ->withInput();
        } else {
              
            $user = $this->model::find($uid);
            $user->password = bcrypt($password);
            $user->save();
              
            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
                        
            return redirect($this->main_route);
        }  
    }

    public function userDelete(Request $request)
    {
        $uid = $this->sanitizeData($request->input('uid'));
        $this->redirectIfIdIsNull($uid, $this->main_route);
        
        $user = $this->model::find($uid);
        $user->delete();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

}
