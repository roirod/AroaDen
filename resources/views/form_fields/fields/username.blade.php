	
	<div class="form-group col-sm-11">  
		<label class="control-label text-left mar10">{{ @trans('aroaden.user') }}</label>

		@if ($is_create_view)

			<input type="text" class="form-control" name="username" value="{{ old('username') }}">

		@elseif (isset($object) && $object->username != 'admin')

			<input type="text" class="form-control" name="username" value="{!! $object->username !!}">

		@elseif (isset($object) && $object->username == 'admin')

			<input type="hidden" name="username" value="{!! $object->username !!}">
			<br>
			<p class="label label-primary fonsi18">
				{!! $object->username !!}
			</p>

		@endif

	</div>
