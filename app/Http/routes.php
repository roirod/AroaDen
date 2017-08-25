<?php

Route::group(['middleware' => 'web'], function () {
	
	Route::get('/', 'Auth\AuthController@getLogin');
	Route::get('/login', 'Auth\AuthController@getLogin');
	Route::post('/login', 'Auth\AuthController@postLogin');
	Route::get('/logout', 'Auth\AuthController@getLogout');	

	Route::get('/home', 'CitasController@index');

	Route::group(['middleware' => ['admin']], function () {
		Route::get('Empresa/editData', 'EmpresaController@editData');
		Route::post('Empresa/saveData', 'EmpresaController@saveData');

		Route::delete('Pacientes/{id}', 'PacientesController@destroy');

		Route::delete('Personal/{id}', 'PersonalController@destroy');

		Route::delete('Servicios/{id}', 'ServiciosController@destroy');

		Route::get('Usuarios/userEdit', 'UsuariosController@userEdit');
		Route::get('Usuarios/userDeleteViev', 'UsuariosController@userDeleteViev');
		Route::post('Usuarios/userUpdate', 'UsuariosController@userUpdate');
		Route::post('Usuarios/userDelete', 'UsuariosController@userDelete');
		Route::resource('Usuarios', 'UsuariosController', ['except' => ['create', 'update', 'edit', 'destroy']]);

	 	Route::get('Settings', 'SettingsController@index');
	});

	Route::group(['middleware' => ['medio']], function () {
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

		Route::get('Trapac/{id}/edit', 'TratamientosController@edit');
		Route::delete('Trapac/{id}', 'TratamientosController@destroy');
		
		Route::post('Presup/delcod', 'PresupuestosController@delcod');
		Route::post('Presup/delid', 'PresupuestosController@delid');
	});

	Route::get('Empresa', 'EmpresaController@index');

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

	Route::get('Presup/{id}/create', 'PresupuestosController@create');
	Route::post('Presup/presuedit', 'PresupuestosController@presuedit');
	Route::post('Presup/presmod', 'PresupuestosController@presmod');
	Route::resource('Presup', 'PresupuestosController', ['except' => ['index', 'update', 'edit', 'destroy']]);

	Route::get('Trapac/{id}/create','TratamientosController@create');
	Route::post('Trapac/select', 'TratamientosController@select');  
	Route::get('Trapac/{id}/edit', 'TratamientosController@edit');
	Route::resource('Trapac', 'TratamientosController', ['except' => ['index', 'create', 'show']]);

});

