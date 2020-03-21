@extends('layouts.main')

@section('content')

	@include('includes.users_nav')

	@include('includes.messages')
	@include('includes.errors')

	<div class="col-sm-5">
        <fieldset>
	          <legend>
	            {!! @trans('aroaden.edit_user') !!}
	          </legend>

				@include('form_fields.common')
		</fieldset>
	</div>


 <br> <br> <br> <br> <br> <br> <br> <br>


 @endsection