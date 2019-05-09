<div class="form-group col-sm-5">  
	<label class="control-label text-left mar10">{{ @trans('aroaden.address') }}</label>

	@if ($is_create_view)

		<input type="text" class="form-control" name="address" value="{{ old('address') }}" maxlength="111">

	@else

		<input type="text" class="form-control" name="address" value="{!! $object->address !!}" maxlength="111">

	@endif
</div>