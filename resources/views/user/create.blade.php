@extends('layouts.main')

@section('content')

@include('includes.usernav')

@include('includes.messages')
@include('includes.errors')


<div class="row">

	<div class="col-sm-4 mar10">
	  <p class="pad10"> Usuarios creados: </p>

	  <div class="panel panel-default">
			<table class="table">
			  	 <tr class="fonsi16">
					<td class="wid110">Usuario</td>
					<td class="wid110">Permisos</td>			
				 </tr>
			</table>
	 	<div class="box300">

		 	 <table class="table table-striped table-bordered table-hover">
				
				@foreach ($users as $user)

					@continue($user->username == 'admin')
						
					<tr> 
						<td class="wid110">{!! $user->username !!}</td>
						<td class="wid110">{!! $user->tipo !!}</td>				
					</tr>
								
				@endforeach
						
			</table>

</div> </div> </div>


	<div class="col-sm-6 mar10"> 
	  	<p class="pad10"> Crear Usuario: </p>
	  	
	  	<form role="form" class="form" action="/Usuarios" method="post">
			{!! csrf_field() !!}
			 
			 <div class="input-group"> 
			 	<span class="input-group-btn pad4"> <p> &nbsp; Usuario:</p> </span>
			 	<div class="col-sm-7">
			 		<input type="text" name="username" class="form-control" placeholder="Usuario" autofocus required>
			 </div> </div>

			<br>

			 <div class="input-group"> 
			 	<span class="input-group-btn pad4"> <p> &nbsp; Contraseña:</p> </span>
			 	<div class="col-sm-7">
			 		<input type="text" name="password" class="form-control" placeholder="Contraseña" required> </div>  </div>

			<br>

			<div class="form-group col-lg-6">
			   
			   <label class="control-label text-left mar10">Permisos:</label> 
			   
			   <select name="tipo" class="form-control" required>
			  
			  		<option value="normal" selected>normal</option>
			  		<option value="medio">medio</option>

			   </select>
			
			</div>

			@include('includes.subuto')

	 </form>
  </div>
 
</div>
 
@endsection