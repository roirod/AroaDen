@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addText("Crear factura") !!}

	@include('form_fields.create.opendiv')

		<form role="form" id="form" class="form">

			@include('form_fields.create_alternative')

		@include('form_fields.create.closeform')
	@include('form_fields.create.closediv')
    
@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/main.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/guarda.js') !!}"></script>
@endsection