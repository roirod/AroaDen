@extends('layouts.main')

@section('content')

	@include('includes.messages')

	<div id="ajax_content">

		@include('services.ajaxIndex')

	</div>

@endsection

