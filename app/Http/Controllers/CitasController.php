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
        return view('cit.index', ['request' => $request]);
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
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 

			} elseif ($selec === '1semana' ) {
				$selfe1 = date('Y-m-d');
				$selfe2 = date('Y-m-d', strtotime('+1 Week'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
							 	
			} elseif ($selec === '1mes' ) {
				$selfe1 = date('Y-m-d');
				$selfe2 = date('Y-m-d', strtotime('+1 Month'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
		
			} elseif ($selec === 'menos1mes' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-1 Month'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
					
			} elseif ($selec === '3mes' ) {
				$selfe1 = date('Y-m-d');
				$selfe2 = date('Y-m-d', strtotime('+3 Month'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
					
			} elseif ($selec === 'menos3mes' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-3 Month'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
												 		
			} elseif ($selec === '1ano' ) {
				$selfe1 = date('Y-m-d');
				$selfe2 = date('Y-m-d', strtotime('+1 Year'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
										 		
			} elseif ($selec === 'menos1ano' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-1 Year'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
									 		
			} elseif ($selec === 'menos5ano' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-5 Year'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
						
			} elseif ($selec === 'menos20ano' ) {
				$selfe2 = date('Y-m-d');
				$selfe1 = date('Y-m-d', strtotime('-20 Year'));
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
									 	
			} elseif ($selec === 'todas' ) {
				$citas = DB::select('SELECT * FROM citas ORDER BY diacit ASC, horacit ASC');
				$citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 
					
			} elseif ($selec === 'rango' ) {
				$selfe2 = $fechha;
				$selfe1 = $fechde;
			    $citas = DB::table('citas')
		            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
		            ->select('citas.*','pacientes.apepac','pacientes.nompac')
		            ->whereBetween('diacit', [$selfe1, $selfe2])
		            ->orderBy('diacit' , 'ASC')
		            ->orderBy('horacit' , 'ASC')
		            ->get(); 							 	 			 	
			 											 	 			 	
			} else {
			 	die();
			}      
	        
        }
        
        return view('cit.ver', ['citas' => $citas,
								'request' => $request]);
    }

    public function create(Request $request,$idpac)
    {  	  
    	if ( null == $idpac ) {
    	  	return redirect('Pacientes');
    	}
    	
    	$pacientes = pacientes::find($idpac);

    	$apepac = $pacientes->apepac;
    	$nompac = $pacientes->nompac;

        return view('cit.create', ['request' => $request,
     							   'idpac' => $idpac,
     							   'apepac' => $apepac,
     							   'nompac' => $nompac]);
    }

    public function store(Request $request)
    {
    	
    	$idpac = $request->input('idpac');

    	if ( null == $idpac ) {
    	  	return redirect('Pacientes');
    	}    	
    	
    	$horacit = trim ( $request->input('horacit') );
    	$diacit = trim ( $request->input('diacit') );
    	$notas = trim ( $request->input('notas') );
	      	  
    	if ( isset ($diacit) ) {
   	  		$regex = '/^(18|19|20)\d\d[\/\-.](0[1-9]|1[012])[\/\-.](0[1-9]|[12][0-9]|3[01])$/';  	  
	    	if ( preg_match($regex, $diacit) ) {  } else {
			  	$request->session()->flash('errmess', 'Fecha incorrecta');	
				return redirect("/Citas/$idpac/create");
			}
		}
			
    	if ( isset ($horacit) ) {
    	  	$regex = '/^([01][0-9]|2[0-3])[\:.]([012345][0-9])$/';
	    	if ( preg_match($regex, $horacit) ) {  } else {
			  	$request->session()->flash('errmess', 'Hora incorrecta');
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
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
