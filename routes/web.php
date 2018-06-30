<?php

use Illuminate\Support\Facades\Config;

$route = Config::get('aroaden.routes');

Route::group(['middleware' => ['web']], function () use ($route) {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::get('/login', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/logout', 'Auth\LoginController@logout');

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

	Route::group(['middleware' => ['normal']], function () use ($route) {
		Route::get($route["patients"].'/{id}/edit', 'PatientsController@edit');
		Route::put($route["patients"].'/{id}', 'PatientsController@update');
		Route::get($route["patients"].'/{id}/recordEdit', 'PatientsController@recordEdit');
		Route::put($route["patients"].'/{id}/recordSave', 'PatientsController@recordSave');
		Route::post($route["patients"].'/fileRemove', 'PatientsController@fileRemove');
		Route::post($route["patients"].'/upodog', 'PatientsController@upodog');
		Route::post($route["patients"].'/resodog', 'PatientsController@resodog');

		Route::get($route["staff"].'/{id}/edit', 'StaffController@edit');
		Route::put($route["staff"].'/{id}', 'StaffController@update');
		Route::post($route["staff"].'/fileRemove', 'StaffController@fileRemove');

		Route::get($route["services"].'/{id}/edit', 'ServicesController@edit');
		Route::put($route["services"].'/{id}', 'ServicesController@update');

		Route::get($route["appointments"].'/{id}/edit', 'AppointmentsController@edit');
		Route::delete($route["appointments"].'/{id}', 'AppointmentsController@destroy');	

		Route::get($route["treatments"].'/{id}/edit', 'TreatmentsController@edit');
		Route::delete($route["treatments"].'/{id}', 'TreatmentsController@destroy');
		
		Route::post($route["budgets"].'/delCode', 'BudgetsController@delCode');
		Route::post($route["budgets"].'/delId', 'BudgetsController@delId');
	});

	Route::get($route["settings"].'/jsonSettings', 'SettingsController@jsonSettings');

	Route::get($route["company"].'/ajaxIndex', 'CompanyController@ajaxIndex');
	Route::get($route["company"], 'CompanyController@index');

	Route::post($route["appointments"].'/list', 'AppointmentsController@list');
	Route::get($route["appointments"].'/{id}/create', 'AppointmentsController@create');
	Route::get($route["appointments"].'/{id}/edit', 'AppointmentsController@edit');
	Route::resource($route["appointments"], 'AppointmentsController', ['except' => ['show']]);
	  	  
	Route::post($route["patients"].'/list', 'PatientsController@list');
	Route::get($route["patients"].'/{id}/record', 'PatientsController@record');
	Route::get($route["patients"].'/{id}/file', 'PatientsController@file');
	Route::get($route["patients"].'/{id}/filesList', 'PatientsController@filesList');
	Route::post($route["patients"].'/upload', 'PatientsController@upload');
	Route::post($route["patients"].'/uploadProfilePhoto', 'PatientsController@uploadProfilePhoto');
	Route::get($route["patients"].'/{id}/{file}/down', 'PatientsController@download');
	Route::get($route["patients"].'/{id}/odogram', 'PatientsController@odogram');	 
	Route::get($route["patients"].'/{id}/downodog', 'PatientsController@downodog');
	Route::get($route["patients"].'/{id}/budgets', 'PatientsController@budgets');
	Route::resource($route["patients"], 'PatientsController');

	Route::post($route["staff"].'/list', 'StaffController@list');
	Route::get($route["staff"].'/{id}/file', 'StaffController@file');
	Route::get($route["staff"].'/{id}/filesList', 'StaffController@filesList');
	Route::post($route["staff"].'/upload', 'StaffController@upload');
	Route::post($route["staff"].'/uploadProfilePhoto', 'StaffController@uploadProfilePhoto');
	Route::get($route["staff"].'/{id}/{file}/down', 'StaffController@download');
	Route::resource($route["staff"], 'StaffController');

	Route::get($route["services"].'/ajaxIndex', 'ServicesController@ajaxIndex');
	Route::get($route["services"].'/create', 'ServicesController@create');	
	Route::post($route["services"].'/list', 'ServicesController@list');
	Route::resource($route["services"], 'ServicesController', ['except' => ['show']]);

	Route::get($route["accounting"], 'AccountingController@index');

	Route::get($route["pays"].'/ajaxIndex', 'PaysController@ajaxIndex');
	Route::post($route["pays"].'/list', 'PaysController@list');
	Route::get($route["pays"], 'PaysController@index');

	Route::get($route["budgets"].'/{id}/create', 'BudgetsController@create');
	Route::post($route["budgets"].'/editBudget', 'BudgetsController@editBudget');
	Route::post($route["budgets"].'/mode', 'BudgetsController@mode');
	Route::resource($route["budgets"], 'BudgetsController', ['except' => 
		['index', 'edit', 'update', 'destroy']]);

	Route::get($route["invoices"].'/{id}/create', 'InvoicesController@create');
	Route::post($route["invoices"].'/invoicesFactory', 'InvoicesController@invoicesFactory');
	Route::resource($route["invoices"], 'InvoicesController', ['except' => 
		['index', 'update', 'edit', 'destroy']]);

	Route::get($route["treatments"].'/{id}/create','TreatmentsController@create');
	Route::post($route["treatments"].'/select', 'TreatmentsController@select');  
	Route::get($route["treatments"].'/{id}/edit', 'TreatmentsController@edit');
	Route::resource($route["treatments"], 'TreatmentsController', ['except' => ['index', 'create', 'show']]);

});

