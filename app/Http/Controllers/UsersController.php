<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Exception;
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
      'username' => true,
      'password' => true,
      'full_name' => true,
      'type' => true,
      'save' => true
    ];

    $this->form_fields = array_replace($this->form_fields, $fields);
  }
    
  public function index(Request $request)
  {
    $this->setPageTitle(Lang::get('aroaden.users'));

    $this->view_data['main_loop'] = $this->model::AllOrderByUsername();

    return parent::index($request);
  }    

  public function store(Request $request)
  {
    $data = [];
    $data['error'] = false;
    $data['redirectTo'] = "/$this->main_route";

    $this->request = $request;
    $this->validateInputs();

    try {

      extract($this->sanitizeRequest($request->all()));

      if ($username == 'admin')
        throw new Exception(Lang::get('aroaden.name_in_use'));

      $exists = $this->model::CheckIfExists($username);

      if ($exists)
        throw new Exception(Lang::get('aroaden.name_in_use'));
                            
      $password = trim($request->input('password'));

      $this->model::create([
        'username' => $username,
        'password' => bcrypt($password),
        'type' => $type,
        'full_name' => $full_name
      ]);
    
    } catch (\Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
  }
  
  public function edit(Request $request, $id)
  {
    $id = $this->sanitizeData($id);        
    $this->redirectIfIdIsNull($id, $this->main_route);

    $this->setPageTitle(Lang::get('aroaden.users'));

    $this->view_name = 'edit';
    $this->autofocus = 'name';
    $this->view_data['id'] = $id;
    $this->view_data['object'] = $this->model::find($id);
    $this->view_data['is_create_view'] = false;
    $this->view_data['request'] = $request;

    return $this->loadView();
  }

  public function update(Request $request, $id)
  {
    $data = [];
    $data['error'] = false;
    $data['redirectTo'] = "/$this->main_route";

    $id = $this->sanitizeData($id);
    $user = $this->model::find($id);

    if ($user->username == 'admin') {
      $this->form_fields['full_name'] = false;
      $this->form_fields['type'] = false;
    }

    $this->request = $request;
    $this->validateInputs();

    try {

      extract($this->sanitizeRequest($request->all()));

      if ($user->username != 'admin') {
        $exists = $this->model::CheckIfExistsOnUpdate($id, $username);

        if ($exists)
          throw new Exception(Lang::get('aroaden.name_in_use'));

        if ($username != $user->username)
          $user->username = $username;

        if ($full_name != $user->full_name)
          $user->full_name = $full_name;

        if ($type != $user->type)
          $user->type = $type;
      }

      $password = trim($request->input('password'));

      $user->password = bcrypt($password);
      $user->save();

    } catch (\Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
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

  public function userDelete(Request $request)
  {
    $data = [];
    $data['error'] = false;
    $data['redirectTo'] = "/$this->main_route";

    try {

      $uid = $this->sanitizeData($request->input('uid'));      
      $user = $this->model::find($uid);

      if ($user->username == 'admin')
        throw new Exception(Lang::get('aroaden.del_user_error'));

      $user->delete();

    } catch (\Exception $e) {

      $data['error'] = true;
      $data['msg'] = $e->getMessage();

    }

    $this->echoJsonOuptut($data);
  }

}
