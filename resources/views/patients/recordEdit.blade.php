@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')


{!! addText("Editar record") !!}

<div class="row">
 <div class="col-sm-12">
  	<form class="form" id="form" role="form" action="{!! url("/$main_route/$id/$form_route") !!}" method="POST">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="PUT">

		<div class="form-group col-sm-12">
		    <label class="control-label text-left mar10">Historial Médico:</label>
		    <textarea class="form-control" name="histo" autofocus rows="4">{!! $record->histo !!}</textarea>
		</div>

		<div class="form-group col-sm-12">
		    <label class="control-label text-left mar10">Enfermedades:</label>
		    <textarea class="form-control" name="histo" autofocus rows="4">{!! $record->enfer !!}</textarea>
		</div>

		<div class="form-group col-sm-12">
		    <label class="control-label text-left mar10">Medicamentos:</label>
		    <textarea class="form-control" name="histo" autofocus rows="4">{!! $record->medic !!}</textarea>
		</div>

		<div class="form-group col-sm-12">
		    <label class="control-label text-left mar10">Alergias:</label>
		    <textarea class="form-control" name="histo" autofocus rows="4">{!! $record->aler !!}</textarea>
		</div>

		<div class="form-group col-sm-12">
		    <label class="control-label text-left mar10">Notas:</label>
		    <textarea class="form-control" name="histo" autofocus rows="4">{!! $record->notes !!}</textarea>
		</div>
		
		@include('includes.submit_button')

 	</form>

</div> </div>

@endsection
	 
@section('js')
    @parent
    
	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/main.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/guarda.js') !!}"></script>
	 	  
@endsection