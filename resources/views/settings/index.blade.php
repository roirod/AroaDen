@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

@if( $username == 'admin' )

	<div class="row"> 
	  <div class="col-sm-12"> 
	    <div class="input-group"> 
	      <span class="input-group-btn pad10">  <p> Usuarios: </p> </span>
	      <div class="btn-toolbar pad4" role="toolbar"> 
	        <div class="btn-group">
	          <a href="{{ url("/$main_route") }}" role="button" class="btn btn-sm btn-primary">
	            <i class="fa fa-bars"></i> Ver
	          </a>
	        </div>  
	</div> </div> </div> </div>

@else

	<h2 class="col-sm-12 mar30 text-danger"> <br>
	<i class="fa fa-warning"></i> No tienes permisos para acceder a esta área. </h2>
   
@endif


<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 
<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 


@endsection