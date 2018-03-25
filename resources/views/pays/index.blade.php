@extends('layouts.main')

@section('content')

	@include('includes.accounting_nav')

	<div id="ajax_content">

		@include('includes.messages')
		@include('includes.errors')

		@include('pays.common')

	</div>

@endsection

@section('js')

  @parent

  @include('pays.jsInclude')
    
@endsection