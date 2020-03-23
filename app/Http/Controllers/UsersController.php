<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Lang;

class UsersController extends BaseController
{
    public function __construct(User $users)
    {
        parent::__construct();

        $this->main_route = $this->config['routes']['users'];
        $this->other_route = $this->config['routes']['settings'];      
        $this->views_folder = $this->config['routes']['users'];
        $this->view_data['deleteViewRoute'] = 'deleteView';
        $this->model = $users;

        $fields = [
            'user' => true,
            'password' => true,
            'full_name' => true,
            'scopes' => true,
            'save' => true
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }
      
    public function index(Request $request)
    {
        $this->setPageTitle(Lang::get('aroaden.users'));

        $this->view_data['form_fields'] = $this->form_fields;
        $this->view_data['main_loop'] = $this->model::AllOrderByUsername();

        return parent::index($request);
    }    

    public function store(Request $request)
    {    	  	  
        $password = trim($request->input('password'));
        $username = $this->sanitizeData($request->input('username')); 
        $type = $this->sanitizeData($request->input('type'));
        $full_name = $this->sanitizeData($request->input('full_name'));

        if ($username == 'admin') {
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
            'type' => 'required|max:44',
            'full_name' => 'required|max:77'
        ]);
            
        if ($validator->fails()) {
	         return redirect($this->main_route)
	                     ->withErrors($validator)
	                     ->withInput();
	    } else { 		
        	     	        	
	        $this->model::create([
	          'username' => $username,
	          'password' => bcrypt($password),
              'type' => $type,
              'full_name' => $full_name
            ]);
	      
            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );	
        	        	
        	return redirect($this->main_route);
        }      
    }
    
    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);

        $this->setPageTitle(Lang::get('aroaden.users'));

        $this->view_name = 'edit';
        $this->autofocus = 'name';
        $this->view_data['id'] = $id;
        $this->view_data['object'] = $this->model::find($id);
        $this->view_data['form_fields'] = $this->form_fields;
        $this->view_data['is_create_view'] = false;
        $this->view_data['request'] = $request;

        return $this->loadView();
    }

    public function deleteView(Request $request)
    {
        $this->view_name = 'deleteView';
        $this->form_route = 'userDelete';
        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $this->model::AllOrderByUsername();

        $this->setPageTitle(Lang::get('aroaden.users'));

        return $this->loadView();
    }

    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);
        $id = $this->sanitizeData($id);
        $route = "$this->main_route/$id/edit";
        $user = $this->model::find($id);

        $password = trim($request->input('password'));
        $username = $this->sanitizeData($request->input('username'));        
        $full_name = $this->sanitizeData($request->input('full_name'));        
        $type = $this->sanitizeData($request->input('type'));

        if ($user->username == 'admin') {

            if ($password == '') {
                $request->session()->flash($this->error_message_name, 'La contraseña es obligatoria.');   
                return redirect($route);
            }

            $user->password = bcrypt($password);

        } else {

            if ($username == '') {
                $request->session()->flash($this->error_message_name, 'El Usuario es obligatorio.');   
                return redirect($route);
            }

            $CheckIfExistsOnUpdate = $this->model::CheckIfExistsOnUpdate($id, $username);

            if ($CheckIfExistsOnUpdate) {
                $request->session()->flash($this->error_message_name,  "Nombre de usuario: $username - en uso, use cualquier otro.");   
                return redirect($route);
            }

            if ($username != $user->username)
                $user->username = $username;

            if ($password != '')
                $user->password = bcrypt($password);            

            if ($full_name == '') {
                $request->session()->flash($this->error_message_name, 'El Nombre completo es obligatorio.');   
                return redirect($route);
            }

            if ($full_name != $user->full_name)
                $user->full_name = $full_name;

            if ($type == '') {
                $request->session()->flash($this->error_message_name, 'El campo Permisos es obligatorio.');   
                return redirect($route);
            }

            if ($type != $user->type)
                $user->type = $type;
        }

        $user->save();
            
        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message'));
        return redirect($this->main_route);
    }

    public function userDelete(Request $request)
    {
        $uid = $this->sanitizeData($request->input('uid'));
        $this->redirectIfIdIsNull($uid, $this->main_route);
        
        $user = $this->model::find($uid);

        if ($user->username == 'admin') {
            $request->session()->flash($this->error_message_name, 'No se puede eliminar el usuario admin.');   
            return redirect($this->main_route.'/deleteView');
        }

        $user->delete();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        return redirect($this->main_route);
    }

}
