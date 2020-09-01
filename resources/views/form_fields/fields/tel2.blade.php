<div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ @trans('aroaden.tele2') }}</label>

	@if ($is_create_view)

		<input type="text" class="form-control" name="tel2" value="{{ old('tel2') }}">

	@else

		<input type="text" class="form-control" name="tel2" value="{!! $object->tel2 !!}">

	@endif
</div>