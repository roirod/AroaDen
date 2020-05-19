<div class="form-group col-sm-2">
  	<label class="control-label text-left mar10">{{ Lang::get('aroaden.price_no_tax') }}</label>
	@if ($is_create_view)

		<input type="number" min="0" step="1" class="form-control" name="price" maxlength="11" value="0" required>

	@else

		<input type="number" min="0" step="1" class="form-control" name="price" value="{!! $object->price !!}" maxlength="11" required>

	@endif
</div>