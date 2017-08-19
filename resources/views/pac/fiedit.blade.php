@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


{!! addtexto("Editar Ficha") !!}

<div class="row">
 <div class="col-sm-12">
  	<form class="form" id="form" role="form" action="{!!url("/$main_route/$id/fisave")!!}" method="POST">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="PUT">


		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Historial Médico:</label>
		<textarea class="form-control" name="histo" autofocus rows="4"> {!! $ficha->histo !!} </textarea> 	</div> 	

		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Enfermedades:</label>
		<textarea class="form-control" name="enfer" rows="4"> {!! $ficha->enfer !!} </textarea> 	</div> 


		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Medicamentos:</label>
		<textarea class="form-control" name="medic" rows="4"> {!! $ficha->medic !!} </textarea> 	</div> 

		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Alergias:</label>
		<textarea class="form-control" name="aler" rows="4"> {!! $ficha->aler !!} </textarea> 	</div> 


		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Notas:</label>
		<textarea class="form-control" name="notes" rows="4"> {!! $ficha->notes !!} </textarea> 	</div> 
		
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