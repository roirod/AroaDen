@extends('layouts.main')

@section('content')

@include('includes.usernav')

@include('includes.messages')
@include('includes.errors')

 <div class="row"> 
  	<div class="col-sm-12 mar10">
  	
   	<p class="pad10">
   		Eliminar Usuario:
   	</p>

	 	<form role="form" class="form" id="form" role="form" action="{{url("/Usuarios/delete")}}" method="POST">	
	  		{!! csrf_field() !!}

			<div class="input-group"> 
				<span class="input-group-btn pad4"> <p> &nbsp; Usuario:</p> </span>
	 			<div class="col-sm-3">
	 				<select name="uid" class="form-control">
 
						@foreach($users as $user)

							@continue($user->username == 'admin')
			   
			  				<option value="{{$user->uid}}">{{$user->username}}</option> 
			
						@endforeach
 
 					</select>
 				</div>
 			</div>

			<br>
	
	 		<div class="col-sm-12">
	 			<div class="input-group">
					<div class="btn-group">
						<button type="button" class="btn btn-danger btn-md dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-times"></i> Eliminar 
							<span class="caret"></span> 
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><button type="submit"> <i class="fa fa-times"></i> Eliminar </button></li>
						</ul>
					</div>
				</div>
			</div>
 			
 		</form>
 	</div>
 </div>
 
 @endsection