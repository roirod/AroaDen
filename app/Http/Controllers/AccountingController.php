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

        $this->main_route = $this->config['routes']['pays'];
    }

    public function index(Request $request)
    {
        return redirect($this->main_route);
    }
}
