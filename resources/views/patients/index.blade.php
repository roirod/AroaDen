@extends('layouts.main')

@section('content')

  @include('includes.messages')

  <div id="ajax_content">

    @include('patients.ajaxIndex')

  </div>

@endsection