@extends('layouts.main')

@section('content')

@include('includes.pernav')

@include('includes.messages')
@include('includes.errors')


{!! addtexto("Editar Personal") !!}


<div class="row">
  <div class="col-sm-12">
  	<form role="form" id="form" class="form" action="{{url("/Personal/$idper") }}" method="post">

  	  {!! csrf_field() !!}

  	  <input type="hidden" name="_method" value="PUT">

		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Apellidos:</label>
			<input type="text" class="form-control" name="surname" value="{{$personal->surname}}" maxlength="111" autofocus required > 	</div>
				
		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Nombre:</label>
			<input type="text" class="form-control" name="name" value="{{$personal->name}}" maxlength="111" required >  </div>

		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Cargo:</label>
			<input type="text" class="form-control" name="position" value="{{$personal->position}}" maxlength="66" required >  </div>
				
		<div class="form-group col-sm-3"> <label class="control-label text-left mar10">Poblaci&#xF3;n:</label>
		  <input type="text" class="form-control" name="city" value="{{$personal->city}}" maxlength="111" > </div>  
		  
		<div class="form-group col-sm-3"> <label class="control-label text-left mar10">Direcci&#xF3;n:</label>
		  <input type="text" class="form-control" name="address" value="{{$personal->address}}" maxlength="111" > </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">DNI:</label>
		  <input type="text" class="form-control" name="dni" value="{{$personal->dni}}" maxlength="11" required> </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">Tel&#xE9;fono1:</label>
		  <input type="text" class="form-control" name="tel1" value="{{$personal->tel1}}" maxlength="11"> </div>

		<div class="form-group col-sm-2"> <label class="control-label text-left mar10">Tel&#xE9;fono2:</label>
		  <input type="text" class="form-control" name="tel2" value="{{$personal->tel2}}" maxlength="11"> </div>

		<div class="form-group col-sm-4"> 	 <label class="control-label text-left mar10">F. nacimiento:</label>		 	
			<input type="date" name="birth" value="{{$personal->birth}}"> </div>

		<div class="form-group col-sm-11">    <label class="control-label text-left mar10">Notas:</label>
		    <textarea class="form-control" name="notes" rows="4"> {{ $personal->notes }} </textarea> 		</div>

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