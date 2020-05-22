<div class="form-group col-sm-2">
  <label class="control-label text-left mar10">{{ Lang::get('aroaden.price_no_tax') }}</label>
	@if ($is_create_view)

		<input type="number" min="0.00" step="any" pattern="^\d*(\.\d{0,2})?$" class="form-control" name="price" value="0.00" required>

	@else

    <input type="number" min="0.00" step="any" pattern="^\d*(\.\d{0,2})?$" class="form-control" name="price" value="{!! $object->price !!}" required>

	@endif
</div>