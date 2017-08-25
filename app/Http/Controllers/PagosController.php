<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Http\Request;
use App\Http\Requests;
use Lang;

class PagosController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Pagos';
        $this->other_route = 'Pacientes';
        $this->form_route = 'list';        
        $this->views_folder = 'pago';        
    }

    public function index(Request $request)
    {
        $num_mostrado = 100;
        $main_loop = Pacientes::GetTotalPayments($num_mostrado);

        $this->page_title = Lang::get('aroaden.payments').' - '.$this->page_title;

        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['num_mostrado'] = $num_mostrado;
        $this->view_data['other_route'] = $this->other_route;
        $this->view_data['form_route'] = $this->form_route;
        
        return parent::index($request);
    }

    public function list(Request $request)
    {
        $num_mostrado = $this->sanitizeData($request->input('num_mostrado'));

        if ($num_mostrado == 'todos') {

            $main_loop = Pacientes::GetTotalPayments($num_mostrado, true);
            $num_mostrado = 'Todos los ';

        } else {

            $main_loop = Pacientes::GetTotalPayments($num_mostrado);
            $num_mostrado = $this->formatNumber($num_mostrado);

        }

        $this->page_title = Lang::get('aroaden.payments').' - '.$this->page_title;
        $this->passVarsToViews();   

        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['num_mostrado'] = $num_mostrado;
        $this->view_data['other_route'] = $this->other_route;
        $this->view_data['form_route'] = $this->form_route;

        return view($this->views_folder.'.list', $this->view_data);
    }

}
