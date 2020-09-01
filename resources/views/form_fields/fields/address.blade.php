<div class="form-group col-sm-4">  
	<label class="control-label text-left mar10">{{ @trans('aroaden.address') }}</label>

	@if ($is_create_view)

		<input type="text" class="form-control" name="address" value="{{ old('address') }}">

	@else

		<input type="text" class="form-control" name="address" value="{!! $object->address !!}">

	@endif
</div>