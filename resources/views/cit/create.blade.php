@extends('layouts.main')

@include('includes.other')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

<?php
 addtexto("Añadir Cita");
?>

<div class="row">
  <div class="col-sm-12">

	<p class="lead"> {{$apepac}}, {{$nompac}} | id: {{$idpac}}</p>

	<form role="form" id="form" class="form" action="{{ url('/Citas') }}" method="post">
	{!! csrf_field() !!}

	<input type="hidden" name="idpac" value="{{$idpac}}">

	<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Hora:</label> 
	<input type="time" name="horacit" step="300" class="form-control" required>  </div>
			
	<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Dia:</label>		 	
	<input type="date" name="diacit" class="form-control" required>  </div>
			
	<div class="form-group col-sm-9">  <label class="control-label text-left mar10">Notas:</label>
	<textarea class="form-control" name="notas" rows="3"></textarea>  </div>		
			<br>
			
	<div class="form-group"> <div class="col-sm-4 text-left">
	<button type="submit" class="text-left btn btn-primary btn-md">Añadir <i class="fa fa-chevron-circle-right"></i> </button>
</div>  </div>  </form>  </div>  </div>

@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ URL::asset('assets/js/modernizr.js') }}"></script>
	  <script type="text/javascript" src="{{ URL::asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ URL::asset('assets/js/main.js') }}"></script>
	  <script type="text/javascript" src="{{ URL::asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ URL::asset('assets/js/guarda.js') }}"></script>
@endsection