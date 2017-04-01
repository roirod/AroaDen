<?php

namespace App\Http\Controllers;

use DB;
use App\citas;
use App\pacientes;

use Validator;
use Illuminate\Http\Request;


class CitasController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');    
    }
    
    public function index(Request $request)
    {	
	    $today = date('Y-m-d');

	    $citas = DB::table('citas')
            ->join('pacientes','citas.idpac','=','pacientes.idpac')
            ->select('citas.*','pacientes.apepac','pacientes.nompac')
            ->where('citas.diacit', $today)
            ->whereNull('pacientes.deleted_at')
            ->orderBy('citas.diacit' , 'DESC')
            ->orderBy('citas.horacit' , 'ASC')
            ->get();

        $count = DB::table('citas')
            ->join('pacientes','citas.idpac','=','pacientes.idpac')
            ->select('citas.*','pacientes.apepac','pacientes.nompac')
            ->where('citas.diacit', $today)
            ->whereNull('pacientes.deleted_at')
            ->count();

        return view('cit.index', [
        	'request' => $request,
        	'citas' => $citas,
        	'count' => $count
        ]);
    }
    
    public function list(Request $request)
    {
        $selec = $request->input('selec');
        $selec = htmlentities (trim($selec),ENT_QUOTES,"UTF-8");

        $data = [];

        $count = DB::table('citas')
            ->join('pacientes','citas.idpac','=','pacientes.idpac')
            ->select('citas.*','pacientes.apepac','pacientes.nompac')
            ->whereNull('pacientes.deleted_at')
            ->count();

        if ($count === 0) {

            $data['citas'] = false;
            $data['citas_de'] = false;       
            $data['msg'] = ' No hay citas en la base de datos. ';

        } else {

	        $fechde = $request->input('fechde');
	        $fechha = $request->input('fechha');

        	if ( $selec === 'rango' ) {

		        $fechde = htmlentities (trim($fechde),ENT_QUOTES,"UTF-8");
		        $fechha = htmlentities (trim($fechha),ENT_QUOTES,"UTF-8");

		        $regex = '/^(18|19|20)\d\d[\/\-.](0[1-9]|1[012])[\/\-.](0[1-9]|[12][0-9]|3[01])$/';
		        
		        if ( !preg_match($regex, $fechde) || !preg_match($regex, $fechha) ) {

		            $data['citas'] = false;
		            $data['citas_de'] = false;       
		            $data['msg'] = ' Fecha/s incorrecta. ';

			 	} elseif ( $fechde > $fechha ) {

		            $data['citas'] = false;
		            $data['citas_de'] = false;       
		            $data['msg'] = "La fecha ". $this->convertYmdToDmY($fechha) ." es anterior a ". $this->convertYmdToDmY($fechde) .".";

		        } else {

		       		$data = $this->getItemsByDate('rango', $fechde, $fechha);

		       	}

		    } else {

	        	$data = $this->getItemsByDate($selec, $fechde, $fechha);
	        	   	
			}     
	    }

        header('Content-type: application/json; charset=utf-8');

        echo json_encode($data);

        exit();
    }

    private function getItemsByDate($selec, $fechde, $fechha)
    {
    	if ( $selec === 'todas' ) {

			$citas_de = "todas";

			$citas = DB::table('citas')
	            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
	            ->select('citas.*','pacientes.apepac','pacientes.nompac')
	            ->whereNull('pacientes.deleted_at')
	            ->orderBy('diacit' , 'DESC')
	            ->orderBy('horacit' , 'ASC')
	            ->get();

    	} elseif ( $selec === 'hoy' ) {

    		$selfe1 = date('Y-m-d');
			$citas_de = "hoy";

		    $citas = DB::table('citas')
	            ->join('pacientes','citas.idpac','=','pacientes.idpac')
	            ->select('citas.*','pacientes.apepac','pacientes.nompac')
	            ->where('diacit', $selfe1)
	            ->whereNull('pacientes.deleted_at')
	            ->orderBy('diacit' , 'DESC')
	            ->orderBy('horacit' , 'ASC')
	            ->get(); 

		} elseif ($selec === '1semana' ) {

			$selfe1 = date('Y-m-d');
			$selfe2 = date('Y-m-d', strtotime('+1 Week'));
			$citas_de = "+1 semana";

			$citas = $this->getQueryResults($selfe1, $selfe2);
					 	
		} elseif ($selec === '1mes' ) {

			$selfe1 = date('Y-m-d');
			$selfe2 = date('Y-m-d', strtotime('+1 Month'));
			$citas_de = "+1 mes";

			$citas = $this->getQueryResults($selfe1, $selfe2);

		} elseif ($selec === '3mes' ) {

			$selfe1 = date('Y-m-d');
			$selfe2 = date('Y-m-d', strtotime('+3 Month'));
			$citas_de = "+3 meses";

			$citas = $this->getQueryResults($selfe1, $selfe2);

		} elseif ($selec === '1ano' ) {

			$selfe1 = date('Y-m-d');
			$selfe2 = date('Y-m-d', strtotime('+1 Year'));
			$citas_de = "+1 año";

			$citas = $this->getQueryResults($selfe1, $selfe2);

		} elseif ($selec === 'menos1mes' ) {

			$selfe2 = date('Y-m-d');
			$selfe1 = date('Y-m-d', strtotime('-1 Month'));
			$citas_de = "-1 mes";

			$citas = $this->getQueryResults($selfe1, $selfe2);

		} elseif ($selec === 'menos3mes' ) {

			$selfe2 = date('Y-m-d');
			$selfe1 = date('Y-m-d', strtotime('-3 Month'));
			$citas_de = "-3 meses";

			$citas = $this->getQueryResults($selfe1, $selfe2);

		} elseif ($selec === 'menos1ano' ) {

			$selfe2 = date('Y-m-d');
			$selfe1 = date('Y-m-d', strtotime('-1 Year'));
			$citas_de = "-1 año";

			$citas = $this->getQueryResults($selfe1, $selfe2);

		} elseif ($selec === 'menos5ano' ) {

			$selfe2 = date('Y-m-d');
			$selfe1 = date('Y-m-d', strtotime('-5 Year'));
			$citas_de = "-5 años";

			$citas = $this->getQueryResults($selfe1, $selfe2);

		} elseif ($selec === 'menos20ano' ) {

			$selfe2 = date('Y-m-d');
			$selfe1 = date('Y-m-d', strtotime('-20 Year'));
			$citas_de = "-20 años";

			$citas = $this->getQueryResults($selfe1, $selfe2);

		} elseif ($selec === 'rango' ) {

			$selfe2 = $fechha;
			$selfe1 = $fechde;
			$citas_de = "Citas entre ".$this->convertYmdToDmY($fechde)." y ".$this->convertYmdToDmY($fechha);

			$citas = $this->getQueryResults($selfe1, $selfe2);		 											 	 			 	
		}

        $data['citas'] = $citas;
        $data['citas_de'] = $citas_de;       
        $data['msg'] = false;

    	return $data;
    }

    private function getQueryResults($selfe1, $selfe2)
    {
	    $citas = DB::table('citas')
            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
            ->select('citas.*','pacientes.apepac','pacientes.nompac')
            ->whereBetween('diacit', [$selfe1, $selfe2])
            ->whereNull('pacientes.deleted_at')
            ->orderBy('diacit' , 'DESC')
            ->orderBy('horacit' , 'ASC')
            ->get(); 

    	return $citas;
    }

    public function create(Request $request,$idpac)
    {  	  
    	if ( null == $idpac ) {
    	  	return redirect('Pacientes');
    	}
    	
    	$pacientes = pacientes::find($idpac);

    	$apepac = $pacientes->apepac;
    	$nompac = $pacientes->nompac;

        return view('cit.create', [
        	'request' => $request,
     		'idpac' => $idpac,
     		'apepac' => $apepac,
     		'nompac' => $nompac
     	]);
    }

    public function store(Request $request)
    {
    	$idpac = $request->input('idpac');

    	if ( null == $idpac ) {
    	  	return redirect('Pacientes');
    	}    	
    	
    	$horacit = trim ( $request->input('horacit') );
    	$diacit = trim ( $request->input('diacit') );
    	$notas = htmlentities (trim($request->input('notas')),ENT_QUOTES,"UTF-8");
	      	  
    	if ( isset ($diacit) ) {
   	  		$regex = '/^(18|19|20)\d\d[\/\-.](0[1-9]|1[012])[\/\-.](0[1-9]|[12][0-9]|3[01])$/';  	  
	    	if ( preg_match($regex, $diacit) ) {  } else {
			  	$request->session()->flash('errmess', 'Fecha incorrecta');	
				return redirect("/Citas/$idpac/create");
			}
		}
	    	  
        $validator = Validator::make($request->all(), [
	        'horacit' => 'required',
	        'diacit' => 'required|date',
	        'notas' => ''
	    ]);
            
        if ($validator->fails()) {
	        return redirect("/Citas/$idpac/create")
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {
	        	
		    citas::create([
		        'idpac' => $idpac,
		        'horacit' => $horacit,
		        'diacit' => $diacit,
		        'notas' => $notas
		    ]);
		      
		    $request->session()->flash('sucmess', 'Hecho!!!');	
	        	        	
	        return redirect("/Citas/$idpac/create");
        }     
    }

    public function show($id)
    { }

    public function edit(Request $request,$idpac,$idcit)
    {
    	if ( null === $idpac ) {
    		return redirect('Pacientes');
    	}
    	
    	if ( null === $idcit ) {
    		return redirect('Pacientes');
    	}

    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");
    	$idcit = htmlentities (trim($idcit),ENT_QUOTES,"UTF-8");

    	$cita = citas::find($idcit);

    	return view('cit.edit', [
    		'request' => $request,
    		'cita' => $cita,
    		'idcit' => $idcit,
    		'idpac' => $idpac
    	]);
    }

    public function update(Request $request,$idcit)
    {
    	if ( null === $idcit ) {
    		return redirect('Pacientes');
    	}

    	$idcit = htmlentities(trim($idcit),ENT_QUOTES,"UTF-8");
    	$idpac = htmlentities(trim($request->input('idpac')),ENT_QUOTES,"UTF-8");

    	if ( null === $idpac ) {
    		return redirect('Pacientes');
    	}
       	  
        $validator = Validator::make($request->all(), [
	        'horacit' => 'required',
	        'diacit' => 'required|date',
	        'notas' => ''
	    ]);
            
        if ($validator->fails()) {
	        return redirect("/Citas/$idpac/$idcit/edit")
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {

	    	$horacit = trim($request->input('horacit'));
	    	$diacit = trim($request->input('diacit'));
		      	  
	    	if ( isset ($diacit) ) {
	   	  		$regex = '/^(18|19|20)\d\d[\/\-.](0[1-9]|1[012])[\/\-.](0[1-9]|[12][0-9]|3[01])$/';  	  
		    	if ( preg_match($regex, $diacit) ) {  } else {
				  	$request->session()->flash('errmess', 'Fecha incorrecta');	
					return redirect("/Citas/$idpac/$idcit/edit");
				}
			}
					
			$citas = citas::find($idcit);

	    	$notas = ucfirst(strtolower($request->input('notas')));
	    	
			$citas->horacit = htmlentities (trim($horacit),ENT_QUOTES,"UTF-8");
			$citas->diacit = htmlentities (trim($diacit),ENT_QUOTES,"UTF-8");
			$citas->notas = htmlentities (trim($notas),ENT_QUOTES,"UTF-8");
			
			$citas->save();

			$request->session()->flash('sucmess', 'Hecho!!!');

			return redirect("Pacientes/$idpac");
		}   
    }

    public function del(Request $request,$idpac,$idcit)
    {    	  
    	$idcit = htmlentities (trim($idcit),ENT_QUOTES,"UTF-8");
    	$idpac = htmlentities (trim($idpac),ENT_QUOTES,"UTF-8");

        if ( null === $idcit ) {
            return redirect('Pacientes');
        }

        if ( null === $idpac ) {
            return redirect('Pacientes');
        }

        $cita = citas::find($idcit);

    	return view('cit.del', [
            'request' => $request,
            'cita' => $cita,
            'idcit' => $idcit,
    		'idpac' => $idpac
        ]);
    }
 
    public function destroy(Request $request,$idcit)
    {             	
    	$idcit = htmlentities (trim($idcit),ENT_QUOTES,"UTF-8");
    	$idpac = htmlentities(trim($request->input('idpac')),ENT_QUOTES,"UTF-8");

        if ( null === $idcit ) {
            return redirect('Pacientes');
        }

        if ( null === $idpac ) {
            return redirect('Pacientes');
        } 
        
        $idcit = htmlentities (trim($idcit),ENT_QUOTES,"UTF-8");
        
        $cita = citas::find($idcit);
      
        $cita->delete();

        $request->session()->flash('sucmess', 'Hecho!!!');
        
        return redirect("Pacientes/$idpac");
    }

}
