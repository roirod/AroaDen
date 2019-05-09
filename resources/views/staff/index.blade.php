@extends('layouts.main')

@section('content')

  <div id="ajax_content">

    @include('staff.ajaxIndex')

  </div>

@endsection

@section('footer_script')

  <link href="{!! asset('assets/css/datatables.min.css') !!}" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="{!! asset('assets/js/datatables.min.js') !!}"></script>

@endsection