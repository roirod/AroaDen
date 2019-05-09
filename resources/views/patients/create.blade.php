@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addText(@trans('aroaden.create_patient')) !!}

	@include('form_fields.common')
    
@endsection

@section('footer_script')
	<script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/moment-es.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/datetimepicker/css/datetimepicker.min.css') }}" />
    <script type="text/javascript" src="{{ asset('assets/datetimepicker/js/datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/datetimepicker/datepicker1.js') }}"></script>
@endsection