@extends('layouts.main')

@section('content')

@include('includes.users_nav')

@include('includes.messages')
@include('includes.errors')

 <div class="row"> 
  	<div class="col-sm-12 mar10">
  	
   	<p class="pad10">
   		Eliminar Usuario:
   	</p>

	 	<form role="form" class="form" id="form" role="form" action="{!! url("/$main_route/$form_route") !!}" method="POST">
	  		{!! csrf_field() !!}

			<div class="input-group"> 
				<span class="input-group-btn pad4"> <p> &nbsp; Usuario:</p> </span>
	 			<div class="col-sm-3">
	 				<select name="uid" class="form-control">
 
						@foreach($main_loop as $user)

							@continue($user->username == 'admin')
			   
			  				<option value="{!!$user->uid!!}">{!!$user->username!!}</option> 
			
						@endforeach
 
 					</select>
 				</div>
 			</div>

			@include('includes.delete_button')
 			
 		</form>
 	</div>
 </div>


 <br> <br> <br> <br> <br> <br> <br> <br>

 
 @endsection