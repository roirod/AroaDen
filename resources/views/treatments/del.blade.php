@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')

 <div class="row"> 
  	<div class="col-sm-12 mar10">
  	
	    <form role="form" id="form" class="form" action="{{url("/Trapac/$idtra")}}" method="POST">
	        {!! csrf_field() !!}

			<input type="hidden" name="_method" value="DELETE">

			<input type="hidden" name="idpac" value="{{$idpac}}">

		   	<p class="pad10">
		   		Eliminar Tratamiento:
		   	</p>

		    <p class="pad4">
		        {{ $tratampa->name }}
		        <br>
		        Precio: {{ $tratampa->price }} €.
		        <br>
		        Cantidad: {{ $tratampa->units }}.
		        <br>
		        total: {{ numformat($tratampa->units * $tratampa->price) }} €.      
		        <br>
		        Pagado: {{ $tratampa->paid }} €.
		        <br>
		        Fecha: {{ date('d-m-Y', strtotime ($tratampa->date) ) }}.        
		    </p>    

			@include('includes.delete_button')
 			
 		</form>
 	</div>
 </div>
 
 @endsection