@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')


{!! addtexto("Añadir Personal") !!}


<div class="row">
  <div class="col-sm-12">
  	<form role="form" id="form" class="form" action="{{ url('/Personal') }}" method="post">
		{!! csrf_field() !!}
		 
		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Apellidos:</label>
		<input type="text" class="form-control" name="ape" maxlength="77" autofocus required > 	</div>
				
		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Nombre:</label>
		<input type="text" class="form-control" name="nom" maxlength="44" required > 		</div>

		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Cargo:</label>
		<input type="text" class="form-control" name="cargo" maxlength="44" required > 		</div>
				
		<div class="form-group col-sm-5"> <label class="control-label text-left mar10">Poblaci&#xF3;n:</label>
		  <input type="text" class="form-control" name="pobla" maxlength="111" > </div>  
		  
		<div class="form-group col-sm-6"> <label class="control-label text-left mar10">Direcci&#xF3;n:</label>
		  <input type="text" class="form-control" name="direc" maxlength="111" > </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">DNI:</label>
		  <input type="text" class="form-control" name="dni" maxlength="11" required> </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">Tel&#xE9;fono1:</label>
		  <input type="text" class="form-control" name="tel1" maxlength="11"> </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">Tel&#xE9;fono2:</label>
		  <input type="text" class="form-control" name="tel2" maxlength="11"> </div>

		<div class="form-group col-sm-4"> 	 <label class="control-label text-left mar10">F. nacimiento:</label>		 	
			<input type="date" name="fenac" value="1970-01-01" > </div>

		<div class="form-group col-sm-11">    <label class="control-label text-left mar10">Notas:</label>
		    <textarea class="form-control" name="notas" rows="4"></textarea> 		</div>

@include('includes.subuto')
		    
</form> </div> </div>

@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection