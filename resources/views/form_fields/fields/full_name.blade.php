	@if ($is_create_view)

		<div class="form-group col-sm-11">  
			<label class="control-label text-left mar10">{{ @trans('aroaden.full_name') }}</label>
			<input type="text" class="form-control" name="full_name" value="{{ old('full_name') }}">
		</div>

	@elseif (isset($object) && $object->username != 'admin')

		<div class="form-group col-sm-11">  
			<label class="control-label text-left mar10">{{ @trans('aroaden.full_name') }}</label>
			<input type="text" class="form-control" name="full_name" value="{!! $object->full_name !!}">
		</div>

	@endif