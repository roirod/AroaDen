@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

 <div class="row"> 
  	<div class="col-sm-12 mar10">
  	
   	<p class="pad10">
   		Eliminar Paciente:
   	</p>

	 	<form role="form" class="form" id="form" role="form" action="{{url("/Pacientes/$idpac")}}" method="POST">	
	  		{!! csrf_field() !!}

			<input type="hidden" name="_method" value="DELETE">

			<div class="col-sm-12">
				<span class="lead pad4"> 

					<p> &nbsp;{{$paciente->apepac}}, {{$paciente->nompac}}</p>

				</span>
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