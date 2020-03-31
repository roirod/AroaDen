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
      'save' => true
    ];

    $this->form_fields = array_replace($this->form_fields, $fields);
  }
  
  public function index(Request $request)
  {

    $this->view_data['form_route'] = $this->form_route;
    $this->view_data['error'] = false;
    $this->view_data['msg'] = Lang::get('aroaden.today_appointments');

    try {

      $this->view_data['main_loop'] = $this->model::AllTodayOrderByDay();
      $this->view_data['count'] = $this->model::CountAllToday();

      if ($this->view_data['count'] === 0)
        $this->view_data['msg'] = Lang::get('aroaden.no_appointments_today');

    } catch (Exception $e) {

      $this->view_data['error'] = true;
      $this->view_data['msg'] = $e->getMessage();

    }

    $this->setPageTitle(Lang::get('aroaden.today_appointments'));

    return parent::index($request);
  }
  
  public function list(Request $request)
  {
    $this->request = $request;

    $this->misc_array['main_loop'] = false;
    $this->misc_array['msg'] = false;
    $this->misc_array['count'] = false;
    $this->misc_array['error'] = false;

    try {

      $this->getItemsByDate();

    } catch (Exception $e) {

      $this->misc_array['error'] = true;
      $this->misc_array['msg'] = $e->getMessage();

    }

    $this->setPageTitle($this->misc_array['msg']);

    $this->view_name = 'tableStaff';
    $this->view_data['main_loop'] = $this->misc_array['main_loop'];
    $this->view_data['msg'] = $this->misc_array['msg'];
    $this->view_data['count'] = $this->misc_array['count'];
    $this->view_data['error'] = $this->misc_array['error'];
    $this->view_data['request'] = $request;

    return $this->loadView();
  }

  private function getItemsByDate()
  {
    $this->misc_array['select_val'] = $this->sanitizeData($this->request->input('select_val'));

    if ($this->misc_array['select_val'] == 'date_range') {

      $this->misc_array['date_from_DmY'] = $this->sanitizeData($this->request->input('date_from'));
      $this->misc_array['date_to_DmY'] = $this->sanitizeData($this->request->input('date_to'));
      $this->misc_array['date_from_Ymd'] = $this->convertDmYToYmd($this->misc_array['date_from_DmY']);
      $this->misc_array['date_to_Ymd'] = $this->convertDmYToYmd($this->misc_array['date_to_DmY']);

      if (
          !$this->validateDateDDMMYYYY($this->misc_array['date_from_DmY']) || 
          !$this->validateDateDDMMYYYY($this->misc_array['date_to_DmY'])
        )
          throw new Exception(Lang::get('aroaden.date_format_fail'));

      if ($this->misc_array['date_from_Ymd'] > $this->misc_array['date_to_Ymd'])
        throw new Exception(Lang::get('aroaden.date_from_is_older', ['date_to' => $this->misc_array['date_to_DmY'], 'date_from' => $this->misc_array['date_from_DmY']]));

      $date_f = new DateTime($this->misc_array['date_from_Ymd']);
      $date_t = new DateTime($this->misc_array['date_to_Ymd']);
      $diff = $date_f->diff($date_t);

      if ((int)$diff->days > (int)$this->date_max_days)
          throw new Exception(Lang::get('aroaden.date_out_range', ['date_max_days' => $this->date_max_days]));
    }

    switch ($this->misc_array['select_val']) {
      case 'today':
        $this->misc_array['msg'] = Lang::get('aroaden.today_appointments');
        break;

      case '1week':
        $this->misc_array['date_from_Ymd'] = date('Y-m-d');
        $this->misc_array['date_to_Ymd'] = date('Y-m-d', strtotime('+1 Week'));
        $this->misc_array['msg'] = Lang::get('aroaden.1week_appointments');
        break;

      case '1month':
        $this->misc_array['date_from_Ymd'] = date('Y-m-d');
        $this->misc_array['date_to_Ymd'] = date('Y-m-d', strtotime('+1 Month'));
        $this->misc_array['msg'] = Lang::get('aroaden.1month_appointments');
        break;

      case 'minus1week':
        $this->misc_array['date_from_Ymd'] = date('Y-m-d', strtotime('-1 Week'));
        $this->misc_array['date_to_Ymd'] = date('Y-m-d');
        $this->misc_array['msg'] = Lang::get('aroaden.minus1week_appointments');
        break;

      case 'minus1month':
        $this->misc_array['date_from_Ymd'] = date('Y-m-d', strtotime('-1 Month'));
        $this->misc_array['date_to_Ymd'] = date('Y-m-d');
        $this->misc_array['msg'] = Lang::get('aroaden.minus1month_appointments');
        break;

      case 'date_range':
        $this->misc_array['msg'] = Lang::get('aroaden.appointments_range', ['date_from' => $this->misc_array['date_from_DmY'], 'date_to' => $this->misc_array['date_to_DmY']]);
        break;
    }

    if ($this->misc_array['select_val'] == 'today') {

      $this->misc_array['main_loop'] = $this->model::AllTodayOrderByDay();

    } else {

      $this->misc_array['main_loop'] = $this->model::AllBetweenRangeOrderByDay($this->misc_array['date_from_Ymd'], $this->misc_array['date_to_Ymd']);

    }

    $this->misc_array['count'] = $this->misc_array['main_loop']->count();

    if ((int) $this->misc_array['count'] === 0)
      throw new Exception(Lang::get('aroaden.no_query_results'));
  }

  public function create(Request $request, $id = false)
  {     
      $this->redirectIfIdIsNull($id, $this->other_route);

      $object = $this->model2::FirstById($id);
      $this->setPageTitle($object->surname.', '.$object->name);

      $this->view_data['id'] = $id;
      $this->view_data['idnav'] = $object->idpat;
      $this->view_data['object'] = $object;
      $this->view_data['form_fields'] = $this->form_fields;

      return parent::create($request, $id);  
  }

  public function store(Request $request)
  {
      $idpat = $request->input('idpat');
      $this->redirectIfIdIsNull($idpat, $this->other_route);      
      
      $hour = trim($request->input('hour'));
      $day = trim($request->input('day'));
      $notes = $this->sanitizeData($request->input('notes'));

      if (!$this->validateDateDDMMYYYY($day) || !$this->validateTime($hour)) {
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

          $day = $this->convertDmYToYmd($day);

          try {
              
              $this->model::create([
                  'idpat' => $idpat,
                  'hour' => $hour,
                  'day' => $day,
                  'notes' => $notes
              ]);

          } catch (Exception $e) {

              $request->session()->flash($this->error_message_name, $e->getMessage());
              return redirect("/$this->main_route/$idpat/create");

          }

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
      $route = "/$this->main_route/$id/edit";

      if (!$exists) {
          $request->session()->flash($this->error_message_name, 'Error');  
          return redirect($route);
      }

      $this->redirectIfIdIsNull($id, $this->other_route);
        
      $validator = Validator::make($request->all(), [
          'hour' => 'required',
          'day' => 'required',
          'notes' => ''
      ]);
          
      if ($validator->fails()) {
          return redirect($route)
                       ->withErrors($validator)
                       ->withInput();
      } else { 
          
          $hour = trim($request->input('hour'));
          $day = trim($request->input('day'));
          $notes = $this->sanitizeData($request->input('notes'));

          if (!$this->validateDateDDMMYYYY($day) || !$this->validateTime($hour)) {
              $request->session()->flash($this->error_message_name, Lang::get('aroaden.date_time_fail')); 
              return redirect($route);
          }
              
          $object = $this->model::find($id);

          $day = $this->convertDmYToYmd($day);

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
      $object = $this->model::find($id);

      $this->redirect_to = "/$this->other_route/$object->idpat";

      return parent::destroy($request, $id);        
  }

}
