	@if ($is_create_view)

		<div class="form-group col-sm-11">  
			<label class="control-label text-left mar10">{{ @trans('aroaden.user') }}</label>
			<input type="text" class="form-control" name="username" value="{{ old('username') }}" maxlength="77" required>
		</div>

	@elseif (isset($object) && $object->username != 'admin')

		<div class="form-group col-sm-11">  
			<label class="control-label text-left mar10">{{ @trans('aroaden.user') }}</label>
			<input type="text" class="form-control" name="username" value="{!! $object->username !!}" maxlength="77" required>
		</div>

	@endif


