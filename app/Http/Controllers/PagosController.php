<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;
use App\Http\Requests;

class PagosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {  

        $pagos = DB::raw('select `pacientes`.`apepac`, `pacientes`.`nompac`, SUM(canti*precio) as total, SUM(pagado) as pagado, 
                            SUM(canti*precio)-sum(pagado) as resto 
                            from `tratampacien` 
                            inner join `pacientes` on `tratampacien`.`idpac` = `pacientes`.`idpac` 
                            group by `tratampacien`.`idpac` 
                            order by `resto` desc LIMIT 1000');

        return view('pago.index', ['request' => $request,
       							  'pagos' => $pagos]);   
    }
}
