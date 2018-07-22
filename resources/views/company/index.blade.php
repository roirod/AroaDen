@extends('layouts.main')

@section('content')

@include('includes.company_nav')

  <div id="ajax_content">

    @include('includes.messages')
    @include('includes.errors')

    @include('company.indexInclude')

  </div>

  @include('company.jsInclude')

@endsection
