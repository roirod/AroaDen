 <div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ @trans('aroaden.paid') }}</label>
	@if ($is_create_view)

		<input type="text" name="paid" pattern="^\d*(\.\d{0,2})?$" class="form-control" required>

	@else

		<input type="text" name="paid" value="{{ $object->paid }}" pattern="^\d*(\.\d{0,2})?$" class="form-control" required> 

	@endif
 </div>