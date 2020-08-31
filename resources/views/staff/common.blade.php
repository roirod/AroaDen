@extends('layouts.main')

@section('content')

  @include('includes.staff_nav')

  @include('form_fields.create_edit')
    
@endsection

@section('footer_script')

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


