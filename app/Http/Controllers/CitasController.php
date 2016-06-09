<?php

namespace App\Http\Controllers;

use DB;
use App\citas;
use App\pacientes;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;


class CitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');    
    }
    
    public function index(Request $request)
    {	
        return view('cit.index', [
        	'request' => $request
        ]);
    }
    
    public function ver(Request $request)
    {   
		     	  
        if ( null !== $request->input('veord') || null !== $request->input('veo')  ) {
        
	        $selec = $request->input('selec');
	        
	        if ( null !== $request->input('fechde') && null !== $request->input('fechha') ) {     
	        
		        $fechde = $request->input('fechde');      
		        $fechha = $request->input('fechha');
		        
		        $regex = '/^(18|19|20)\d\d[\/\-.](0[1-9]|1[012])[\/\-.](0[1-9]|[12][0-9]|3[01])$/';
		        
		        if ( preg_match($regex, $fechde) ) {  } else {
				 	$request->session()->flash('errmess', 'Fecha/s incorrecta');
					return redirect('/Pacientes/ver');
			 	}
			 		
			 	if ( preg_match($regex, $fechha) ) {  } else {
				 	$request->session()->flash('errmess', 'Fecha/s incorrecta');
					return redirect('/Pacientes/ver');
			 	}
	        }
	        
	        if ($selec === 'hoy') {
			    $selfe1 = date('Y-m-d');
			    $citas = DB::table('citas')
		            ->join('pacientes','citas.idpac','=','pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->where('diacit', '=', $selfe1)
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 

			} elseif ($selec === '1semana' ) {
				$selfe1 = date('Y-m-d');
				$selfe2 = date('Y-m-d', strtotime('+1 Week'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
							 	
			} elseif ($selec === '1mes' ) {
				$selfe1 = date('Y-m-d');
				$selfe2 = date('Y-m-d', strtotime('+1 Month'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
		
			} elseif ($selec === 'menos1mes' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-1 Month'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
					
			} elseif ($selec === '3mes' ) {
				$selfe1 = date('Y-m-d');
				$selfe2 = date('Y-m-d', strtotime('+3 Month'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
					
			} elseif ($selec === 'menos3mes' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-3 Month'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
												 		
			} elseif ($selec === '1ano' ) {
				$selfe1 = date('Y-m-d');
				$selfe2 = date('Y-m-d', strtotime('+1 Year'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
										 		
			} elseif ($selec === 'menos1ano' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-1 Year'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
									 		
			} elseif ($selec === 'menos5ano' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-5 Year'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
						
			} elseif ($selec === 'menos20ano' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-20 Year'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
									 	
			} elseif ($selec === 'todas' ) {
				$citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
					
			} elseif ($selec === 'rango' ) {
				$selfe2 = $fechha;
				$selfe1 = $fechde;
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'DESC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 							 	 			 	
			 											 	 			 	
			} else {
			 	die();
			}      
	        
        }
        
        return view('cit.ver', [
        	'citas' => $citas,
			'request' => $request
		]);
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
