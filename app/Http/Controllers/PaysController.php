<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patients;
use Lang;

class PaysController extends BaseController
{
    public function __construct(Patients $patients)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['pays'];
        $this->other_route = $this->config['routes']['patients'];
        $this->view_data['pays_route'] = $this->config['routes']['pays'];
        $this->views_folder = $this->config['routes']['pays'];
        $this->form_route = 'list';        
        $this->model = $patients;      
    }

    public function index(Request $request)
    {
        $this->setPageTitle(Lang::get('aroaden.payments'));

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
     *  get index page, show the company data
     * 
     *  @return view       
     */
    public function list(Request $request)
    {
        return $this->commonProcess($request, 'list');
    }

    private function commonProcess($request, $view_name)
    {
        $this->view_data['request'] = $request;

        if ($view_name == 'index' || $view_name == 'ajaxIndex') {

            $num_mostrado = 100;
            $main_loop = $this->model::GetTotalPayments($num_mostrado);

        } elseif ($view_name == 'list') {

            $num_mostrado = $this->sanitizeData($request->input('num_mostrado'));

            if ($num_mostrado == 'todos') {

                $main_loop = $this->model::GetTotalPayments($num_mostrado, true);
                $num_mostrado = 'Todos los ';

            } else {

                $main_loop = $this->model::GetTotalPayments($num_mostrado);
                $num_mostrado = $this->formatNumber($num_mostrado);

            }

        }

        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['num_mostrado'] = $num_mostrado;        

        return $this->loadView($this->views_folder.".$view_name", $this->view_data);
    }

}
