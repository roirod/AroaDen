@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

	<div class="col-sm-12 pad10">
	    @include('form_fields.show.name')
	</div>

	<div class="row">
	  <div class="col-sm-12">
	    <fieldset>
	      <legend>
	        {!! @trans('aroaden.create_appointment') !!}
	      </legend>

				@include('form_fields.fields.opendiv')
					@include('form_fields.fields.openform')

						<input type="hidden" name="idpat" value="{{ $id }}">

						@include('form_fields.common_alternative')

					@include('form_fields.fields.closeform')
				@include('form_fields.fields.closediv')
	    </fieldset>
	  </div>
	</div>

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
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/timepicker1.js') }}"></script>
@endsection