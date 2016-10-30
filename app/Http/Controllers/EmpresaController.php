<?php

namespace App\Http\Controllers;

use DB;
use App\empre;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {  
       $empre = DB::table('empre')->where('id', '1')->first();

       return view('emp.index', [
            'request' => $request,
            'empre' => $empre
        ]);
    }

    public function create()
    { }

    public function store(Request $request)
    { }

    public function show($id)
    { }
  
    public function edit(Request $request,$id)
    {
        $empre = DB::table('empre')->where('id', '1')->first();

        return view('emp.edit', [
            'request' => $request,
            'empre' => $empre
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
                'nom' => 'required|max:111',
                'direc' => 'max:111',
                'pobla' => 'max:111',
                'nif' => 'max:22',
                'tel1' => 'max:11',
                'tel2' => 'max:11',
                'tel3' => 'max:11',                
                'notas' => '',
                'factutex' => '',
                'presutex' => ''
        ]);
            
        if ($validator->fails()) {
             return redirect("Empresa/1/edit")
                         ->withErrors($validator)
                         ->withInput();
        } else {        

            $empre = empre::find('1');
                    
            $nom = ucfirst(strtolower( $request->input('nom') ) );
            $direc = ucfirst(strtolower( $request->input('direc') ) );
            $pobla = ucfirst(strtolower( $request->input('pobla') ) );
            $notas = ucfirst(strtolower( $request->input('notas') ) );
            $factutex = ucfirst(strtolower( $request->input('factutex') ) );   
            $presutex = ucfirst(strtolower( $request->input('presutex') ) );           

            $empre->nom = htmlentities (trim($nom),ENT_QUOTES,"UTF-8");
            $empre->direc = htmlentities (trim($direc),ENT_QUOTES,"UTF-8");
            $empre->pobla = htmlentities (trim($pobla),ENT_QUOTES,"UTF-8");            
            $empre->nif = htmlentities (trim($request->input('nif')),ENT_QUOTES,"UTF-8");
            $empre->tel1 = htmlentities (trim($request->input('tel1')),ENT_QUOTES,"UTF-8");
            $empre->tel2 = htmlentities (trim($request->input('tel2')),ENT_QUOTES,"UTF-8");
            $empre->tel3 = htmlentities (trim($request->input('tel3')),ENT_QUOTES,"UTF-8");
            $empre->notas = htmlentities (trim($notas),ENT_QUOTES,"UTF-8");
            $empre->factutex = htmlentities (trim($factutex),ENT_QUOTES,"UTF-8");
            $empre->presutex = htmlentities (trim($presutex),ENT_QUOTES,"UTF-8");

            $empre->save();

            $request->session()->flash('sucmess', 'Hecho!!!');

            return redirect("/Empresa");
        }   
    }

    public function destroy($id)
    { }
}
