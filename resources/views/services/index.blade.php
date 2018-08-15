@extends('layouts.main')

@section('content')

  <div id="ajax_content">

    @include('includes.messages')
    @include('includes.errors')

    @include('services.indexInclude')

  	@include('services.jsInclude')

  </div>

@endsection