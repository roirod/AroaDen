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

        $pagos = DB::select('SELECT pac.apepac, pac.nompac, pac.idpac, 
                            SUM(tra.canti*tra.precio) as total, 
                            SUM(tra.pagado) as pagado, 
                            SUM(tra.canti*tra.precio)-SUM(tra.pagado) as resto 
                            FROM tratampacien tra
                            INNER JOIN pacientes pac
                            ON tra.idpac=pac.idpac 
                            GROUP BY tra.idpac 
                            HAVING tra.idpac=tra.idpac  
                            ORDER BY resto DESC
                            LIMIT 5000');

        return view('pago.index', [
            'request' => $request,
       		'pagos' => $pagos
        ]);   
    }
}
