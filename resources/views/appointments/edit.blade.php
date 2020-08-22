@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')

  <div class="col-sm-12 pad10">
    @include('form_fields.show.name')
  </div>

  <div class="row">
    <div class="col-sm-12">
      <fieldset>
        <legend>
          {!! @trans('aroaden.edit_appointment') !!}
        </legend>

        @include('form_fields.fields.opendiv')
          @include('form_fields.fields.openform')

            @include('form_fields.common_alternative')

          @include('form_fields.fields.closeform')
        @include('form_fields.fields.closediv')
      </fieldset>
    </div>
  </div>            

@endsection

@section('footer_script')

  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/forgetChanges.js') !!}"></script>

@endsection