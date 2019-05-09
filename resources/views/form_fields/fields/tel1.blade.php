<div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ @trans('aroaden.tele1') }}</label>
	@if ($is_create_view)

		<input type="text" class="form-control" name="tel1" maxlength="11" value="{{ old('tel1') }}">

	@else

		<input type="text" class="form-control" name="tel1" maxlength="11" value="{!! $object->tel1 !!}">

	@endif
</div>