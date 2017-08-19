@extends('layouts.main')

@section('content')

	@include('includes.pernav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addtexto("Editar Personal") !!}

	@include('form_fields.edit')

@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection