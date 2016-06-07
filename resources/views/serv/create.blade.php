@extends('layouts.main')

@include('includes.other')

@section('content')

@include('includes.messages')
@include('includes.errors')


{{ addtexto("Añadir servicio") }}

<div class="row">
  <div class="col-sm-12">
  
	<form role="form" id="form" class="form" action="{{url("Servicios")}}" method="post">
		{!! csrf_field() !!}
		 
		 <div class="form-group col-sm-5">   <label class="control-label text-left mar10">Nombre:</label>
		  	<input type="text" class="form-control" name="nomser" maxlength="111" required >
		 </div>
				
		 <div class="form-group col-sm-2">  <label class="control-label text-left mar10">Precio:</label>
		  	<input type="number" min="0" step="1" class="form-control" name="precio" maxlength="11" required >
		 </div> 
				
		<div class="form-group col-sm-2">
			<label class="control-label text-left mar10">IVA:</label>
			<select name="iva" class="form-control" required>
		 
				@foreach ($ivatp as $clave => $valor) 
					 <option value="{{$valor}}">{{$clave}}</option>
				@endforeach
		 
			</select>
		</div>	   
		   	
@include('includes.subuto')
  
 	</form>

 </div> </div>

@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection