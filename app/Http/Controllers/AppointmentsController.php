<?php

namespace App\Http\Controllers;

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

      if ((int)$this->view_data['count'] === 0)
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
    unset($this->request);
    $this->request = $request;

    unset($this->misc_array);
    $this->misc_array['main_loop'] = false;
    $this->misc_array['msg'] = false;
    $this->misc_array['count'] = false;
    $this->misc_array['error'] = false;
    $this->misc_array['select'] = $this->sanitizeData($this->request->select);

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
    extract($this->misc_array);

    if ($select == 'date_range') {

      $date_from_DmY = $this->sanitizeData($this->request->date_from);
      $date_to_DmY = $this->sanitizeData($this->request->date_to);
      $date_from_Ymd = $this->convertDmYToYmd($date_from_DmY);
      $date_to_Ymd = $this->convertDmYToYmd($date_to_DmY);

      if (
          !$this->validateDateDDMMYYYY($date_from_DmY) || 
          !$this->validateDateDDMMYYYY($date_to_DmY)
        )
          throw new Exception(Lang::get('aroaden.date_format_fail'));

      if ($date_from_Ymd > $date_to_Ymd)
        throw new Exception(Lang::get('aroaden.date_from_is_older', ['date_to' => $date_to_DmY, 'date_from' => $date_from_DmY]));

      $date_f = new DateTime($date_from_Ymd);
      $date_t = new DateTime($date_to_Ymd);
      $diff = $date_f->diff($date_t);

      if ((int)$diff->days > (int)$this->date_max_days)
          throw new Exception(Lang::get('aroaden.date_out_range', ['date_max_days' => $this->date_max_days]));
    }

    switch ($select) {
      case 'today':
        $msg = Lang::get('aroaden.today_appointments');
        break;

      case '1week':
        $date_from_Ymd = date('Y-m-d');
        $date_to_Ymd = date('Y-m-d', strtotime('+1 Week'));
        $msg = Lang::get('aroaden.1week_appointments');
        break;

      case '1month':
        $date_from_Ymd = date('Y-m-d');
        $date_to_Ymd = date('Y-m-d', strtotime('+1 Month'));
        $msg = Lang::get('aroaden.1month_appointments');
        break;

      case 'minus1week':
        $date_from_Ymd = date('Y-m-d', strtotime('-1 Week'));
        $date_to_Ymd = date('Y-m-d');
        $msg = Lang::get('aroaden.minus1week_appointments');
        break;

      case 'minus1month':
        $date_from_Ymd = date('Y-m-d', strtotime('-1 Month'));
        $date_to_Ymd = date('Y-m-d');
        $msg = Lang::get('aroaden.minus1month_appointments');
        break;

      case 'date_range':
        $msg = Lang::get('aroaden.appointments_range', ['date_from' => $date_from_DmY, 'date_to' => $date_to_DmY]);
        break;
    }

    if ($select == 'today') {

      $main_loop = $this->model::AllTodayOrderByDay();

    } else {

      $main_loop = $this->model::AllBetweenRangeOrderByDay($date_from_Ymd, $date_to_Ymd);

    }

    $count = $main_loop->count();

    if ((int)$count === 0)
      throw new Exception(Lang::get('aroaden.no_query_results'));

    $this->misc_array['msg'] = $msg;
    $this->misc_array['main_loop'] = $main_loop;
    $this->misc_array['count'] = $count;
  }

  public function create(Request $request, $id = false)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->other_route);

    $object = $this->model2->FirstById($id);
    $this->setPageTitle($object->surname.', '.$object->name);

    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $object->idpat;
    $this->view_data['object'] = $object;

    return parent::create($request, $id);  
  }

  public function store(Request $request)
  {
    extract($this->sanitizeRequest($request->all()));

    $this->redirectIfIdIsNull($idpat, $this->other_route);

    $route = "/$this->main_route/$idpat/create";

    if (!$this->validateDateDDMMYYYY($day) || !$this->validateTime($hour)) {
      $request->session()->flash($this->error_message_name, Lang::get('aroaden.date_time_fail')); 
      return redirect($route);
    }

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
      return redirect($route);

    }

    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
    return redirect($route); 
  }

  public function edit(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->other_route);
    $object = $this->model::FirstById($id);

    $this->autofocus = 'hour';
    $this->view_data['object'] = $object;
    $this->view_data['id'] = $id;
    $this->view_data['idnav'] = $object->idpat;
    $this->view_data['name'] = $object->name;
    $this->view_data['surname'] = $object->surname;

    $this->setPageTitle($object->surname.', '.$object->name);

    return parent::edit($request, $id);
  }

  public function update(Request $request, $id)
  {
    $id = $this->sanitizeData($id);
    $this->redirectIfIdIsNull($id, $this->other_route);

    $exists = $this->model::CheckIfIdExists($id);
    $route = "/$this->main_route/$id/edit";

    if (!$exists) {
      $request->session()->flash($this->error_message_name, 'Error');  
      return redirect($route);
    }      

    extract($this->sanitizeRequest($request->all()));

    if (!$this->validateDateDDMMYYYY($day) || !$this->validateTime($hour)) {
        $request->session()->flash($this->error_message_name, Lang::get('aroaden.date_time_fail')); 
        return redirect($route);
    }
        
    $object = $this->model::find($id);

    $day = $this->convertDmYToYmd($day);

    $object->hour = $hour;
    $object->day = $day;
    $object->notes = $notes;
    
    $object->save();

    $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
    return redirect("$this->other_route/$object->idpat");
  }

  public function destroy(Request $request, $id)
  {      
    return parent::destroy($request, $id);        
  }

}
