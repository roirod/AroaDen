<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Personal;

use Carbon\Carbon;
use Storage;
use Validator;
use Illuminate\Http\Request;
use Lang;
use App\Interfaces\BaseInterface;

class PersonalController extends BaseController implements BaseInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Personal';
        $this->views_folder = 'per';

        $fields = [
            'surname' => true,
            'name' => true,
            'position' => true,
            'address' => true,
            'city' => true,
            'birth' => true,
            'dni' => true,
            'tel1' => true,
            'tel2' => true,
            'notes' => true,
            'save' => true,
        ];

        $this->form_fields = array_replace($this->form_fields, $fields);
    }

    public function index(Request $request)
    {   
        $numpag = 30;

        $main_loop = DB::table('personal')
                    ->whereNull('deleted_at')
                    ->orderBy('surname', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->paginate($numpag);

        $count = DB::table('personal')
                    ->whereNull('deleted_at')
                    ->count();

        return view($this->views_folder.'.index', [
          'personal' => $main_loop,
          'request' => $request,
          'count' => $count          
        ]);   
    }
  
    public function list(Request $request)
    {                                    
        $busca = $this->sanitizeData($request->input('busca'));
        $busen = $this->sanitizeData($request->input('busen'));

        $data = $this->getResults($busen, $busca);

        header('Content-type: application/json; charset=utf-8');

        echo json_encode($data);

        exit();
    } 

    public function show(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);
          
        $this->createDir($id);

        $personal = Personal::where('idper', $id)
                    ->whereNull('deleted_at')
                    ->first();

        if (is_null($personal)) {
            $request->session()->flash($this->error_message_name, 'Has borrado a este profesional.');    
            return redirect($this->main_route);
        }
                       
        $trabajos = DB::table('tratampacien')
                ->join('pacientes', 'tratampacien.idpac','=','pacientes.idpac')
                ->join('servicios', 'tratampacien.idser','=','servicios.idser')
                ->select('tratampacien.*','pacientes.surname','pacientes.name','servicios.name as servicio_name')
                ->whereNull('pacientes.deleted_at')
                ->where('per1', $id)
                ->orWhere('per2', $id)
                ->orderBy('date' , 'DESC')
                ->get();    
            
        return view($this->views_folder.'.show', [
            'request' => $request,
            'personal' => $personal,
            'trabajos' => $trabajos,
            'idper' => $id
        ]);       
    }

    public function create(Request $request, $id = false)
    {     
        $this->view_data = [
            'request' => $request,
            'form_fields' => $this->form_fields
        ];

        return parent::create($request, $id);  
    }

    public function store(Request $request)
    {
        $dni = $this->sanitizeData($request->input('dni'));

        $person = DB::table('personal')
                        ->where('dni', $dni)
                        ->first();

        if ($person != null) {
            $messa = 'Repetido. El dni: '.$dni.', pertenece a: '.$person->surname.', '.$person->name;
            $request->session()->flash($this->error_message_name, $messa);
            return redirect("/$this->main_route/create")->withInput();
        }

        $validator = Validator::make($request->all(),[
           'name' => 'required|max:111',
           'surname' => 'required|max:111',
           'dni' => 'unique:personal|max:12',
           'tel1' => 'max:11',
           'tel2' => 'max:11',
           'position' => 'max:66',
           'address' => 'max:111',
           'city' => 'max:111',
           'birth' => 'date',
           'notes' => ''
        ]);
            
        if ($validator->fails()) {
             return redirect("/$this->main_route/create")
                         ->withErrors($validator)
                         ->withInput();
        } else {

            $name = ucfirst(strtolower( $request->input('name') ) );
            $surname = ucwords(strtolower( $request->input('surname') ) );
            $address = ucfirst(strtolower( $request->input('address') ) );
            $city = ucfirst(strtolower( $request->input('city') ) );
            $notes = ucfirst(strtolower( $request->input('notes') ) );
            
            $name = $this->sanitizeData($name);
            $surname = $this->sanitizeData($surname);
            $position = $this->sanitizeData($request->input('position'));
            $dni = $this->sanitizeData($request->input('dni'));
            $tel1 = $this->sanitizeData($request->input('tel1'));
            $tel2 = $this->sanitizeData($request->input('tel2'));
            $address = $this->sanitizeData($address);
            $city = $this->sanitizeData($city);
            $birth = $this->sanitizeData($request->input('birth'));
            $notes = $this->sanitizeData($notes);
                        
            Personal::create([
              'name' => $name,
              'surname' => $surname,
              'dni' => $dni,
              'tel1' => $tel1,
              'tel2' => $tel2,
              'position' => $position,
              'address' => $address,
              'city' => $city,
              'birth' => $birth,
              'notes' => $notes
            ]);
          
            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );  
                        
            return redirect("/$this->main_route/create");
        }      
    }
  
    public function edit(Request $request, $id, $idcit = false)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);
         
        $personal = Personal::find($id); 

        return view($this->views_folder.'.edit', [
          'request' => $request,
          'personal' => $personal,
          'idper' => $id
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);
        $dni = $this->sanitizeData($request->input('dni'));

        $person = Personal::find($id); 
       
        $exists = Personal::where('idper', '!=', $id)
                        ->where('dni', $person->dni)
                        ->first();
              
        if ($exists == null) {
            $messa = 'Repetido. El dni: '.$dni.', pertenece a: '.$person->surname.', '.$person->name;
            $request->session()->flash($this->error_message_name, $messa);
            return redirect("/$this->main_route/$id/edit")->withInput();
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required|max:111',
            'surname' => 'required|max:111',
            'dni' => 'required|max:12',
            'tel1' => 'max:11',
            'tel2' => 'max:11',
            'position' => 'max:66',
            'notes' => '',
            'address' => 'max:111',
            'city' => 'max:111',
            'birth' => 'date'
        ]);
            
        if ($validator->fails()) {
             return redirect("/$this->main_route/$id/edit")
                         ->withErrors($validator)
                         ->withInput();
        } else {        

            $birth = $request->input('birth');
                      
            if ( $this->validateDate($birth) ) {
               $request->session()->flash($this->error_message_name, 'Fecha/s incorrecta');
               return redirect("/$this->main_route/$id/edit");
            }
          
            $id = $this->sanitizeData($id);
            
            $personal = Personal::find($id);
                    
            $name = ucfirst(strtolower( $request->input('name') ) );
            $surname = ucwords(strtolower( $request->input('surname') ) );
            $notes = ucfirst(strtolower( $request->input('notes') ) );
            $address = ucfirst(strtolower( $request->input('address') ) );
            $city = ucfirst(strtolower( $request->input('city') ) );
            
            $personal->name = $this->sanitizeData($name);
            $personal->surname = $this->sanitizeData($surname);
            $personal->dni = $this->sanitizeData($request->input('dni'));
            $personal->tel1 = $this->sanitizeData($request->input('tel1'));
            $personal->tel2 = $this->sanitizeData($request->input('tel2'));
            $personal->position = $this->sanitizeData($request->input('position'));
            $personal->notes = $this->sanitizeData($notes);
            $personal->address = $this->sanitizeData($address);
            $personal->city = $this->sanitizeData($city);
            $personal->birth = $this->sanitizeData($request->input('birth'));
            
            $personal->save();

            $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );

            return redirect("/$this->main_route/$id");
        }   
    }

    public function file(Request $request, $id)
    {
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $this->createDir($id);

        $files = Storage::files("/perdir/$id/");

        $url = url("$this->main_route/$id");
        
        return view($this->views_folder.'.file', [
          'request' => $request,
          'idper' => $id,
          'files' => $files,
          'url' => $url
        ]);
    }

    public function upload(Request $request)
    {
        $id = $request->input('idper');

        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $perdir = storage_path("app/perdir/$id");
         
        $files = $request->file('files');
          
        $ficount = count($files);
        $upcount = 0;

        foreach ($files as $file) {                       
            $filename = $file->getClientOriginalName();
            $size = $file->getClientSize();

            $max = 1024 * 1024 * 22;

            $filedisk = storage_path("app/perdir/$id/$filename");

            if ( $size > $max ) {
                $mess = "El archivo: - $filename - es superior a 22 MB";
                $request->session()->flash($this->error_message_name, $mess);
                return redirect("/$this->main_route/$id/file");
            }                

            if ( file_exists($filedisk) ) {
                $mess = "El archivo: $filename -- existe ya en su carpeta";
                $request->session()->flash($this->error_message_name, $mess);
                return redirect("/$this->main_route/$id/file");

            } else {
                $file->move($perdir, $filename);
                $upcount ++;
            }
        }  
        
        if($upcount == $ficount){
            return redirect("/$this->main_route/$id/file");
        } else {
            $request->session()->flash($this->error_message_name, 'error!!!');
            return redirect("/$this->main_route/$id/file");
        }
    }
    
    public function download(Request $request, $id, $file)
    {   
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);

        $perdir = storage_path("app/perdir/$id");

        $filedown = $perdir.'/'.$file;

        return response()->download($filedown);
    } 

    public function filerem(Request $request)
    {
        $id = $request->input('idper');

        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);     

        $perdir = storage_path("app/perdir/$id");

        $file = $request->input('filerem');

        $filerem = $perdir.'/'.$file;
          
        unlink($filerem);   
          
        return redirect("/$this->main_route/$id/file");
    }  
   
    public function del(Request $request, $id)
    {
        $this->redirectIfIdIsNull($idser, $this->main_route);

        $id = $this->sanitizeData($id);

        $personal = Personal::find($id);
         
        return view($this->views_folder.'.del', [
          'request' => $request,
          'personal' => $personal,
          'idper' => $id
        ]);
    }
 
    public function destroy(Request $request, $id)
    {      
        $this->redirectIfIdIsNull($id, $this->main_route);

        $id = $this->sanitizeData($id);    
        
        Personal::destroy($id);     

        $request->session()->flash($this->success_message_name, Lang::get('aroaden.success_message') );
        
        return redirect($this->main_route);
    }

    public function createDir($id)
    {               
        $perdir = "/perdir/$id/";

        if ( ! Storage::exists($perdir) ) { 
            Storage::makeDirectory($perdir, 0770, true);
        }

        $thumbdir = $perdir.'/.thumbdir';

        if ( ! Storage::exists($thumbdir) ) { 
            Storage::makeDirectory($thumbdir, 0770, true);
        }
    }

    private function getResults($busen, $busca)
    {
        $count = DB::table('personal')
                    ->whereNull('deleted_at')
                    ->count();

        if ($count === 0) {
            $data['main_loop'] = false;
            $data['count'] = false;       
            $data['msg'] = ' No hay personal en la base de datos. ';

            return $data;
        }

        $main_loop = DB::table('personal')
                    ->select('idper', 'surname', 'name', 'dni', 'tel1', 'position')
                    ->whereNull('deleted_at')
                    ->where($busen,'LIKE','%'.$busca.'%')
                    ->orderBy('surname','ASC')
                    ->orderBy('name','ASC')
                    ->get();

        $count = DB::table('personal')
                    ->select('idper', 'surname', 'name', 'dni', 'tel1', 'position')
                    ->whereNull('deleted_at')
                    ->where($busen,'LIKE','%'.$busca.'%')
                    ->count();      
                    
        return $this->getOutputData($main_loop, $count);
    }

    private function getOutputData($main_loop, $count)
    {
        $data = [];

        if ($count == 0) {
            $data['main_loop'] = false;
            $data['count'] = false;       
            $data['msg'] = ' No hay resultados. ';
        } else {
            $data['main_loop'] = $main_loop;
            $data['count'] = $count;        
            $data['msg'] = false;
        }

        return $data;
    }
}
