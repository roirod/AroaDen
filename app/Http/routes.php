<?php

Route::group(['middleware' => 'web'], function () {
	
	 Route::get('/', 'Auth\AuthController@getLogin');
	 Route::get('/login', 'Auth\AuthController@getLogin');
	 Route::post('/login', 'Auth\AuthController@postLogin');
	 Route::get('/logout', 'Auth\AuthController@getLogout');	

	 Route::get('/home', 'CitasController@index');


	 Route::group(['middleware' => 'admin'], function () {

	 	Route::get('Empresa/editData', 'EmpresaController@editData');
	 	Route::post('Empresa/saveData', 'EmpresaController@saveData');

	 	Route::get('Pacientes/{id}/del', 'PacientesController@del');
	 	Route::delete('Pacientes/{id}', 'PacientesController@destroy');

	 	Route::get('Personal/{id}/del', 'PersonalController@del');
	 	Route::delete('Personal/{id}', 'PersonalController@destroy');

	 	Route::get('Servicios/{id}/del', 'ServiciosController@del');
	 	Route::delete('Servicios/{id}', 'ServiciosController@destroy');

	 	Route::get('Usuarios/usuedit', 'UsuariosController@usuedit');
	 	Route::get('Usuarios/usudel', 'UsuariosController@usudel');
	 	Route::post('Usuarios/saveup', 'UsuariosController@saveup');
	 	Route::post('Usuarios/delete', 'UsuariosController@delete');
	 	Route::resource('Usuarios', 'UsuariosController', ['except' => ['index', 'update', 'edit', 'destroy']]);
	 });

	 Route::group(['middleware' => 'medio'], function () {

	 	Route::get('Pacientes/{id}/edit', 'PacientesController@edit');
	 	Route::put('Pacientes/{idpac}', 'PacientesController@update');
	 	Route::get('Pacientes/{idpac}/fiedit', 'PacientesController@fiedit');
	 	Route::put('Pacientes/{idpac}/fisave', 'PacientesController@fisave');
	 	Route::post('Pacientes/filerem', 'PacientesController@filerem');
		Route::post('Pacientes/upodog', 'PacientesController@upodog');
		Route::post('Pacientes/resodog', 'PacientesController@resodog');

		Route::get('Personal/{id}/edit', 'PersonalController@edit');
		Route::put('Personal/{id}', 'PersonalController@update');
	 	Route::post('Personal/filerem', 'PersonalController@filerem');

	 	Route::get('Servicios/{id}/edit', 'ServiciosController@edit');
	 	Route::put('Servicios/{id}', 'ServiciosController@update');

		Route::get('Citas/{idpac}/{idcit}/edit', 'CitasController@edit');
		Route::get('Citas/{idpac}/{idcit}/del', 'CitasController@del');
		Route::delete('Citas/{idcit}', 'CitasController@destroy');	

		Route::get('Trapac/{idpac}/{idtra}/edit', 'TratamientosController@edit');
		Route::get('Trapac/{idpac}/{idtra}/del', 'TratamientosController@del');
		Route::delete('Trapac/{idtra}', 'TratamientosController@destroy');	

		Route::post('Presup/delcod', 'PresupuestosController@delcod');
		Route::post('Presup/delid', 'PresupuestosController@delid');
	 });


	 Route::get('Empresa', 'EmpresaController@index');
	 
	 Route::post('Citas/list', 'CitasController@list');
	 Route::get('Citas/{idpac}/create', 'CitasController@create');
	 Route::resource('Citas', 'CitasController', ['except' => ['show']]);
	  	  
	 Route::post('Pacientes/list', 'PacientesController@list');
	 Route::get('Pacientes/{idpac}/ficha', 'PacientesController@ficha');
	 Route::get('Pacientes/{idpac}/file', 'PacientesController@file');
	 Route::post('Pacientes/upload', 'PacientesController@upload');
	 Route::get('Pacientes/{idpac}/{file}/down', 'PacientesController@download');
	 Route::get('Pacientes/{idpac}/odogram', 'PacientesController@odogram');	 
	 Route::get('Pacientes/{idpac}/downodog', 'PacientesController@downodog');
	 Route::get('Pacientes/{idpac}/presup', 'PacientesController@presup');
	 Route::resource('Pacientes', 'PacientesController');

	 Route::post('Personal/list', 'PersonalController@list');
	 Route::get('Personal/{idper}/file', 'PersonalController@file');	 
	 Route::post('Personal/upload', 'PersonalController@upload');
	 Route::get('Personal/{idper}/{file}/down', 'PersonalController@download');
	 Route::resource('Personal', 'PersonalController');

	 Route::post('Servicios/list', 'ServiciosController@list');
	 Route::resource('Servicios', 'ServiciosController');

	 Route::resource('Contable', 'ContableController');

	 Route::resource('Pagos', 'PagosController@index');

	 Route::get('Settings', 'SettingsController@index');
	 
	 Route::get('Presup/{idpac}/create', 'PresupuestosController@create');
	 Route::post('Presup/presuedit', 'PresupuestosController@presuedit');
	 Route::post('Presup/presmod', 'PresupuestosController@presmod');
	 Route::resource('Presup', 'PresupuestosController', ['except' => ['index', 'update', 'edit', 'destroy']]);

	 Route::post('Trapac/crea','TratamientosController@crea');
	 Route::post('Trapac/selcrea', 'TratamientosController@selcrea'); 
	 Route::resource('Trapac', 'TratamientosController', ['except' => ['index', 'create', 'show']]);

});
