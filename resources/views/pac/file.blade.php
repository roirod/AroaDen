@extends('layouts.main')

@section('content')

  @include('includes.pacnav')

  @include('includes.messages')
  @include('includes.errors')

  @include('form_fields.show.file')
	 
@endsection