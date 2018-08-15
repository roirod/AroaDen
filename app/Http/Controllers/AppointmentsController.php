<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Exceptions\NoAppointmentsFoundException;
use App\Http\Controllers\Interfaces\BaseInterface;
use Illuminate\Http\Request;
use App\Models\Appointments;
use App\Models\Patients;
use Validator;
use Exception;
use DateTime;
use Lang;
use DB;

class AppointmentsController extends BaseController implements BaseInterface
{
    public function __construct(Appointments $appointments, Patients $patients)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['appointments'];
        $this->other_route = $this->config['routes']['patients'];      
        $this->views_folder = $this->config['routes']['appointments'];        
        $this->model = $appointments;        
        $this->model2 = $patients; 
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
        $main_loop = $this->model::AllTodayOrderByDay();
        $count = $this->model::CountAllToday();

        $this->view_data['request'] = $request;
        $this->view_data['main_loop'] = $main_loop;
        $this->view_data['count'] = $count;
        $this->view_data['form_route'] = $this->form_route;

        $this->setPageTitle(Lang::get('aroaden.appointments'));

        return parent::index($request);
    }
    
    public function list(Request $request)
    {
        $select_val = $this->sanitizeData($request->input('select_val'));
        $date_from = $this->sanitizeData($request->input('date_from'));
        $date_to = $this->sanitizeData($request->input('date_to'));

        $data = [];
        $data['main_loop'] = false;
        $data['msg'] = false;
        $data['error'] = false;

        $count = $this->model::CountAll();

        if ((int)$count === 0) {

            $data['error'] = true;    
            $data['msg'] = Lang::get('aroaden.no_appointments_on_db');
            $this->echoJsonOuptut($data);

        }

        try {

            if ($select_val == 'date_range') {

                if (!$this->validateDate($date_from) || !$this->validateDate($date_to)) {

                    $data['error'] = true; 
                    $data['msg'] = Lang::get('aroaden.date_format_fail');
                    $this->echoJsonOuptut($data);

                } 

                if ($date_from > $date_to) {

                    $date_from = $this->convertYmdToDmY($date_from);
                    $date_to = $this->convertYmdToDmY($date_to);
                    $data['error'] = true;
                    $data['msg'] = Lang::get('aroaden.date_from_is_older', ['date_to' => $date_to, 'date_from' => $date_from]);
                    $this->echoJsonOuptut($data);

                } 

                $date_f = new DateTime($date_from);
                $date_t = new DateTime($date_to);
                $diff = $date_f->diff($date_t);

                if ((int)$diff->days > (int)$this->date_max_days) {

                    $data['error'] = true;    
                    $data['msg'] = Lang::get('aroaden.date_out_range', ['date_max_days' => $this->date_max_days]);
                    $this->echoJsonOuptut($data);

                }

                $data = $this->getItemsByDate($select_val, $date_from, $date_to);
                $this->echoJsonOuptut($data);

            }

            $data = $this->getItemsByDate($select_val);
            $this->echoJsonOuptut($data);

        } catch (NoAppointmentsFoundException $e) {

            $data['error'] = true;    
            $data['msg'] = $e->getMessage();
            $this->echoJsonOuptut($data);

        }
    }

    private function getItemsByDate($select, $date_from = null, $date_to = null)
    {
        switch ($select) {

            case 'today_appointments':
                $msg = Lang::get('aroaden.today_appointments');
                $main_loop = $this->model::AllTodayOrderByDay();
                break;

            case '1week_appointments':
                $date_from = date('Y-m-d');
                $date_to = date('Y-m-d', strtotime('+1 Week'));
                $msg = Lang::get('aroaden.1week_appointments');
                $main_loop = $this->model::AllBetweenRangeOrderByDay($date_from, $date_to);
                break;

            case '1month_appointments':
                $date_from = date('Y-m-d');
                $date_to = date('Y-m-d', strtotime('+1 Month'));
                $msg = Lang::get('aroaden.1month_appointments');
                $main_loop = $this->model::AllBetweenRangeOrderByDay($date_from, $date_to);
                break;

            case 'minus1week_appointments':
                $date_to = date('Y-m-d');
                $date_from = date('Y-m-d', strtotime('-1 Week'));
                $msg = Lang::get('aroaden.minus1week_appointments');
                $main_loop = $this->model::AllBetweenRangeOrderByDay($date_from, $date_to);
                break;

            case 'minus1month_appointments':
                $date_to = date('Y-m-d');
                $date_from = date('Y-m-d', strtotime('-1 Month'));
                $msg = Lang::get('aroaden.minus1month_appointments');
                $main_loop = $this->model::AllBetweenRangeOrderByDay($date_from, $date_to);
                break;

            case 'date_range':
                $main_loop = $this->model::AllBetweenRangeOrderByDay($date_from, $date_to);
                $date_from = $this->convertYmdToDmY($date_from);
                $date_to = $this->convertYmdToDmY($date_to);
                $msg = Lang::get('aroaden.appointments_range', ['date_from' => $date_from, 'date_to' => $date_to]);
                break;
        }

        $count = count($main_loop);

        if ((int)$count === 0)
            throw new NoAppointmentsFoundException(Lang::get('aroaden.no_query_results'));

        $data = [];
        $data['main_loop'] = $main_loop;
        $data['msg'] = $msg;
        $data['error'] = false;

        return $data;
    }

    public function create(Request $request, $id = false)
    {  	  
        $this->redirectIfIdIsNull($id, $this->other_route);

        $object = $this->model2::FirstById($id);
        $this->setPageTitle($object->surname.', '.$object->name);

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $object->idpat;
        $this->view_data['name'] = $object->name;
        $this->view_data['surname'] = $object->surname;
        $this->view_data['form_fields'] = $this->form_fields;

        return parent::create($request, $id);  
    }

    public function store(Request $request)
    {
    	$idpat = $request->input('idpat');
        $this->redirectIfIdIsNull($idpat, $this->other_route);  	
    	
    	$hour = trim ( $request->input('hour') );
    	$day = trim ( $request->input('day') );
        $notes = $this->sanitizeData($request->input('notes'));

        if ( !$this->validateDate($day) || !$this->validateTime($hour) ) {
		  	$request->session()->flash($this->error_message_name, Lang::get('aroaden.date_time_fail'));	
			return redirect("/$this->main_route/$idpat/create");
		}
	    	  
        $validator = Validator::make($request->all(), [
	        'hour' => 'required',
	        'day' => 'required',
	        'notes' => ''
	    ]);
            
        if ($validator->fails()) {
	        return redirect("/$this->main_route/$idpat/create")
	                     ->withErrors($validator)
	                     ->withInput();
	    } else {
	        	
		    $this->model::create([
		        'idpat' => $idpat,
		        'hour' => $hour,
		        'day' => $day,
		        'notes' => $notes
		    ]);
		      
		    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
	        return redirect("/$this->main_route/$idpat/create");
        }     
    }

    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->other_route);
        $id = $this->sanitizeData($id);
        $object = $this->model::FirstById($id);

        $this->autofocus = 'hour';
        $this->view_data['request'] = $request;
        $this->view_data['object'] = $object;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $object->idpat;
        $this->view_data['name'] = $object->name;
        $this->view_data['surname'] = $object->surname;
        $this->view_data['form_fields'] = $this->form_fields;
        $this->view_data['autofocus'] = $this->autofocus;

        $this->setPageTitle($object->surname.', '.$object->name);

        return parent::edit($request, $id);
    }

    public function update(Request $request, $id)
    {
        $id = $this->sanitizeData($id);

        $exists = $this->model::CheckIfIdExists($id);

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
                $request->session()->flash($this->error_message_name, Lang::get('aroaden.date_time_fail'));  
                return redirect("/$this->main_route/$id/$idapp/edit");
            }
				
			$object = $this->model::find($id);

	    	$notes = ucfirst(strtolower($request->input('notes')));

            $object->hour = $this->sanitizeData($hour);
            $object->day = $this->sanitizeData($day);
            $object->notes = $this->sanitizeData($notes);
			
			$object->save();

			$request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
			return redirect("$this->other_route/$object->idpat");
		}   
    }

    public function destroy(Request $request, $id)
    {       
        $id = $this->sanitizeData($id);
        $this->redirectIfIdIsNull($id, $this->other_route);
       
        $object = $this->model::find($id);
        $object->delete();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        return redirect("$this->other_route/$object->idpat");
    }

}
