<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Lang;

class AccountingController extends BaseController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');

        $this->views_folder = $this->config['routes']['accounting'];
        $this->form_route = 'list';
    }

    public function index(Request $request)
    {   
        $this->view_data['request'] = $request;
        $this->view_data['pays_route'] = $this->config['routes']['pays'];

        $this->setPageTitle(Lang::get('aroaden.accounting'));

        return parent::index($request); 
    }
}
