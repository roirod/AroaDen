
  @include('includes.staff_positions_nav')

  @include('includes.messages')
    
  <div class="row">
    <div class="col-sm-11">
      <fieldset>

        <legend>
          @if ($is_create_view)

            {!! @trans('aroaden.create_position') !!}

          @else

            {!! @trans('aroaden.edit_position') !!}

          @endif
        </legend>

          @include('form_fields.common')

      </fieldset>
    </div>
  </div>

  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>
