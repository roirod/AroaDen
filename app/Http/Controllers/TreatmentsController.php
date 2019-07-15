<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatments;
use App\Models\StaffWorks;
use App\Models\Patients;
use App\Models\Services;
use App\Models\Staff;
use Validator;
use Exception;
use Lang;
use DB;

class TreatmentsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = $this->config['routes']['treatments'];
        $this->other_route = $this->config['routes']['patients'];             
        $this->views_folder = $this->config['routes']['treatments'];        
        $this->form_route = 'select';   

        $fields = [
            'units' => true,
            'paid' => true,
            'day' => true,
            'staff' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }   

    public function create(Request $request, $id = false)
    {  
        $this->redirectIfIdIsNull($id, $this->other_route);
        $object = Patients::FirstById($id);

        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $object->idpat;
        $this->view_data['services'] = Services::AllOrderByName();
        $this->view_data['staff'] = Staff::AllOrderBySurnameNoPagination();
        $this->view_data['name'] = $object->name;
        $this->view_data['surname'] = $object->surname;
        $this->view_data['form_fields'] = $this->form_fields;

        $this->setPageTitle($object->surname.', '.$object->name);

        return parent::create($request, $id);
    }

    public function select(Request $request)
    {
        $id = $this->sanitizeData($request->input('idser_select'));

        $service = Services::FirstById($id);

        $data = [];
        $data['idser'] = $id;
        $data['name'] = html_entity_decode($service->name);        
        $data['price'] = $service->price;
        $data['tax'] = $service->tax;   
        
        $this->echoJsonOuptut($data);
    }

    public function store(Request $request)
    {
        $idpat = $this->sanitizeData($request->input('idpat'));
        $idser = $this->sanitizeData($request->input('idser'));
        $this->redirectIfIdIsNull($idpat, $this->other_route);
        $this->redirectIfIdIsNull($idser, $this->other_route);

        $service = Services::FirstById($idser);     

        $price = $service->price;
        $units = $this->sanitizeData($request->input('units'));
        $paid = $this->sanitizeData($request->input('paid'));
        $day = $this->sanitizeData($request->input('day'));
        $day = $this->convertDmYToYmd($day);
        $tax = $service->tax;
        $staff = $request->input('staff');

        DB::beginTransaction();

        try {

            $idtre = Treatments::insertGetId([
                'idpat' => $idpat,
                'idser' => $idser,
                'price' => $price,
                'units' => $units,
                'paid' => $paid,
                'day' => $day,
                'tax' => $tax,
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            if (count($staff) > 0) {
                foreach ($staff as $idsta) {
                    StaffWorks::create([
                      'idsta' => $idsta,
                      'idtre' => $idtre
                    ]);
                }
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            $request->session()->flash($this->error_message_name, $e->getMessage());  
            return redirect("/$this->other_route/$idpat");

        }

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );      
        return redirect("/$this->other_route/$idpat");
    }

    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->other_route);
        $id = $this->sanitizeData($id);
    
        $object = Treatments::FirstById($id);
        $paciente = Patients::FirstById($object->idpat);

        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $object->idpat;        
        $this->view_data['object'] = $object;
        $this->view_data['staff_works'] = StaffWorks::AllById($id)->toArray();
        $this->view_data['staff'] = Staff::AllOrderBySurnameNoPagination();
        $this->view_data['name'] = $paciente->name;
        $this->view_data['surname'] = $paciente->surname;
        $this->view_data['form_fields'] = $this->form_fields;        
        $this->view_data['autofocus'] = 'units';
        
        $this->setPageTitle($paciente->surname.', '.$paciente->name);

        return parent::edit($request, $id);
    }

    public function update(Request $request, $id)
    {
        $id = $this->sanitizeData($id);  
                  
        $validator = Validator::make($request->all(), [
            'units' => 'required',            
            'paid' => 'required',
            'price' => 'required',
            'day' => 'required|date',
            'per1' => '',
            'per2' => ''
        ]);
            
        if ($validator->fails()) {
            return redirect("/$this->main_route/$id/edit")
                         ->withErrors($validator)
                         ->withInput();
        } else {

            $units = $this->sanitizeData($request->input('units'));
            $price = $this->sanitizeData($request->input('price'));
            $paid = $this->sanitizeData($request->input('paid'));
            $day = $this->sanitizeData($request->input('day'));
            $day = $this->convertDmYToYmd($day);
            $staff = $request->input('staff');

            DB::beginTransaction();

            try {

                $treatment = Treatments::find($id);

                if ($this->checkIfPaidIsHigher($units, $price, $paid))
                    throw new Exception(Lang::get('aroaden.paid_is_higher'));

                $treatment->units = $units;
                $treatment->paid = $paid;
                $treatment->day = $day;
                $treatment->updated_at = date('Y-m-d H:i:s');
                $treatment->save();

                StaffWorks::where('idtre', $id)->delete();

                if (count($staff) > 0) {
                    foreach ($staff as $idsta) {
                        StaffWorks::create([
                          'idsta' => $idsta,
                          'idtre' => $id
                        ]);
                    }
                }

                DB::commit();

            } catch (\Exception $e) {

                DB::rollBack();

                $request->session()->flash($this->error_message_name, $e->getMessage());  
                return redirect("/$this->main_route/$id/edit");

            }
             
            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );  
            return redirect("/$this->other_route/$treatment->idpat");
        }     
    }

    public function destroy(Request $request, $id)
    {               
        $id = $this->sanitizeData($id);
        $this->redirectIfIdIsNull($id, $this->other_route);

        DB::beginTransaction();
                
        try {

            $treatment = Treatments::find($id);
            $treatment->delete();

            StaffWorks::where('idtre', $id)->delete();

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            $request->session()->flash($this->error_message_name, $e->getMessage());  
            return redirect("$this->other_route/$treatment->idpat");

        }

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        return redirect("$this->other_route/$treatment->idpat");
    }
}
