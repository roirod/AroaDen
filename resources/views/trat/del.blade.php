@extends('layouts.main')

@section('content')

@include('includes.pacnav')

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
		        {{ $tratampa->nomser }}
		        <br>
		        precio: {{ $tratampa->precio }} €.
		        <br>
		        cantidad: {{ $tratampa->canti }}.
		        <br>
		        total: {{ numformat($tratampa->canti * $tratampa->precio) }} €.      
		        <br>
		        Pagado: {{ $tratampa->pagado }} €.
		        <br>
		        Fecha: {{ date('d-m-Y', strtotime ($tratampa->fecha) ) }}.        
		    </p>   

			@include('includes.delbuto')
 			
 		</form>
 	</div>
 </div>
 
 @endsection