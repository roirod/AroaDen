@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


 {!! addtexto("Editar Cita") !!}

<div class="row">
  <div class="col-sm-12">

  	<form class="form" id="form" role="form" action="{{url("/Citas/$idcit")}}" method="POST">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="PUT">

		<input type="hidden" name="idpac" value="{{$idpac}}">

		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Hora:</label> 
		<input type="time" step="300" class="form-control" name="hour" value="{{$cita->hour}}" autofocus required>  </div>
				
		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Día:</label>		 	
		<input type="date" class="form-control" name="day" value="{{$cita->day}}" required>  </div>
				
		<div class="form-group col-sm-9">  <label class="control-label text-left mar10">Notas:</label>
		<textarea class="form-control" name="notes" rows="3"> {{$cita->notes}} </textarea>  </div>		
				
		@include('includes.submit_button')
	 
	 </form>
 
</div>  </div>

@endsection

@section('js')
    @parent

	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/main.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/guarda.js') !!}"></script>
@endsection