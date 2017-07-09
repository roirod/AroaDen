@extends('layouts.main')

@section('content')

@include('includes.messages')

@if( $username === 'admin' )

	<div class="row">  <div class="col-sm-12 mar10">
	 <div class="input-group"> <span class="input-group-btn pad4"> <p> Usuario:</p> </span>	
	 <div class="btn-toolbar pad4" role="toolbar">
		 <div class="btn-group"> <a href="/Usuarios/create" role="button" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> Nuevo</a> </div> 
		 <div class="btn-group"> <a href="/Usuarios/userEdit" role="button" class="btn btn-sm btn-success"> <i class="fa fa-edit"></i> Editar</a> </div>
		 <div class="btn-group"> <a href="/Usuarios/userDeleteViev" role="button" class="btn btn-sm btn-danger"> <i class="fa fa-times"></i> Eliminar</a> </div>
	</div> </div> </div> </div>

@else

	<h2 class="col-sm-12 mar30 text-danger"> <br>
	<i class="fa fa-warning"></i> No tienes permisos para acceder a esta área. </h2>
   
@endif

@endsection