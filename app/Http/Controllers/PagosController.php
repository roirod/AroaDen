<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Http\Request;
use App\Http\Requests;

class PagosController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Pagos';
        $this->form_route = 'list';        
        $this->views_folder = 'pago';        
    }

    public function index(Request $request)
    {
        $num_mostrado = 200;
        $main_loop = Pacientes::GetTotalPayments($num_mostrado);

        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['num_mostrado'] = $num_mostrado;

        return parent::index($request);
    }
}
