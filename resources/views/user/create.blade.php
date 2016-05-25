@extends('layouts.main')

@section('content')

@include('includes.usernav')

@include('includes.messages')
@include('includes.errors')


<div class="row">

<div class="col-sm-3 mar10">
	<p class="pad10"> Usuarios creados: </p>
	@foreach ($users as $user)
		<ul>
			<li>{{ $user->username }}</li>		
		</ul>
	@endforeach
</div>

  <div class="col-sm-6 mar10"> 
  	<p class="pad10"> Crear Usuario: </p>
  	
  	<form role="form" class="form" action="/Usuarios" method="post">
 	{!! csrf_field() !!}
 
 <div class="input-group"> <span class="input-group-btn pad4"> <p> &nbsp; Usuario:</p> </span>
 <div class="col-sm-7"> <input type="text" name="username" class="form-control" placeholder="Usuario" required> </div> </div>
<br>
 <div class="input-group"> <span class="input-group-btn pad4"> <p> &nbsp; Contraseña:</p> </span>
 <div class="col-sm-7"> <input type="text" name="password" class="form-control" placeholder="Contraseña" required> </div>  </div>
<br>
 <div class="col-sm-5"> <button class="btn btn-primary" type="submit"> Crear &nbsp; <i class="fa fa-arrow-circle-right"></i></button>
 </div> </form> </div>
 
</div>
 
@endsection