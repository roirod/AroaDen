<div class="form-group col-sm-3">  
	<label class="control-label text-left mar10">{{ @trans('aroaden.city') }}</label>
	@if ($is_create_view)

		<input type="text" class="form-control" name="city" value="{{ old('city') }}" maxlength="111">

	@else

		<input type="text" class="form-control" name="city" value="{!! $object->city !!}" maxlength="111">

	@endif
</div>