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

  @include('includes.pacnav')

  @include('includes.messages')
  @include('includes.errors')

  @include('form_fields.show.file')
	 
@endsection
