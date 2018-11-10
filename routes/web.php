<?php

use Illuminate\Support\Facades\Config;

$route = Config::get('aroaden.routes');

Route::group(['middleware' => ['web']], function () use ($route) {
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::get('/login', 'Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/logout', 'Auth\LoginController@logout');

	Route::get('/home', 'AppointmentsController@index');
	Route::get('/index', 'AppointmentsController@index');	

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
		Route::post($route["patients"].'/{id}', 'PatientsController@update');
		Route::get($route["patients"].'/{id}/editRecord', 'PatientsController@editRecord');
		Route::put($route["patients"].'/{id}/saveRecord', 'PatientsController@saveRecord');
		Route::delete($route["patients"].'/deleteFile/{idfiles}', 'PatientsController@deleteFile');

		Route::post($route["patients"].'/uploadOdontogram/{id}', 'PatientsController@uploadOdontogram');
		Route::put($route["patients"].'/resetOdontogram/{id}', 'PatientsController@resetOdontogram');

		Route::get($route["staff"].'/{id}/edit', 'StaffController@edit');
		Route::post($route["staff"].'/{id}', 'StaffController@update');
		Route::delete($route["staff"].'/{idfiles}/deleteFile', 'StaffController@deleteFile');

		Route::get($route["services"].'/{id}/edit', 'ServicesController@edit');
		Route::post($route["services"].'/{id}', 'ServicesController@update');

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
	  	  
	Route::get($route["patients"].'/list', 'PatientsController@list');
	Route::get($route["patients"].'/{id}/record', 'PatientsController@record');
	Route::get($route["patients"].'/{id}/file', 'PatientsController@file');
	Route::get($route["patients"].'/{id}/filesList', 'PatientsController@filesList');
	Route::post($route["patients"].'/uploadFiles/{id}', 'PatientsController@uploadFiles');
	Route::post($route["patients"].'/uploadProfilePhoto/{id}', 'PatientsController@uploadProfilePhoto');
	Route::get($route["patients"].'/{id}/{idfiles}/download', 'PatientsController@download');
	Route::get($route["patients"].'/{id}/odontogram', 'PatientsController@odontogram');	 
	Route::get($route["patients"].'/{id}/downloadOdontogram', 'PatientsController@downloadOdontogram');
	Route::resource($route["patients"], 'PatientsController');

	Route::get($route["staff"].'/search', 'StaffController@search');
	Route::get($route["staff"].'/{id}/file', 'StaffController@file');

	Route::get($route["staff"].'/{id}/filesList', 'StaffController@filesList');
	Route::post($route["staff"].'/uploadFiles/{id}', 'StaffController@uploadFiles');
	Route::get($route["staff"].'/{id}/{file}/download', 'StaffController@download');	
	Route::post($route["staff"].'/uploadProfilePhoto/{id}', 'StaffController@uploadProfilePhoto');
	Route::post($route["staff"].'/uploadFiles/{id}', 'StaffController@uploadFiles');
	Route::resource($route["staff"], 'StaffController');

	Route::resource($route["staff_positions"], 'StaffPositionsController', ['except' => ['show', 'delete']]);

	Route::get($route["services"].'/ajaxIndex', 'ServicesController@ajaxIndex');
	Route::get($route["services"].'/create', 'ServicesController@create');	
	Route::get($route["services"].'/search', 'ServicesController@search');
	Route::resource($route["services"], 'ServicesController', ['except' => ['show']]);

	Route::get($route["accounting"], 'AccountingController@index');

	Route::get($route["pays"].'/ajaxIndex', 'PaysController@ajaxIndex');
	Route::get($route["pays"].'/list', 'PaysController@list');
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

