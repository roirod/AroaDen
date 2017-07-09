@extends('layouts.main')

@section('content')

@include('includes.usernav')

@include('includes.messages')
@include('includes.errors')

 <div class="row"> 
  	<div class="col-sm-12 mar10">

   		<p class="pad10"> {!!Lang::get('aroaden.change_pass')!!}: </p>
	 	
	 	<form role="form" class="form" id="form" role="form" action="{!!url("/Usuarios/userUpdate")!!}" method="POST">	
	  		{!! csrf_field() !!}

			<div class="input-group"> 
				<span class="input-group-btn pad4"> <p> &nbsp; {!!Lang::get('aroaden.user')!!}:</p> </span>
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
	 			<span class="input-group-btn pad4"> <p> &nbsp; {!!Lang::get('aroaden.new_pass')!!}:</p> </span>
	 			<div class="col-sm-3">
	 				<input type="text" name="password" class="form-control" placeholder="{!!Lang::get('aroaden.new_pass')!!}" autofocus required> 
	 			</div> 
	  		</div>

			@include('includes.submit_button')
	 			
 		</form>
 	</div>
 </div>
 

 <br> <br> <br> <br> <br> <br> <br> <br>


 @endsection