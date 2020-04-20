<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{

    public function __construct()
    {


    }

	public function handle(Login $event)
	{
	   session()->remove('url.intended');
	}

}