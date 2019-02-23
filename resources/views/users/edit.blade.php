@extends('layouts.main')

@section('content')

	@include('includes.users_nav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addText(@trans('aroaden.edit')) !!}

	<div class="col-sm-5"> 

		@include('form_fields.common')

	</div>



 <br> <br> <br> <br> <br> <br> <br> <br>


 @endsection