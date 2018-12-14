@extends('layouts.main')

@section('head')
    @parent

	<link href="{!! asset('assets/css/colorbox.css') !!}" rel="stylesheet" type="text/css" >
@endsection

@section('js')
    @parent

	<script type="text/javascript" src="{!! asset('assets/js/colorbox/jquery.colorbox-min.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('assets/js/colorbox/jquery.colorbox-es.js') !!}"></script>	
@endsection

@section('content')

  @include('includes.staff_nav')

  @include('includes.messages')
  @include('includes.errors')

  <div class="col-sm-12 pad10">
      @include('form_fields.show.name')
  </div>

  @include('form_fields.show.file')
	 
@endsection