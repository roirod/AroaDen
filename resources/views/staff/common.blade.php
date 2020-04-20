@extends('layouts.main')

@section('content')

  @include('includes.staff_nav')

  @include('includes.messages')
  
  <div class="row">
    <div class="col-sm-12">
      <fieldset>
        <legend>
          {!! $misc_text !!}
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

  <link rel="stylesheet" href="{{ asset('assets/slimselect/slimselect.min.css') }}" />
  <script type="text/javascript" src="{{ asset('assets/slimselect/slimselect.min.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      new SlimSelect({
        select: '#positions',
        deselectLabel: '<span class="red fonsi18">✖</span>',
        placeholder: "{{ Lang::get('aroaden.select_one_or_more') }}",
        searchText: "{{ Lang::get('aroaden.search') }}",
        searchPlaceholder: "{{ Lang::get('aroaden.search') }}",
        searchFocus: true,
        searchHighlight: true,
        closeOnSelect: false
      });
    });
  </script>

@endsection


