@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

<?php
 addtexto("Añadir Tratamientos al Paciente");
?>

<div class="row">
 <div class="col-sm-12 mar10">

    <form role="form" id="form" class="form" action="{{url('/Trapac/selcrea')}}" method="post">
	    
	    {!! csrf_field() !!}

		<input type="hidden" name="idpac" value="{{$idpac}}">
		<input type="hidden" name="apepac" value="{{$apepac}}">
		<input type="hidden" name="nompac" value="{{$nompac}}">

		<div class="form-group col-lg-6">
		   
		   <label class="control-label text-left mar10">Selecciona servicio:</label> 
		   
		   <select name="idser" class="form-control" required>
		   
			     @foreach($servicios as $servici)
					<option value="{{$servici->idser}}">{{$servici->nomser}} | precio: {{$servici->precio}} €</option>
			 	 @endforeach	
		   
		   </select>
		
		</div>

		@include('includes.subuto')

	</form>

 </div>
</div>

@endsection

@section('js')
    @parent  
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection