<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use Validator;
use Redis;
use Lang;
use DB;

class CompanyController extends BaseController
{
    /**
     *  __construct method          
     */
    public function __construct(Settings $settings)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['company'];
        $this->views_folder = $this->config['routes']['company'];
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
     *  get index page, show the company data
     * 
     *  @return view       
     */
    public function ajaxIndex(Request $request)
    {
        return $this->commonProcess($request, 'ajaxIndex');
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
        $obj = $this->getSettings();

        $this->view_data['request'] = $request;
        $this->view_data['obj'] = $obj;
        $this->view_data['main_loop'] = $this->config['settings_fields'];
        $this->view_name = $view_name;

        if ($view_name == 'edit') {

            $this->view_data['form_route'] = 'saveData';
            $this->setPageTitle(Lang::get('aroaden.company_edit_data'));

        } else {

            $this->view_data['form_route'] = 'editData';
            $this->setPageTitle(Lang::get('aroaden.company_data'));

        }

        return $this->loadView();
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
                $request_value = $this->sanitizeData($request_value);

                if ($value["key"] == $request_key)
                    $this->model::where('key', $request_key)->update(['value' => $request_value]);
            }
        }

        $settings = Settings::getArray();

        if (env('REDIS_SERVER_IS_ON')) 
            Redis::set('settings', json_encode($settings));               

        return $this->ajaxIndex($request);
    }

}
