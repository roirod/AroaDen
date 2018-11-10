@extends('layouts.main')

@section('content')

	@include('includes.staff_positions_nav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addText("Añadir Cargos de personal") !!}

	@include('form_fields.create')
    
@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>
@endsection