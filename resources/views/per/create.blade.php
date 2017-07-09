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
			<input type="text" class="form-control" name="surname" maxlength="111" value="{{ old('surname') }}" autofocus required > </div>
			
		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Nombre:</label>
			<input type="text" class="form-control" name="name" maxlength="111" value="{{ old('name') }}" required > 		</div>

		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Cargo:</label>
			<input type="text" class="form-control" name="position" maxlength="66" value="{{ old('position') }}" required > 		</div>
				
		<div class="form-group col-sm-5"> <label class="control-label text-left mar10">Poblaci&#xF3;n:</label>
		  <input type="text" class="form-control" name="city" maxlength="111" value="{{ old('city') }}" > </div>  
		  
		<div class="form-group col-sm-6"> <label class="control-label text-left mar10">Direcci&#xF3;n:</label>
		  <input type="text" class="form-control" name="address" maxlength="111" value="{{ old('address') }}" > </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">DNI:</label>
		  <input type="text" class="form-control" name="dni" maxlength="11" value="{{ old('dni') }}" required> </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">Tel&#xE9;fono1:</label>
		  <input type="text" class="form-control" name="tel1" maxlength="11" value="{{ old('tel1') }}"> </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">Tel&#xE9;fono2:</label>
		  <input type="text" class="form-control" name="tel2" maxlength="11" value="{{ old('tel2') }}"> </div>

		<div class="form-group col-sm-4"> 	 <label class="control-label text-left mar10">F. nacimiento:</label>		 	
			<input type="date" name="birth" value="1970-01-01" > </div>

		<div class="form-group col-sm-11">    <label class="control-label text-left mar10">Notas:</label>
		    <textarea class="form-control" name="notes" rows="4"> {{ old('notes') }} </textarea> 		</div>

		@include('includes.submit_button')
		    
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