@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

    <div class="row">
      <div class="col-sm-12">
        <fieldset>
          <legend>
            {!! @trans('aroaden.edit_patient') !!}
          </legend>

            @include('form_fields.common')

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