<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Servicios;

use Auth;
use Validator;
use Lang;
use Illuminate\Http\Request;
use App\Interfaces\BaseInterface;

class ServiciosController extends BaseController implements BaseInterface
{
    /**
     *  construct method
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->tax_types = require(base_path().'/config/tax_types.php');        
        $this->main_route = 'Servicios';
        $this->views_folder = 'serv';

        $fields = [
            'name' => true,
            'price' => true,
            'tax' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }

    /**
     *  get the index page
     *      
     *  @return view
     */
    public function index(Request $request)
    {			
		$main_loop = Servicios::whereNull('deleted_at')
                        ->orderBy('name', 'ASC')
                        ->get();		

        $count = Servicios::whereNull('deleted_at')->count();

        $this->form_fields = [
            'name' => true,
            'price' => true,
            'tax' => true,
        ];

        $this->view_data = [
            'main_loop' => $main_loop,
            'request' => $request,
            'count' => $count,
            'form_fields' => $this->form_fields,           
        ];

        return view($this->views_folder.'.index', $this->view_data);          
    }

    /**
     *  get query result, using ajax
     * 
     *  @param Request $request request object.
     * 
     *  @return string JSON
     */
    public function list(Request $request)
    {   
        $busca = $this->sanitizeData($request->input('busca'));

        $data = $this->consultaItems($busca);

        header('Content-type: application/json; charset=utf-8');

        echo json_encode($data);

        exit();
    }  

    /**
     *  create an item
     * 
     *  @param Request $request request object.
     * 
     *  @return view
     */
    public function create(Request $request, $id = false)
    {
        $this->autofocus = 'name';

        $this->view_data = [
            'request' => $request,
            'tax_types' => $this->tax_types,
            'main_route' => $this->main_route,
            'autofocus' => $this->autofocus,
            'form_fields' => $this->form_fields
        ];

        return parent::create($request, $id);  
    }
   
    /**
     *  store an item
     * 
     *  @param Request $request request object.
     * 
     *  @return redirect
     */
    public function store(Request $request)
    {          
        $name = ucfirst(strtolower( $request->input('name') ) );

        $name = $this->sanitizeData($name);
        $price = $this->sanitizeData($request->input('price'));
        $tax = $this->sanitizeData($request->input('tax'));  

        $exists = Servicios::where('name', $name)->exists();
          
        if ($exists) {
           $request->session()->flash($this->error_message_name, Lang::get('aroaden.name_in_use', ['name' => $name]) );
           return redirect($this->main_route.'/create')->withInput();
        }

    	$validator = Validator::make($request->all(), [
            'name' => 'required|unique:servicios|max:111',
            'price' => 'required',
            'tax' => ''
	    ]);
    	        
        if ($validator->fails()) {
	        return redirect($this->main_route.'/create')
	                     ->withErrors($validator)
	                     ->withInput();
	     } else {
	        	
		    Servicios::create([
                'name' => $name,
		        'price' => $price,
		        'tax' => $tax
		    ]);
		      
		    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );	
	        	        	
	        return redirect($this->main_route.'/create');
        }     
    }
 
    /**
     *  get query result, using an ajax request
     * 
     *  @param Request $request request object.
     *  @param int $idser The ID.
     * 
     *  @return json
     */
    public function edit(Request $request, $id, $idcit = false)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);
        $object = Servicios::find($id);
        $this->autofocus = 'name';

        $this->view_data = [
            'request' => $request,
            'id' => $id,
            'object' => $object,            
            'tax_types' => $this->tax_types,
            'main_route' => $this->main_route,
            'autofocus' => $this->autofocus,
            'form_fields' => $this->form_fields
        ];

        return parent::edit($request, $id);  
    }

    /**
     *  get query result, using ajax
     *  @return json
     */
    public function update(Request $request, $idser)
    {
        $this->redirectIfIdIsNull($idser, $this->main_route);

        $idser = $this->sanitizeData($idser);

        $name = ucfirst(strtolower( $request->input('name') ) );

        $name = $this->sanitizeData($name);
        $price = $this->sanitizeData($request->input('price'));
        $tax = $this->sanitizeData($request->input('tax'));  

        $exists = Servicios::where('idser', '!=', $idser)->exists();

        if ($exists) { 
           $request->session()->flash($this->error_message_name, "Nombre: $name - ya en uso, use cualquier otro.");
           return redirect("$this->main_route/$idser/edit");
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:111',
            'price' => 'required',
            'tax' => 'required'
        ]);
            
        if ($validator->fails()) {
            return redirect("$this->main_route/$idser/edit")
                         ->withErrors($validator);
        } else {        
            
            $object = Servicios::find($idser);
                 
            $object->name = $name;
            $object->price = $price;
            $object->tax = $tax;         
            
            $object->save();

            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

            return redirect($this->main_route);
        }   
    }

    /**
     *  get query result, using ajax
     *  @return json
     */
    public function del(Request $request, $idser)
    {
        $this->redirectIfIdIsNull($idser, $this->main_route);

        $idser = $this->sanitizeData($idser);

        $object = Servicios::find($idser);

        return view($this->views_folder.'.del', [
            'request' => $request,
            'object' => $object,
            'idser' => $idser
        ]);
    }
 
     /**
     *  get query result, using ajax
     *  @return json
     */
    public function destroy(Request $request, $idser)
    {          
        $this->redirectIfIdIsNull($idser, $this->main_route);
                
        $idser = $this->sanitizeData($idser);

        Servicios::destroy($idser); 

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

    /**
     *  get query result, using ajax
     *  @return json
     */
    public function consultaItems($busca)
    {
        $count = Servicios::whereNull('deleted_at')->count();

        $data = [];

        if ($count == 0) {
            $data['main_loop'] = false;
            $data['count'] = false;       
            $data['msg'] = ' No hay servicios en la base de datos. ';

            return $data;
        }

        $main_loop = Servicios::select('idser', 'name', 'price', 'tax')
                        ->whereNull('deleted_at')
                        ->where('name','LIKE','%'.$busca.'%')
                        ->orderBy('name','ASC')
                        ->get();

        $count = Servicios::whereNull('deleted_at')
                    ->where('name','LIKE','%'.$busca.'%')
                    ->count();

        return $this->recorrerItems($main_loop, $count);
    }

    /**
     *  get query result, using ajax
     *  @return json
     */
    public function recorrerItems($main_loop, $count)
    {
        $data = [];

        if ($count == 0) {

            $data['main_loop'] = false;
            $data['count'] = false;       
            $data['msg'] = ' No hay resultados. ';

        } else {

            $data['main_loop'] = $main_loop;
            $data['count'] = $count;        
            $data['msg'] = false;
            
        }

        return $data;
    }
}
