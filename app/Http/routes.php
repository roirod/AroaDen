<?php

use Illuminate\Support\Facades\Config;

$route = Config::get('aroaden.routes');

Route::group(['middleware' => 'web'], function () use ($route) {
	
	Route::get('/', 'Auth\AuthController@getLogin');
	Route::get('/login', 'Auth\AuthController@getLogin');
	Route::post('/login', 'Auth\AuthController@postLogin');
	Route::get('/logout', 'Auth\AuthController@getLogout');	

	Route::get('/home', 'CitasController@index');

	Route::group(['middleware' => ['admin']], function () use ($route) {
		Route::get($route["company"].'/editData', 'CompanyController@editData');
		Route::post($route["company"].'/saveData', 'CompanyController@saveData');

		Route::delete('Pacientes/{id}', 'PacientesController@destroy');

		Route::delete('Personal/{id}', 'PersonalController@destroy');

		Route::delete('Servicios/{id}', 'ServiciosController@destroy');

		Route::get('Usuarios/userEdit', 'UsuariosController@userEdit');
		Route::get('Usuarios/userDeleteViev', 'UsuariosController@userDeleteViev');
		Route::post('Usuarios/userUpdate', 'UsuariosController@userUpdate');
		Route::post('Usuarios/userDelete', 'UsuariosController@userDelete');
		Route::resource('Usuarios', 'UsuariosController', ['except' => ['create', 'update', 'edit', 'destroy']]);

	 	Route::get($route["settings"], 'SettingsController@index');
	});

	Route::group(['middleware' => ['medio']], function () use ($route) {
		Route::get('Pacientes/{id}/edit', 'PacientesController@edit');
		Route::put('Pacientes/{id}', 'PacientesController@update');
		Route::get('Pacientes/{id}/fiedit', 'PacientesController@fiedit');
		Route::put('Pacientes/{id}/fisave', 'PacientesController@fisave');
		Route::post('Pacientes/filerem', 'PacientesController@filerem');
		Route::post('Pacientes/upodog', 'PacientesController@upodog');
		Route::post('Pacientes/resodog', 'PacientesController@resodog');

		Route::get('Personal/{id}/edit', 'PersonalController@edit');
		Route::put('Personal/{id}', 'PersonalController@update');
		Route::post('Personal/filerem', 'PersonalController@filerem');

		Route::get('Servicios/{id}/edit', 'ServiciosController@edit');
		Route::put('Servicios/{id}', 'ServiciosController@update');

		Route::get('Citas/{id}/edit', 'CitasController@edit');
		Route::delete('Citas/{id}', 'CitasController@destroy');	

		Route::get($route["treatments"].'/{id}/edit', 'TreatmentsController@edit');
		Route::delete($route["treatments"].'/{id}', 'TreatmentsController@destroy');
		
		Route::post($route["budgets"].'/delcod', 'PresupuestosController@delcod');
		Route::post($route["budgets"].'/delid', 'PresupuestosController@delid');
	});

	Route::get(Config::get('aroaden.routes.company'), 'CompanyController@index');

	Route::post('Citas/list', 'CitasController@list');
	Route::get('Citas/{id}/create', 'CitasController@create');
	Route::get('Citas/{id}/edit', 'CitasController@edit');
	Route::resource('Citas', 'CitasController', ['except' => ['show']]);
	  	  
	Route::post('Pacientes/list', 'PacientesController@list');
	Route::get('Pacientes/{id}/ficha', 'PacientesController@ficha');
	Route::get('Pacientes/{id}/file', 'PacientesController@file');
	Route::post('Pacientes/upload', 'PacientesController@upload');
	Route::get('Pacientes/{id}/{file}/down', 'PacientesController@download');
	Route::get('Pacientes/{id}/odogram', 'PacientesController@odogram');	 
	Route::get('Pacientes/{id}/downodog', 'PacientesController@downodog');
	Route::get('Pacientes/{id}/presup', 'PacientesController@presup');
	Route::resource('Pacientes', 'PacientesController');

	Route::post('Personal/list', 'PersonalController@list');
	Route::get('Personal/{idper}/file', 'PersonalController@file');	 
	Route::post('Personal/upload', 'PersonalController@upload');
	Route::get('Personal/{id}/{file}/down', 'PersonalController@download');
	Route::resource('Personal', 'PersonalController');

	Route::post('Servicios/list', 'ServiciosController@list');
	Route::resource('Servicios', 'ServiciosController', ['except' => ['show']]);

	Route::get('Contable', 'ContableController@index');

	Route::post('Pagos/list', 'PagosController@list');
	Route::get('Pagos', 'PagosController@index');

	Route::get($route["budgets"].'/{id}/create', 'PresupuestosController@create');
	Route::post($route["budgets"].'/presuedit', 'PresupuestosController@presuedit');
	Route::post($route["budgets"].'/presmod', 'PresupuestosController@presmod');
	Route::resource($route["budgets"], 'PresupuestosController', ['except' => 
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

