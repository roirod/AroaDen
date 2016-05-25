<?php

Route::group(['middleware' => 'web'], function () {
	
	 Route::get('/', 'Auth\AuthController@getLogin');
	 Route::get('/login', 'Auth\AuthController@getLogin');
	 Route::post('/login', 'Auth\AuthController@postLogin');
	 Route::get('/logout', 'Auth\AuthController@getLogout');	

	 Route::get('/home', 'CitasController@index');

	 Route::group(['middleware' => 'admin'], function () {
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
	 	Route::resource('Usuarios', 'UsuariosController');
	 });
	 
	 Route::resource('Empresa', 'EmpresaController');
	 
	 Route::post('Citas/ver', 'CitasController@ver');
	 Route::get('Citas/{idpac}/create', 'CitasController@create');
	 Route::resource('Citas', 'CitasController');
	  	  
	 Route::post('Pacientes/ver', 'PacientesController@ver');
	 Route::get('Pacientes/{idpac}/file', 'PacientesController@file');
	 Route::post('Pacientes/filerem', 'PacientesController@filerem');
	 Route::post('Pacientes/upload', 'PacientesController@upload');
	 Route::get('Pacientes/{idpac}/{file}/down', 'PacientesController@download');
	 Route::get('Pacientes/{idpac}/odogram', 'PacientesController@odogram');	 
	 Route::get('Pacientes/{idpac}/downodog', 'PacientesController@downodog');
	 Route::post('Pacientes/upodog', 'PacientesController@upodog');
	 Route::post('Pacientes/resodog', 'PacientesController@resodog');
	 Route::get('Pacientes/{idpac}/presup', 'PacientesController@presup');
	 Route::resource('Pacientes', 'PacientesController');

	 Route::post('Personal/ver', 'PersonalController@ver');
	 Route::get('Personal/{idper}/file', 'PersonalController@file');
	 Route::post('Personal/filerem', 'PersonalController@filerem');
	 Route::post('Personal/upload', 'PersonalController@upload');
	 Route::get('Personal/{idpac}/{file}/down', 'PersonalController@download');
	 Route::resource('Personal', 'PersonalController');

	 Route::post('Servicios/ver', 'ServiciosController@ver');
	 Route::resource('Servicios', 'ServiciosController');

	 Route::resource('Contable', 'ContableController');

	 Route::resource('Pagos', 'PagosController@index');

	 Route::get('Ajustes', 'AjustesController@index');
	 
	 Route::get('Presup/{idpac}/create', 'PresupuestosController@create');
	 Route::resource('Presup', 'PresupuestosController');

	 Route::post('Trapac/crea','TratamientosController@crea');
	 Route::post('Trapac/selcrea', 'TratamientosController@selcrea');
	 Route::resource('Trapac', 'TratamientosController');
	     
	 Route::get('Test', 'TestController@test');
});
