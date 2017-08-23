<?php

namespace App\Http\Controllers;

use DB;

use Illuminate\Http\Request;
use App\Http\Requests;

class PagosController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $num_mostrado = 5000;

        $pagos = DB::select("
            SELECT pac.surname, pac.name, pac.idpac, 
            SUM(tra.units*tra.price) as total, 
            SUM(tra.paid) as paid, 
            SUM(tra.units*tra.price)-SUM(tra.paid) as rest 
            FROM tratampacien tra
            INNER JOIN pacientes pac
            ON tra.idpac=pac.idpac 
            WHERE pac.deleted_at IS NULL 
            GROUP BY tra.idpac 
            HAVING tra.idpac=tra.idpac  
            ORDER BY rest DESC
            LIMIT $num_mostrado
        ");

        return view('pago.index', [
            'request' => $request,
       		'pagos' => $pagos,
            'num_mostrado' => $num_mostrado
        ]);   
    }
}
