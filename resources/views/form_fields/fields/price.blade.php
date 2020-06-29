<div class="form-group col-sm-2">
  <label class="control-label text-left mar10">{{ @trans('aroaden.price_no_tax') }}</label>
	@if ($is_create_view)

		<input type="text" class="form-control" name="price" value="0" required>

	@else

    <input type="text" class="form-control" name="price" value="{{ numformat($object->price) }}" required>

	@endif
</div>