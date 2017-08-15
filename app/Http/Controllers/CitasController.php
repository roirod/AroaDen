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
        $this->form_route = 'list';

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
        $main_loop = Citas::AllTodayOrderByDay();
        $count = Citas::CountAllToday();

        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['count'] = $count;
        $this->view_data['form_route'] = $this->form_route;

        return parent::index($request);
    }
    
    public function list(Request $request)
    {
        $selec = $this->sanitizeData($request->input('selec'));

        $data = [];
        $data['main_loop'] = false;
        $data['appointments_of'] = false;   
        $data['msg'] = false; 

        $count = Citas::CountAll();

        if ($count == 0) {
    
            $data['msg'] = ' No hay citas en la base de datos. ';

        } else {

            $fechde = $this->sanitizeData($request->input('fechde'));
            $fechha = $this->sanitizeData($request->input('fechha'));

        	if ( $selec == 'rango' ) {

		        if ( $this->validateDate($fechde) == false || $this->validateDate($fechha) == false ) {
   
		            $data['msg'] = ' Fecha/s incorrecta, introduzca fechas válidas. ejemplo: 14/04/2017. ';

			 	} elseif ( $fechde > $fechha ) {
   
		            $data['msg'] = "La fecha ".$this->convertYmdToDmY($fechha)." es anterior a ".$this->convertYmdToDmY($fechde) .".";

		        } else {

		       		$data = $this->getItemsByDate('rango', $fechde, $fechha);

		       	}

		    } else {

	        	$data = $this->getItemsByDate($selec, $fechde, $fechha);
	        	   	
			}     
	    }

        $this->echoJsonOuptut($data);
    }

    public function create(Request $request, $id = false)
    {  	  
        $this->redirectIfIdIsNull($id, $this->other_route);
    	
    	$object = Pacientes::FirstById($id);

        $this->page_title = $object->surname.', '.$object->name.' - '.$this->page_title;

        $this->passVarsToViews();

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idpac'] = $object->idpac;
        $this->view_data['name'] = $object->name;
        $this->view_data['surname'] = $object->surname;
        $this->view_data['form_fields'] = $this->form_fields;

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
	        'notes' => ''
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

    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->other_route);

        $id = $this->sanitizeData($id);

        $object = Citas::FirstById($id);

        $this->page_title = $object->surname.', '.$object->name.' - '.$this->page_title;
        $this->autofocus = 'hour';

        $this->passVarsToViews();

        $this->view_data['request'] = $request;
        $this->view_data['object'] = $object;
        $this->view_data['id'] = $id;
        $this->view_data['idpac'] = $object->idpac;
        $this->view_data['name'] = $object->name;
        $this->view_data['surname'] = $object->surname;
        $this->view_data['form_fields'] = $this->form_fields;
        $this->view_data['autofocus'] = $this->autofocus;

        return parent::edit($request, $id);
    }

    public function update(Request $request, $id)
    {
        $id = $this->sanitizeData($id);

        $exists = Citas::CheckIfIdExists($id);

        if (!$exists) {
            $request->session()->flash($this->error_message_name, 'Error');  
            return redirect("/$this->main_route/$id/edit");
        }

        $this->redirectIfIdIsNull($id, $this->other_route);
       	  
        $validator = Validator::make($request->all(), [
            'hour' => 'required',
            'day' => 'required',
            'notes' => ''
	    ]);
            
        if ($validator->fails()) {
	        return redirect("/$this->main_route/$id/edit")
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {

	    	$hour = trim($request->input('hour'));
	    	$day = trim($request->input('day'));

            if ( !$this->validateTime($hour) || !$this->validateDate($day) ) {
                $request->session()->flash($this->error_message_name, 'Fecha o hora incorrecta');  
                return redirect("/$this->main_route/$id/$idcit/edit");
            }
				
			$object = Citas::find($id);

	    	$notes = ucfirst(strtolower($request->input('notes')));

            $object->hour = $this->sanitizeData($hour);
            $object->day = $this->sanitizeData($day);
            $object->notes = $this->sanitizeData($notes);
			
			$object->save();

			$request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

			return redirect("$this->other_route/$object->idpac");
		}   
    }

    public function destroy(Request $request, $id)
    {       
        $id = $this->sanitizeData($id);

        $this->redirectIfIdIsNull($id, $this->other_route);
       
        $object = Citas::find($id);
        $object->delete();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect("$this->other_route/$object->idpac");
    }

    private function getItemsByDate($selec, $fechde, $fechha)
    {
        $msg_type = true;

        if ( $selec == 'todas' ) {

            $appointments_of = 'todas';
            $main_loop = Citas::AllOrderByDay();

        } elseif ( $selec == 'hoy' ) {

            $appointments_of = 'hoy';
            $main_loop = Citas::AllTodayOrderByDay();

        } elseif ($selec == '1semana' ) {

            $selfe1 = date('Y-m-d');
            $selfe2 = date('Y-m-d', strtotime('+1 Week'));
            $appointments_of = '+1 semana';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);
                        
        } elseif ($selec == '1mes' ) {

            $selfe1 = date('Y-m-d');
            $selfe2 = date('Y-m-d', strtotime('+1 Month'));
            $appointments_of = '+1 mes';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);

        } elseif ($selec == '3mes' ) {

            $selfe1 = date('Y-m-d');
            $selfe2 = date('Y-m-d', strtotime('+3 Month'));
            $appointments_of = '+3 meses';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);

        } elseif ($selec == '1ano' ) {

            $selfe1 = date('Y-m-d');
            $selfe2 = date('Y-m-d', strtotime('+1 Year'));
            $appointments_of = '+1 año';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);

        } elseif ($selec == 'menos1mes' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-1 Month'));
            $appointments_of = '-1 mes';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);

        } elseif ($selec == 'menos3mes' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-3 Month'));
            $appointments_of = '-3 meses';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);

        } elseif ($selec == 'menos1ano' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-1 Year'));
            $appointments_of = '-1 año';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);

        } elseif ($selec == 'menos5ano' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-5 Year'));
            $appointments_of = '-5 años';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);

        } elseif ($selec == 'menos20ano' ) {

            $selfe2 = date('Y-m-d');
            $selfe1 = date('Y-m-d', strtotime('-20 Year'));
            $appointments_of = '-20 años';
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);

        } elseif ($selec == 'rango' ) {

            $selfe2 = $fechha;
            $selfe1 = $fechde;
            $appointments_of = "Citas entre ".$this->convertYmdToDmY($fechde)." y ".$this->convertYmdToDmY($fechha);
            $msg_type = false;
            $main_loop = Citas::AllBetweenRangeOrderByDay($selfe1, $selfe2);                                                                      
        }

        $data = [];
        $data['main_loop'] = $main_loop;
        $data['appointments_of'] = $appointments_of;       
        $data['msg'] = false;
        $data['msg_type'] = $msg_type;

        return $data;
    }

}
