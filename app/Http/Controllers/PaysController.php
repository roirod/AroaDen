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

            $entries_number = 100;
            $main_loop = $this->model::GetTotalPayments($entries_number);

        } elseif ($view_name == 'list') {

            $entries_number = $this->sanitizeData($request->input('entries_number'));

            if ($entries_number == 'todos') {

                $main_loop = $this->model::GetTotalPayments($entries_number, true);
                $entries_number = 'Todos los ';

            } else {

                $main_loop = $this->model::GetTotalPayments($entries_number);
                $entries_number = $this->formatNumber($entries_number);

            }

        }

        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['entries_number'] = $entries_number;

        $this->view_name = $view_name;   

        return $this->loadView();
    }

}
