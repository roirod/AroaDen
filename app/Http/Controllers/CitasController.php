<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Citas;
use App\Models\Pacientes;
use Validator;
use Illuminate\Http\Request;
use Lang;
use App\Interfaces\BaseInterface;

class CitasController extends BaseController implements BaseInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Citas';
        $this->views_folder = 'cit';
        $this->other_route = 'Pacientes';

        $fields = [
            'hour' => true,
            'day' => true,
            'notes' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }
    
    public function index(Request $request)
    {	
	    $today = date('Y-m-d');

	    $main_loop = DB::table('citas')
            ->join('pacientes','citas.idpac','=','pacientes.idpac')
            ->select('citas.*','pacientes.surname','pacientes.name')
            ->where('citas.day', $today)
            ->whereNull('pacientes.deleted_at')
            ->orderBy('citas.day' , 'DESC')
            ->orderBy('citas.hour' , 'ASC')
            ->get();

        $count = DB::table('citas')
            ->join('pacientes','citas.idpac','=','pacientes.idpac')
            ->select('citas.*','pacientes.surname','pacientes.name')
            ->where('citas.day', $today)
            ->whereNull('pacientes.deleted_at')
            ->count();

        return view($this->views_folder.'.index', [
        	'request' => $request,
        	'main_loop' => $main_loop,
        	'count' => $count
        ]);
    }
    
    public function list(Request $request)
    {
        $selec = $this->sanitizeData($request->input('selec'));

        $data = [];

        $count = DB::table('citas')
            ->join('pacientes','citas.idpac','=','pacientes.idpac')
            ->select('citas.*','pacientes.surname','pacientes.name')
            ->whereNull('pacientes.deleted_at')
            ->count();

        if ($count == 0) {

            $data['main_loop'] = false;
            $data['appointments_of'] = false;       
            $data['msg'] = ' No hay citas en la base de datos. ';

        } else {

            $fechde = $this->sanitizeData($request->input('fechde'));
            $fechha = $this->sanitizeData($request->input('fechha'));

        	if ( $selec == 'rango' ) {

		        if ( $this->validateDate($fechde) == false || $this->validateDate($fechha) == false ) {

		            $data['main_loop'] = false;
		            $data['appointments_of'] = false;       
		            $data['msg'] = ' Fecha/s incorrecta, introduzca fechas válidas. ejemplo: 14/04/2017. ';

			 	} elseif ( $fechde > $fechha ) {

		            $data['main_loop'] = false;
		            $data['appointments_of'] = false;       
		            $data['msg'] = "La fecha ".$this->convertYmdToDmY($fechha)." es anterior a ".$this->convertYmdToDmY($fechde) .".";

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

    public function create(Request $request, $id = false)
    {  	  
        $this->redirectIfIdIsNull($id, $this->other_route);
    	
    	$object = Pacientes::find($id);

        $this->view_data = [
            'request' => $request,
            'idpac' => $id,
            'form_fields' => $this->form_fields,
            'surname' => $object->surname,
            'name' => $object->name
        ];

        return parent::create($request, $id);  
    }

    public function store(Request $request)
    {
    	$id = $request->input('idpac');

        $this->redirectIfIdIsNull($id, $this->other_route);  	
    	
    	$hour = trim ( $request->input('hour') );
    	$day = trim ( $request->input('day') );
        $notes = $this->sanitizeData($request->input('notes'));

        if ( !$this->validateDate($day) || !$this->validateTime($hour) ) {
		  	$request->session()->flash($this->error_message_name, 'Fecha o hora incorrecta.');	
			return redirect("/$this->main_route/$id/create");
		}
	    	  
        $validator = Validator::make($request->all(), [
	        'hour' => 'required',
	        'day' => 'required',
	        'notes' => 'nullable'
	    ]);
            
        if ($validator->fails()) {
	        return redirect("/$this->main_route/$id/create")
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {
	        	
		    Citas::create([
		        'idpac' => $id,
		        'hour' => $hour,
		        'day' => $day,
		        'notes' => $notes
		    ]);
		      
		    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );	
	        	        	
	        return redirect("/$this->main_route/$id/create");
        }     
    }

    public function edit(Request $request, $id, $idcit = false)
    {
        $this->redirectIfIdIsNull($id, $this->other_route);
        $this->redirectIfIdIsNull($idcit, $this->other_route);

        $id = $this->sanitizeData($id);
        $idcit = $this->sanitizeData($idcit);
    	
    	$object = Citas::find($idcit);

        $this->form_fields = [
            'hour' => true,
            'day' => true,
            'notes' => true,
        ];

    	return view($this->views_folder.'.edit', [
    		'request' => $request,
    		'object' => $object,
            'form_fields' => $this->form_fields,            
    		'idcit' => $idcit,
    		'idpac' => $id
    	]);
    }

    public function update(Request $request, $idcit)
    {
        $idcit = $this->sanitizeData($idcit);
        $id = $this->sanitizeData($request->input('idpac'));

        $exists = Citas::where('idcit', $idcit)->where('idpac', $id)->exists();

        if ($exists != true) {
            $request->session()->flash($this->error_message_name, 'Error');  
            return redirect("/$this->main_route/$id/$idcit/edit");
        }

        $this->redirectIfIdIsNull($idcit, $this->other_route);
        $this->redirectIfIdIsNull($id, $this->other_route);
       	  
        $validator = Validator::make($request->all(), [
            'hour' => 'required|date_format:H:i',
            'day' => 'required|date_format:Y:m:d',
            'notes' => 'nullable'
	    ]);
            
        if ($validator->fails()) {
	        return redirect("/$this->main_route/$id/$idcit/edit")
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {

	    	$hour = trim($request->input('hour'));
	    	$day = trim($request->input('day'));

            if ( !$this->validateTime($hour) || !$this->validateDate($day) ) {
                $request->session()->flash($this->error_message_name, 'Fecha o hora incorrecta');  
                return redirect("/$this->main_route/$id/$idcit/edit");
            }
				
			$object = Citas::find($idcit);

	    	$notes = ucfirst(strtolower($request->input('notes')));

            $object->hour = $this->sanitizeData($hour);
            $object->day = $this->sanitizeData($day);
            $object->notes = $this->sanitizeData($notes);
			
			$object->save();

			$request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

			return redirect("$this->other_route/$id");
		}   
    }

    public function del(Request $request, $id, $idcit)
    {    	  
        $idcit = $this->sanitizeData($idcit);
        $id = $this->sanitizeData($id);

        $this->redirectIfIdIsNull($idcit, $this->other_route);
        $this->redirectIfIdIsNull($id, $this->other_route);

        $object = Citas::find($idcit);

    	return view($this->views_folder.'.del', [
            'request' => $request,
            'object' => $object,
            'idcit' => $idcit,
    		'idpac' => $id
        ]);
    }
 
    public function destroy(Request $request, $idcit)
    {       
        $id = $this->sanitizeData($request->input('idpac'));
        $idcit = $this->sanitizeData($idcit);

        $this->redirectIfIdIsNull($idcit, $this->other_route);
        $this->redirectIfIdIsNull($id, $this->other_route);
       
        $object = Citas::find($idcit);
      
        $object->delete();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect("$this->other_route/$id");
    }

    private function getItemsByDate($selec, $fechde, $fechha)
    {
        if ( $selec == 'todas' ) {

            $appointments_of = 'todas';
            $msg_type = true;

            $main_loop = DB::table('citas')
                ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
                ->select('citas.*','pacientes.surname','pacientes.name')
                ->whereNull('pacientes.deleted_at')
                ->orderBy('day' , 'DESC')
                ->orderBy('hour' , 'ASC')
                ->get();

        } elseif ( $selec == 'hoy' ) {

            $selfe1 = date('Y-m-d');
            $appointments_of = 'hoy';
            $msg_type = true;

            $main_loop = DB::table('citas')
                ->join('pacientes','citas.idpac','=','pacientes.idpac')
                ->select('citas.*','pacientes.surname','pacientes.name')
                ->where('day', $selfe1)
                ->whereNull('pacientes.deleted_at')
                ->orderBy('day' , 'DESC')
                ->orderBy('hour' , 'ASC')
                ->get(); 

        } elseif ($selec == '1semana' ) {

            $selfe1 = date('Y-m-d');
            $selfe2 = date('Y-m-d', strtotime('+1 Week'));
            $appointments_of = '+1 semana';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);
                        
        } elseif ($selec == '1mes' ) {

            $selfe1 = date('Y-m-d');
            $selfe2 = date('Y-m-d', strtotime('+1 Month'));
            $appointments_of = '+1 mes';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);

        } elseif ($selec == '3mes' ) {

            $selfe1 = date('Y-m-d');
            $selfe2 = date('Y-m-d', strtotime('+3 Month'));
            $appointments_of = '+3 meses';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);

        } elseif ($selec == '1ano' ) {

            $selfe1 = date('Y-m-d');
            $selfe2 = date('Y-m-d', strtotime('+1 Year'));
            $appointments_of = '+1 año';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);

        } elseif ($selec == 'menos1mes' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-1 Month'));
            $appointments_of = '-1 mes';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);

        } elseif ($selec == 'menos3mes' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-3 Month'));
            $appointments_of = '-3 meses';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);

        } elseif ($selec == 'menos1ano' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-1 Year'));
            $appointments_of = '-1 año';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);

        } elseif ($selec == 'menos5ano' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-5 Year'));
            $appointments_of = '-5 años';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);

        } elseif ($selec == 'menos20ano' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-20 Year'));
            $appointments_of = '-20 años';
            $msg_type = true;

            $main_loop = $this->getQueryResults($selfe1, $selfe2);

        } elseif ($selec == 'rango' ) {

            $selfe2 = $fechha;
            $selfe1 = $fechde;
            $appointments_of = "Citas entre ".$this->convertYmdToDmY($fechde)." y ".$this->convertYmdToDmY($fechha);
            $msg_type = false;
            
            $main_loop = $this->getQueryResults($selfe1, $selfe2);                                                                      
        }

        $data['main_loop'] = $main_loop;
        $data['appointments_of'] = $appointments_of;       
        $data['msg'] = false;
        $data['msg_type'] = $msg_type;

        return $data;
    }

    private function getQueryResults($selfe1, $selfe2)
    {
        $main_loop = DB::table('citas')
            ->join('pacientes', 'citas.idpac', '=', 'pacientes.idpac')
            ->select('citas.*','pacientes.surname','pacientes.name')
            ->whereBetween('day', [$selfe1, $selfe2])
            ->whereNull('pacientes.deleted_at')
            ->orderBy('day' , 'DESC')
            ->orderBy('hour' , 'ASC')
            ->get(); 

        return $main_loop;
    }

}
