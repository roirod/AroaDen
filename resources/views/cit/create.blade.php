@extends('layouts.main')

@section('content')

	@include('includes.pacnav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addtexto("Añadir Cita") !!}

	@include('form_fields.create.opendiv')

		<p class="pad4 fonsi15"> {{ $surname }}, {{ $name }} </p>

		@include('form_fields.create.openform')

			<input type="hidden" name="idpac" value="{{$id}}">

			@include('form_fields.create_alternative')

		@include('form_fields.create.closeform')
	@include('form_fields.create.closediv')

@endsection

@section('js')
    @parent

	<script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/minified/shims/i18n/formcfg-es.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection