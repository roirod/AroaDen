@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addText(@trans('aroaden.edit_patient')) !!}

	@include('form_fields.common')

@endsection
	 
@section('js')
    @parent
    
	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/webshims.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/forgetChanges.js') !!}"></script>
	 	  
@endsection