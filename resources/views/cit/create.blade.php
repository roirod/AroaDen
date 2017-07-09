@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


{{ addtexto("Añadir Cita") }}


<div class="row">
  <div class="col-sm-12">

	<p class="lead"> {{ $surname }}, {{ $name }} | id: {{ $idpac }}</p>

	<form role="form" id="form" class="form" action="{{ url('/Citas') }}" method="post">
		{!! csrf_field() !!}

		<input type="hidden" name="idpac" value="{{$idpac}}">

		@include('form_fields.create') 

	</form> 
</div>  </div>

@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection