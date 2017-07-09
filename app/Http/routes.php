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

	 	Route::get('Usuarios/userEdit', 'UsuariosController@userEdit');
	 	Route::get('Usuarios/userDeleteViev', 'UsuariosController@userDeleteViev');
	 	Route::post('Usuarios/userUpdate', 'UsuariosController@userUpdate');
	 	Route::post('Usuarios/userDelete', 'UsuariosController@userDelete');
	 	Route::resource('Usuarios', 'UsuariosController', ['except' => ['index', 'update', 'edit', 'destroy']]);
	 });

	 Route::group(['middleware' => 'medio'], function () {

	 	Route::get('Pacientes/{id}/{idcit?}/edit', 'PacientesController@edit');
	 	Route::put('Pacientes/{id}', 'PacientesController@update');
	 	Route::get('Pacientes/{id}/fiedit', 'PacientesController@fiedit');
	 	Route::put('Pacientes/{id}/fisave', 'PacientesController@fisave');
	 	Route::post('Pacientes/filerem', 'PacientesController@filerem');
		Route::post('Pacientes/upodog', 'PacientesController@upodog');
		Route::post('Pacientes/resodog', 'PacientesController@resodog');

	 	Route::get('Personal/{id}/{idcit?}/edit', 'PersonalController@edit');
		Route::put('Personal/{id}', 'PersonalController@update');
	 	Route::post('Personal/filerem', 'PersonalController@filerem');

	 	Route::get('Servicios/{id}/{idcit?}/edit', 'ServiciosController@edit');
	 	Route::put('Servicios/{id}', 'ServiciosController@update');

		Route::get('Citas/{id}/{idcit}/edit', 'CitasController@edit');
		Route::get('Citas/{id}/{idcit}/del', 'CitasController@del');
		Route::delete('Citas/{idcit}', 'CitasController@destroy');	

		Route::get('Trapac/{id}/{idtra}/edit', 'TratamientosController@edit');
		Route::get('Trapac/{id}/{idtra}/del', 'TratamientosController@del');
		Route::delete('Trapac/{id}', 'TratamientosController@destroy');	

		Route::post('Presup/delcod', 'PresupuestosController@delcod');
		Route::post('Presup/delid', 'PresupuestosController@delid');
	 });


	 Route::get('Empresa', 'EmpresaController@index');
	 
	 Route::post('Citas/list', 'CitasController@list');
	 Route::get('Citas/{id}/create', 'CitasController@create');
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
	 Route::get('Personal/{idper}/{file}/down', 'PersonalController@download');
	 Route::resource('Personal', 'PersonalController');

	 Route::post('Servicios/list', 'ServiciosController@list');
	 Route::resource('Servicios', 'ServiciosController', ['except' => ['show']]);

	 Route::resource('Contable', 'ContableController');

	 Route::resource('Pagos', 'PagosController@index');

	 Route::get('Settings', 'SettingsController@index');
	 
	 Route::get('Presup/{id}/create', 'PresupuestosController@create');
	 Route::post('Presup/presuedit', 'PresupuestosController@presuedit');
	 Route::post('Presup/presmod', 'PresupuestosController@presmod');
	 Route::resource('Presup', 'PresupuestosController', ['except' => ['index', 'update', 'edit', 'destroy']]);

	 Route::post('Trapac/crea','TratamientosController@crea');
	 Route::post('Trapac/selcrea', 'TratamientosController@selcrea'); 
	 Route::resource('Trapac', 'TratamientosController', ['except' => ['index', 'create', 'show']]);

});
