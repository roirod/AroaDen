@extends('layouts.main')

@section('content')

	@include('includes.users_nav')

  @include('form_fields.show.form_errors')

	<div class="col-sm-5">
    <fieldset>
			<legend>
			  {!! @trans('aroaden.edit_user') !!}
			</legend>

      @include('users.common')

		</fieldset>
	</div>

@endsection