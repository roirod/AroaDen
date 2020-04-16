
  @include('includes.messages')
  
  <div class="row">
    <div class="col-sm-12">
      <fieldset>
        <legend>
          @if ($is_create_view)

            {!! @trans('aroaden.create_patient') !!}

          @else

            {!! @trans('aroaden.edit_patient') !!}

          @endif
        </legend>

        @include('form_fields.common')

      </fieldset>
    </div>
  </div>
    
  <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

  <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/moment-es.js') }}"></script>

  <link rel="stylesheet" href="{{ asset('assets/datetimepicker/css/datetimepicker.min.css') }}" />
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/js/datetimepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/datepicker1.js') }}"></script>
