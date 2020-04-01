@extends('layouts.main')

@section('content')

	@include('includes.messages')
	@include('includes.errors')

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

@endsection