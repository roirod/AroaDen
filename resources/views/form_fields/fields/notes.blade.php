<div class="form-group col-sm-12">
  <label class="control-label text-left mar10">{{ @trans('aroaden.notes') }}</label>
	@if ($is_create_view)

		<textarea class="form-control" name="notes" rows="3"></textarea>

	@else

		<textarea class="form-control" name="notes" rows="3">{!! $object->notes !!}</textarea>

	@endif
</div>