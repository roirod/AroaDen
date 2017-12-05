<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatments;
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
            'per' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }   

    public function create(Request $request, $id = false)
    {  
        $this->redirectIfIdIsNull($id, $this->other_route);

        $servicios = Services::AllOrderByName();
        $personal = Staff::AllOrderBySurnameNoPagination();
        $object = Patients::FirstById($id);

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $object->idpac;
        $this->view_data['servicios'] = $servicios;
        $this->view_data['personal'] = $personal;        
        $this->view_data['name'] = $object->name;
        $this->view_data['surname'] = $object->surname;
        $this->view_data['form_route'] = $this->form_route;
        $this->view_data['form_fields'] = $this->form_fields;

        $this->setPageTitle($object->surname.', '.$object->name);

        return parent::create($request, $id);
    }

    public function select(Request $request)
    {
        $id = $this->sanitizeData( $request->input('idser_select') );

        $servicio = Services::FirstById($id);

        $data = [];
        $data['idser'] = $servicio->idser;
        $data['name'] = $servicio->name;        
        $data['price'] = $servicio->price;
        $data['tax'] = $servicio->tax;   
        
        $this->echoJsonOuptut($data);
    }

    public function store(Request $request)
    {
        $idpac = $this->sanitizeData( $request->input('idpac') );
        $idser = $this->sanitizeData( $request->input('idser') );

        $this->redirectIfIdIsNull($idpac, $this->other_route);
        $this->redirectIfIdIsNull($idser, $this->other_route);

        $servicio = Services::FirstById($idser);     

        $price = $servicio->price;
        $units = $this->sanitizeData( $request->input('units') );
        $paid = $this->sanitizeData( $request->input('paid') );
        $day = $this->sanitizeData( $request->input('day') );
        $tax = $servicio->tax;
        $per1 = $this->sanitizeData( $request->input('per1') );
        $per2 = $this->sanitizeData( $request->input('per2') );

        $data = [];

        try {

            Treatments::create([
                'idpac' => $idpac,
                'idser' => $idser,
                'price' => $price,
                'units' => $units,
                'paid' => $paid,
                'day' => $day,
                'tax' => $tax,
                'per1' => $per1,
                'per2' => $per2
            ]);           

        } catch(Exception $e) {

            $data['msg'] = $e->getMessage();

            $this->echoJsonOuptut($data);

        }

        $data['msg'] = Lang::get('aroaden.success_message');
       
        $this->echoJsonOuptut($data);                      
    }

    public function edit(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->other_route);
        $id = $this->sanitizeData($id);
    
        $object = Treatments::FirstById($id); 
        $personal = Staff::AllOrderBySurnameNoPagination();
        $paciente = Patients::FirstById($object->idpac);

        $this->view_data['request'] = $request;
        $this->view_data['id'] = $id;
        $this->view_data['idnav'] = $object->idpac;        
        $this->view_data['object'] = $object;
        $this->view_data['personal'] = $personal;        
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

            try {

                $tratampacien = Treatments::find($id);

                if ($this->checkIfPaidIsHigher($units, $price, $paid))
                    throw new Exception(Lang::get('aroaden.paid_is_higher'));

                $tratampacien->units = $units;
                $tratampacien->paid = $paid;
                $tratampacien->day = $this->sanitizeData($request->input('day'));
                $tratampacien->per1 = $this->sanitizeData($request->input('per1'));
                $tratampacien->per2 = $this->sanitizeData($request->input('per2'));
                $tratampacien->updated_at = date('Y-m-d H:i:s');

                $tratampacien->save();

            } catch(Exception $e) {

                $request->session()->flash($this->error_message_name, $e->getMessage());  
                                
                return redirect("/$this->main_route/$id/edit");

            }
              
            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );  
                            
            return redirect("/$this->main_route/$id/edit");
        }     
    }

    public function destroy(Request $request, $id)
    {               
        $id = $this->sanitizeData($id);  

        $this->redirectIfIdIsNull($id, $this->main_route);
                
        $tratampacien = Treatments::find($id);
      
        $tratampacien->delete();

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect("$this->main_route/$tratampacien->idpac");
    }
}
