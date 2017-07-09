@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

{!! addtexto("Añadir Tratamientos al Paciente") !!}

<div class="row">
 <div class="col-sm-12 mar10">

    <form role="form" id="form" class="form" action="{{url('/Trapac/selcrea')}}" method="post">
	    
	    {!! csrf_field() !!}

		<input type="hidden" name="idpac" value="{{$idpac}}">
		<input type="hidden" name="surname" value="{{$surname}}">
		<input type="hidden" name="name" value="{{$name}}">

		<div class="form-group col-lg-6">
		   
		   <label class="control-label text-left mar10">Selecciona servicio:</label> 
		   
		   <select name="idser" class="form-control" required>
		   
			     @foreach($servicios as $servici)
					<option value="{{$servici->idser}}">{{$servici->name}} | precio: {{$servici->price}} €</option>
			 	 @endforeach	
		   
		   </select>
		
		</div>

		@include('includes.submit_button')

	</form>

 </div>
</div>

@endsection

@section('js')
    @parent  
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection