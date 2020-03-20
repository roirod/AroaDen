@extends('layouts.main')

@section('content')

	@include('includes.staff_nav')

	@include('includes.messages')
	@include('includes.errors')  

    <div class="row">
      <div class="col-sm-9">
        <fieldset>
          <legend>
            {!! @trans('aroaden.edit_position') !!}
          </legend>

            @include('form_fields.common')

        </fieldset>
      </div>
    </div>

@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>
@endsection