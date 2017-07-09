<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Settings;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use Lang;

class EmpresaController extends BaseController
{
    /**
     *  __construct method          
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Empresa';
        $this->views_folder = 'emp';
    }

    /**
     *  get index page, show the company data
     * 
     *  @return view       
     */
    public function index(Request $request)
    { 
        $obj = $this->getObject();

		return view($this->views_folder.'.index', [
		   'request' => $request,
		   'empre' => $obj
		]);
    }

    /**
     *  edit the company data
     * 
     *  @return view         
     */
    public function editData(Request $request)
    {
        $obj = $this->getObject();

        return view($this->views_folder.'.edit', [
            'request' => $request,
            'empre' => $obj
        ]);
    }

    /**
     *  create object from key value pair array in this format $obj->obj_key
     * 
     *  @return object $obj get object          
     */
    private function getObject()
    {
        $empre = Settings::select('key', 'value')->get()->toArray();

        $obj = new class {};

        foreach ($empre as $arr => $value) {
            $obj_key = $value["key"];
            $obj_val = $value["value"];

            $obj->$obj_key = $obj_val;
        }

        return $obj;
    }

    /**
     *  save the company data
     *  @param  object  $request   
     *  @return view         
     */
    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'company_name' => 'required|max:111',
            'company_address' => 'max:111',
            'company_city' => 'max:111',
            'company_nif' => 'max:22',
            'company_tel1' => 'max:11',
            'company_tel2' => 'max:11',
            'company_tel3' => 'max:11',                
            'company_notes' => '',
            'invoice_text' => '',
            'budget_text' => ''
        ]);
            
        if ($validator->fails()) {
            return redirect("$this->main_route/editData")
                     ->withErrors($validator)
                     ->withInput();
        } else {        

            $empre = Settings::select('key', 'value')->get()->toArray();

            foreach ($empre as $arr => $value) {
                foreach ($request->input() as $request_key => $request_value) {
                    $request_value = ucfirst( strtolower($request_value) );
                    $request_value = $this->sanitizeData($request_value);

                    if ($value["key"] == $request_key) {
                        Settings::where('key', $request_key)->update(['value' => $request_value]);
                    }
                }
            }

            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

            return redirect("/$this->main_route");
        }
    }

}
