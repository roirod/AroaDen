 <div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ @trans('aroaden.dni') }}</label>
	@if ($is_create_view)

		<input type="text" class="form-control" name="dni" value="{{ old('dni') }}" maxlength="9">

	@else

		<input type="text" class="form-control" name="dni" value="{!! $object->dni !!}" maxlength="9">

	@endif
</div>