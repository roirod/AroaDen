<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class ContableController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->main_route = 'Contable';
        $this->form_route = 'list';        
        $this->views_folder = 'conta';        
    }

    public function index(Request $request)
    {   
        $this->page_title = Lang::get('aroaden.patients').' - '.$this->page_title;

        $this->passVarsToViews();

        $this->view_data['request'] = $request;

        return parent::index($request); 
    }
}
