@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')


{{ addtexto("Editar servicio") }}


<div class="row">
  <div class="col-sm-12">
  
	<form role="form" id="form" class="form" action="{{url("/Servicios/$idser")}}" method="POST">
		{!! csrf_field() !!}

		<input type="hidden" name="_method" value="PUT">

		 <div class="form-group col-sm-5">
		    <label class="control-label text-left mar10">Nombre:</label>
		  	<input type="text" class="form-control" name="nomser" value="{{$servicio->nomser}}" maxlength="111" autofocus required >
		 </div>
				
		 <div class="form-group col-sm-2"> 
		 	<label class="control-label text-left mar10">Precio:</label>
		  	<input type="number" min="0" step="1" class="form-control" name="precio" value="{{$servicio->precio}}" maxlength="11" required >
		 </div> 

		<div class="form-group col-sm-2"> 
			<label class="control-label text-left mar10">IVA:</label>
			<select name="iva" class="form-control" required>

			 	<option value="{{$servicio->iva}}" selected> {{$servicio->iva}}% </option>
			 
				@foreach ($ivatp as $clave => $valor) 
					@continue($valor == $servicio->iva)
					 <option value="{{$valor}}">{{$clave}} </option>
				@endforeach
			 
			</select>
		</div>	 
		   	
		
		@include('includes.subuto')
  
 	</form>

 </div> </div>


 <br> <br> <br> <br> <br> <br> 
  <br> <br> <br> <br> <br> <br> <br>


@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection