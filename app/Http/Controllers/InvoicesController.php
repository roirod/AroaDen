<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Invoices\Rectification;
use App\Http\Controllers\Invoices\Complete;
use Illuminate\Http\Request;
use App\Models\Treatments;
use App\Models\Patients;
use App\Models\Services;
use App\Models\Invoices;
use App\Models\Staff;
use Lang;
use DB;

class InvoicesController extends BaseController
{
  private $invoice_types = [
    'Complete' => 'complete',
    'Rectification' => 'rectification',
  ];

  public function __construct(Invoices $invoices)
  {
    parent::__construct();

    $this->main_route = $this->config['routes']['invoices'];
    $this->other_route = $this->config['routes']['patients'];             
    $this->views_folder = $this->config['routes']['invoices'];        
    $this->model = $invoices;

    $fields = [      
      'date2' => true,
      'no_tax_msg' => true,
    ];

    $this->form_fields = array_replace($this->form_fields, $fields);
  }

  public function show(Request $request, $idpat = false)
  {     
    $idpat = $this->sanitizeData($idpat); 
    $this->redirectIfIdIsNull($idpat, $this->other_route);

    $object = Patients::FirstById($idpat);

    $this->setPageTitle($object->surname.', '.$object->name);

    $this->form_route = 'invoicesFactory';

    $this->view_data['request'] = $request;
    $this->view_data['object'] = $object;
    $this->view_data['invoice_types'] = $this->invoice_types;
    $this->view_data['default_type'] = $this->invoice_types['Complete'];
    $this->view_data['idpat'] = $idpat;
    $this->view_data['idnav'] = $idpat;        

    return parent::show($request, $idpat);
  }

  public function invoicesFactory(Request $request)
  {
    $id = $this->sanitizeData($request->id);
    $type = $this->sanitizeData($request->type);

    switch ($type) {
      case $this->invoice_types['Complete']:
        $object = new Complete($this->model);
        return $object->createInvoice($request, $id);
        break;

      case $this->invoice_types['Rectification']:
        $object = new Rectification($this->model);
        return $object->createInvoice($request, $id);
        break;

      default:
        $request->session()->flash($this->error_message_name, Lang::get('aroaden.error_message'));    
        return redirect("$this->main_route/$id");
        break;
    }
  }

  public function create(Request $request, $id = false)
  {    






      $treatments = Treatments::PaidByPatientId($id);

      $this->view_data['request'] = $request;
      $this->view_data['treatments'] = $treatments;
      $this->view_data['form_fields'] = $this->form_fields;        
      $this->view_data['idpat'] = $id;
      $this->view_data['idnav'] = $id;

      return parent::create($request, $id);
  }


  public function store(Request $request)
  {


  }



}



