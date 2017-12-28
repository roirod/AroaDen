<?php

use Illuminate\Support\Facades\Config;

$route = Config::get('aroaden.routes');

Route::group(['middleware' => 'web'], function () use ($route) {
	
	Route::get('/', 'Auth\AuthController@getLogin');
	Route::get('/login', 'Auth\AuthController@getLogin');
	Route::post('/login', 'Auth\AuthController@postLogin');
	Route::get('/logout', 'Auth\AuthController@getLogout');	

	Route::get('/home', 'AppointmentsController@index');

	Route::group(['middleware' => ['admin']], function () use ($route) {
		Route::get($route["company"].'/editData', 'CompanyController@editData');
		Route::post($route["company"].'/saveData', 'CompanyController@saveData');

		Route::delete($route["patients"].'/{id}', 'PatientsController@destroy');

		Route::delete($route["staff"].'/{id}', 'StaffController@destroy');

		Route::delete($route["services"].'/{id}', 'ServicesController@destroy');

		Route::get($route["users"].'/userEdit', 'UsersController@userEdit');
		Route::get($route["users"].'/userDeleteViev', 'UsersController@userDeleteViev');
		Route::post($route["users"].'/userUpdate', 'UsersController@userUpdate');
		Route::post($route["users"].'/userDelete', 'UsersController@userDelete');
		Route::resource($route["users"], 'UsersController', ['except' => ['create', 'update', 'edit', 'destroy']]);

	 	Route::get($route["settings"], 'SettingsController@index');
	});

	Route::group(['middleware' => ['medio']], function () use ($route) {
		Route::get($route["patients"].'/{id}/edit', 'PatientsController@edit');
		Route::put($route["patients"].'/{id}', 'PatientsController@update');
<<<<<<< HEAD
		Route::get($route["patients"].'/{id}/fiedit', 'PatientsController@fiedit');
		Route::put($route["patients"].'/{id}/fisave', 'PatientsController@fisave');
=======
		Route::get($route["patients"].'/{id}/recordEdit', 'PatientsController@recordEdit');
		Route::put($route["patients"].'/{id}/recordSave', 'PatientsController@recordSave');
>>>>>>> feature/invoices-module
		Route::post($route["patients"].'/filerem', 'PatientsController@filerem');
		Route::post($route["patients"].'/upodog', 'PatientsController@upodog');
		Route::post($route["patients"].'/resodog', 'PatientsController@resodog');

		Route::get($route["staff"].'/{id}/edit', 'StaffController@edit');
		Route::put($route["staff"].'/{id}', 'StaffController@update');
		Route::post($route["staff"].'/filerem', 'StaffController@filerem');

		Route::get($route["services"].'/{id}/edit', 'ServicesController@edit');
		Route::put($route["services"].'/{id}', 'ServicesController@update');

		Route::get($route["appointments"].'/{id}/edit', 'AppointmentsController@edit');
		Route::delete($route["appointments"].'/{id}', 'AppointmentsController@destroy');	

		Route::get($route["treatments"].'/{id}/edit', 'TreatmentsController@edit');
		Route::delete($route["treatments"].'/{id}', 'TreatmentsController@destroy');
		
		Route::post($route["budgets"].'/delcod', 'BudgetsController@delcod');
		Route::post($route["budgets"].'/delid', 'BudgetsController@delid');
	});

	Route::get(Config::get('aroaden.routes.company'), 'CompanyController@index');

	Route::post($route["appointments"].'/list', 'AppointmentsController@list');
	Route::get($route["appointments"].'/{id}/create', 'AppointmentsController@create');
	Route::get($route["appointments"].'/{id}/edit', 'AppointmentsController@edit');
	Route::resource($route["appointments"], 'AppointmentsController', ['except' => ['show']]);
	  	  
	Route::post($route["patients"].'/list', 'PatientsController@list');
<<<<<<< HEAD
	Route::get($route["patients"].'/{id}/ficha', 'PatientsController@ficha');
=======
	Route::get($route["patients"].'/{id}/record', 'PatientsController@record');
>>>>>>> feature/invoices-module
	Route::get($route["patients"].'/{id}/file', 'PatientsController@file');
	Route::post($route["patients"].'/upload', 'PatientsController@upload');
	Route::get($route["patients"].'/{id}/{file}/down', 'PatientsController@download');
	Route::get($route["patients"].'/{id}/odogram', 'PatientsController@odogram');	 
	Route::get($route["patients"].'/{id}/downodog', 'PatientsController@downodog');
<<<<<<< HEAD
	Route::get($route["patients"].'/{id}/presup', 'PatientsController@presup');
=======
	Route::get($route["patients"].'/{id}/budgets', 'PatientsController@budgets');
>>>>>>> feature/invoices-module
	Route::resource($route["patients"], 'PatientsController');

	Route::post($route["staff"].'/list', 'StaffController@list');
	Route::get($route["staff"].'/{idper}/file', 'StaffController@file');	 
	Route::post($route["staff"].'/upload', 'StaffController@upload');
	Route::get($route["staff"].'/{id}/{file}/down', 'StaffController@download');
	Route::resource($route["staff"], 'StaffController');

	Route::post($route["services"].'/list', 'ServicesController@list');
	Route::resource($route["services"], 'ServicesController', ['except' => ['show']]);

	Route::get($route["accounting"], 'AccountingController@index');

<<<<<<< HEAD
	Route::post($route["pays"].'/list', 'PaysController@list');
=======
	Route::post($route["pays"].'/index', 'PaysController@index');
>>>>>>> feature/invoices-module
	Route::get($route["pays"], 'PaysController@index');

	Route::get($route["budgets"].'/{id}/create', 'BudgetsController@create');
	Route::post($route["budgets"].'/presuedit', 'BudgetsController@presuedit');
	Route::post($route["budgets"].'/presmod', 'BudgetsController@presmod');
	Route::resource($route["budgets"], 'BudgetsController', ['except' => 
		['index', 'update', 'edit', 'destroy']]);

	Route::get($route["invoices"].'/{id}/create', 'InvoicesController@create');
	Route::post($route["invoices"].'/invoicesFactory', 'InvoicesController@invoicesFactory');
	Route::resource($route["invoices"], 'InvoicesController', ['except' => 
		['index', 'update', 'edit', 'destroy']]);

	Route::get($route["treatments"].'/{id}/create','TreatmentsController@create');
	Route::post($route["treatments"].'/select', 'TreatmentsController@select');  
	Route::get($route["treatments"].'/{id}/edit', 'TreatmentsController@edit');
	Route::resource($route["treatments"], 'TreatmentsController', ['except' => ['index', 'create', 'show']]);

});

