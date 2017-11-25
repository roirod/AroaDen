<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use Lang;
use App\Models\Factutex;
use App\Models\Invoices;
use App\Models\Pacientes;
use App\Models\Treatments;
use Illuminate\Http\Request;
use App\Http\Controllers\Invoices\Complete;
use App\Http\Controllers\Invoices\Rectification;

class InvoicesController extends BaseController
{
    const COMPLETE = 'Complete';
    const RECTIFICATION = 'Rectification';

    /**
     * @var array $invoice_types  invoice types
     */
    private $invoice_types = [];

    public function __construct(Invoices $invoices)
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'invoices';
        $this->other_route = 'Pacientes';
        $this->views_folder = 'invoices';
        $this->model = $invoices;

        $fields = [      
            'issue_date' => true,
            'no_tax_msg' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);

        $complete = '"'.Lang::get('aroaden.complete').'"';
        $rectification = '"'.Lang::get('aroaden.rectification').'"';

        $this->invoice_types = [
            self::COMPLETE => $complete,
            self::RECTIFICATION => $rectification,
        ];
    }

    public function show(Request $request, $id)
    {     
        $this->redirectIfIdIsNull($id, $this->other_route);
        $idpac = $this->sanitizeData($id);

        $paciente = Pacientes::FirstById($idpac);
        $this->page_title = $paciente->surname.', '.$paciente->name.' - '.$this->page_title;
        $this->passVarsToViews();

        $this->form_route = 'invoicesFactory';

        $this->view_data['request'] = $request;
        $this->view_data['form_route'] = $this->form_route;
        $this->view_data['invoice_types'] = $this->invoice_types;
        $this->view_data['default_type'] = self::COMPLETE;
        $this->view_data['idpac'] = $idpac;
        $this->view_data['idnav'] = $idpac;        

        return view($this->views_folder.'.show', $this->view_data);
    }

    public function invoicesFactory(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        $this->redirectIfIdIsNull($id, $this->other_route);
        $id = $this->sanitizeData($id);
        $type = $this->sanitizeData($type);

        switch ($type) {
            case self::COMPLETE:
                $object = new Complete($this->model);
                return $object->createInvoice($request, $id);
            break;

            case self::RECTIFICATION:
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

        $tratampacien = Treatments::PaidServicesById($id);



echo "<pre>";
echo "<br>";
echo "------------ tratampacien ------------------";
echo "<br>";
var_dump($tratampacien);
echo "<br>";
echo "</pre>";

exit();




        $this->view_data['request'] = $request;
        $this->view_data['form_fields'] = $this->form_fields;        
        $this->view_data['idpac'] = $id;
        $this->view_data['idnav'] = $id;

        return parent::create($request, $id);
    }


    public function store(Request $request)
    {
        $idpac = htmlentities (trim( $request->input('idpac')),ENT_QUOTES,"UTF-8");
        $idser = htmlentities (trim( $request->input('idser')),ENT_QUOTES,"UTF-8");
        $precio = htmlentities (trim( $request->input('precio')),ENT_QUOTES,"UTF-8");
        $canti = htmlentities (trim( $request->input('canti')),ENT_QUOTES,"UTF-8");
        $factumun = htmlentities (trim( $request->input('factumun')),ENT_QUOTES,"UTF-8");
        $cod = htmlentities (trim( $request->input('cod')),ENT_QUOTES,"UTF-8");
        $iva = htmlentities (trim( $request->input('iva')),ENT_QUOTES,"UTF-8");            

        if ( null == $idpac ) {
            return redirect('Pacientes');
        }         
          
        $validator = Validator::make($request->all(), [
            'idpac' => 'required',
            'idser' => 'required',
            'precio' => 'required',
            'canti' => 'required',
            'factumun' => 'factumun',
            'cod' => 'required',
            'iva' => 'required',            
        ]);
            
        if ($validator->fails()) {
            return redirect("/Facturas/$idpac/create")
                         ->withErrors($validator)
                         ->withInput();
        } else {
                
            facturas::create([
                'idpac' => $idpac,
                'idser' => $idser,
                'precio' => $precio,
                'canti' => $canti,
                'factumun' => $factumun,
                'cod' => $cod,
                'iva' => $iva,
            ]);

            $facturas = DB::table('facturas')
                    ->join('servicios', 'facturas.idser','=','servicios.idser')
                    ->select('facturas.*','servicios.nomser')
                    ->where('idpac', $idpac)
                    ->where('cod', $cod)
                    ->orderBy('nomser' , 'ASC')
                    ->get();  

            $cadena = '';

            foreach ($facturas as $factu) {

                $cadena .= '<tr>
                                <td class="wid140">'.$factu->nomser.'</td>
                                <td class="wid95 textcent">'.$factu->canti.'</td>
                                <td class="wid95 textcent">'.$factu->precio.' €</td>

                                <td class="wid50">

                                  <form id="delform">
                                  
                                    <input type="hidden" name="idpre" value="'.$factu->idpre.'">
                                    <input type="hidden" name="cod" value="'.$cod.'">

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-times"></i>  <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                          <li> <button type="submit"> <i class="fa fa-times"></i> Borrar</button></li> 
                                        </ul> 
                                    </div>

                                  </form>   
                                </td>

                                <td class="wid230"> </td>                            
                            </tr> ';
            }
                             
            return $cadena;
        }     
    }

    public function delcod(Request $request)
    {
        $cod = $request->input('cod');
        $idpac = $request->input('idpac');

        if ( null == $cod ) {
            return redirect('Pacientes');
        }
        
        if ( null == $idpac ) {
            return redirect('Pacientes');
        }

        $presup = DB::table('presup')
                ->where('cod', $cod)
                ->where('idpac', $idpac)
                ->delete();

        $prestex = DB::table('prestex')->where('idpac', $idpac)->where('cod', $cod)->delete();
        
        return redirect("/Presup/$idpac");
    }

    public function delid(Request $request)
    {
        $idpre = $request->input('idpre');

        if ( null === $idpre ) {
            return redirect('Pacientes');
        }   

        $cod = $request->input('cod');            

        if ( null == $cod ) {
            return redirect('Pacientes');
        }       

        $presup = DB::table('presup')
                ->where('cod', $cod)
                ->first();

        if (is_null($presup)) {
            $prestex = DB::table('prestex')->where('cod', $cod)->first();
            $prestex->delete();
        }
         
        $presup = presup::find($idpre);
      
        $presup->delete();
        
        $presup = DB::table('presup')
                ->join('servicios', 'presup.idser','=','servicios.idser')
                ->select('presup.*','servicios.nomser')
                ->where('cod', $cod)
                ->orderBy('servicios.nomser' , 'ASC')
                ->get();  

        $cadena = '';

        foreach ($presup as $presu) {
            $cadena .= '<tr>
                            <td class="wid140">'.$presu->nomser.'</td>
                            <td class="wid95 textcent">'.$presu->canti.'</td>
                            <td class="wid95 textcent">'.$presu->precio.' €</td>

                            <td class="wid50">

                              <form id="delform">
                              
                                <input type="hidden" name="idpre" value="'.$presu->idpre.'">
                                <input type="hidden" name="cod" value="'.$cod.'">

                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-times"></i>  <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                      <li> <button type="submit"> <i class="fa fa-times"></i> Borrar</button></li> 
                                    </ul> 
                                </div>

                              </form>   
                            </td>

                            <td class="wid230"> </td>                            
                        </tr> ';
        }
                         
        return $cadena;
    }


}



