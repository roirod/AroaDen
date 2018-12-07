<div class="form-group col-sm-3">
  <label class="control-label text-left mar10">{{ @trans('aroaden.day') }}</label>
	@if ($is_create_view)

		<input type="date" name="day" class="form-control" required>

	@else

		<input type="date" name="day" value="{!! $object->day !!}" class="form-control" required>

	@endif
</div>