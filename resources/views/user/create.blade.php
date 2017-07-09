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
				
				@foreach ($main_loop as $user)

					@continue($user->username == 'admin')
						
					<tr> 
						<td class="wid110">{!! $user->username !!}</td>
						<td class="wid110">{!! $user->type !!}</td>				
					</tr>
								
				@endforeach
						
			</table>

</div> </div> </div>


	<div class="col-sm-6 mar10"> 
	  	<p class="pad10"> Crear Usuario: </p>
	  	
	  	<form role="form" class="form" action="/Usuarios" method="post">
			{!! csrf_field() !!}
			 
			@include('form_fields.create.user')

			@include('form_fields.create.password')

			@include('form_fields.create.scopes')

			@include('includes.submit_button')

		</form>
	</div>
 
</div>
 
@endsection