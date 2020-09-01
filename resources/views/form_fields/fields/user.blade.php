	@if ($is_create_view)

		<div class="form-group col-sm-11">  
			<label class="control-label text-left mar10">{{ @trans('aroaden.user') }}</label>
			<input type="text" class="form-control" name="username" value="{{ old('username') }}">
		</div>

	@elseif (isset($object) && $object->username != 'admin')

		<div class="form-group col-sm-11">  
			<label class="control-label text-left mar10">{{ @trans('aroaden.user') }}</label>
			<input type="text" class="form-control" name="username" value="{!! $object->username !!}">
		</div>

	@elseif (isset($object) && $object->username == 'admin')

		<div class="form-group col-sm-11">  
			<label class="control-label text-left mar10">{{ @trans('aroaden.user') }}</label>
			<br>
			<p class="label label-primary fonsi18">
				{!! $object->username !!}
			</p>
		</div>

	@endif


