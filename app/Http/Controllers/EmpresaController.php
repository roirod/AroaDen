<?php

namespace App\Http\Controllers;

use DB;
use App\Settings;

use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;

class EmpresaController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function index(Request $request)
    { 
		$empre = Settings::select('key', 'value')->get()->toArray();

        $obj = new class{};

        foreach ($empre as $arr => $value) {
            $obj_key = $value["key"];

            $obj->$obj_key = $value["value"];
        }

		return view('emp.index', [
		   'request' => $request,
		   'empre' => $obj
		]);
    }

    public function editData(Request $request)
    {
        $empre = Settings::select('key', 'value')->get()->toArray();

        $obj = new class{};

        foreach ($empre as $arr => $value) {
            $obj_key = $value["key"];

            $obj->$obj_key = $value["value"];
        }

        return view('emp.edit', [
            'request' => $request,
            'empre' => $obj
        ]);
    }

    public function saveData(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'empre_nom' => 'required|max:111',
            'empre_direc' => 'max:111',
            'empre_pobla' => 'max:111',
            'empre_nif' => 'max:22',
            'empre_tel1' => 'max:11',
            'empre_tel2' => 'max:11',
            'empre_tel3' => 'max:11',                
            'empre_notas' => '',
            'factutex' => '',
            'presutex' => ''
        ]);
            
        if ($validator->fails()) {
            return redirect("Empresa/editData")
                     ->withErrors($validator)
                     ->withInput();
        } else {        

            $empre = Settings::select('key', 'value')->get()->toArray();

            foreach ($empre as $arr => $value) {
                foreach ($request->input() as $request_key => $request_value) {
                    $request_value = ucfirst( strtolower($request_value) );
                    $request_value = htmlentities (trim($request_value),ENT_QUOTES,"UTF-8");

                    if ($value["key"] == $request_key) {
                        Settings::where('key', $request_key)->update(['value' => $request_value]);
                    }
                }
            }

            $request->session()->flash('sucmess', 'Hecho!!!');

            return redirect("/Empresa");
        }
    }

}
