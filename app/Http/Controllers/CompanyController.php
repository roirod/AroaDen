<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Settings;
use Illuminate\Http\Request;
use Lang;
use Validator;
use Config;

class CompanyController extends BaseController
{
    /**
     *  __construct method          
     */
    public function __construct(Settings $settings)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = Config::get('aroaden.routes.company');
        $this->views_folder = Config::get('aroaden.routes.company');
        $this->model = $settings;        
    }

    /**
     *  get index page, show the company data
     * 
     *  @return view       
     */
    public function index(Request $request)
    {
        return $this->commonProcess($request, 'index');
    }

    /**
     *  edit the company data
     * 
     *  @return view         
     */
    public function editData(Request $request)
    {
        return $this->commonProcess($request, 'edit');
    }


    /**
     *  create object from key value pair array in this format $obj->obj_key
     * 
     *  @return object $obj get object          
     */
    private function commonProcess($request, $view_name)
    {
        $obj = $this->model::getObject();

        $this->setPageTitle(Lang::get('aroaden.company'));

        $this->view_data['request'] = $request;
        $this->view_data['obj'] = $obj;
        $this->view_data['main_loop'] = Config::get('aroaden.settings_fields');

        if ($view_name == 'edit') {
            $this->view_data['form_route'] = 'saveData';
        } else {
            $this->view_data['form_route'] = 'editData';
        }

        $this->passVarsToViews();

        return view($this->views_folder.".$view_name", $this->view_data);
    }

    /**
     *  save the company data
     *  @param  object  $request   
     *  @return view         
     */
    public function saveData(Request $request)
    {
        $empre = $this->model::select('key', 'value')->get()->toArray();

        foreach ($empre as $arr => $value) {
            foreach ($request->input() as $request_key => $request_value) {
                $request_value = ucfirst( strtolower($request_value) );
                $request_value = $this->sanitizeData($request_value);

                if ($value["key"] == $request_key) {
                    $this->model::where('key', $request_key)->update(['value' => $request_value]);
                }
            }
        }

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

        return redirect("/$this->main_route");
    }

}
