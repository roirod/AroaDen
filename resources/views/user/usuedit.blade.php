@extends('layouts.main')

@section('content')

@include('includes.usernav')

@include('includes.messages')
@include('includes.errors')

 <div class="row"> 
  	<div class="col-sm-12 mar10">

   		<p class="pad10"> Cambiar contraseña: </p>
	 	
	 	<form role="form" class="form" id="form" role="form" action="{!!url("/Usuarios/saveup")!!}" method="POST">	
	  		{!! csrf_field() !!}

			<div class="input-group"> 
				<span class="input-group-btn pad4"> <p> &nbsp; Usuario:</p> </span>
	 			<div class="col-sm-3">
	 				<select name="uid" class="form-control">
 
						@foreach ($users as $user)
			   
			  				<option value="{!!$user->uid!!}">{!!$user->username!!}</option> 
			
						@endforeach
 
 					</select>
 				</div>
 			</div>
			
			<br>
	
	 		<div class="input-group">
	 			<span class="input-group-btn pad4"> <p> &nbsp; Contraseña nueva:</p> </span>
	 			<div class="col-sm-3">
	 				<input type="text" name="password" class="form-control" placeholder="Contraseña" required> 
	 			</div> 
	  		</div>

@include('includes.subuto')
	 			
 		</form>
 	</div>
 </div>
 
 @endsection