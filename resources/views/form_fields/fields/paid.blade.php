 <div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ @trans('aroaden.paid') }}</label>
	@if ($is_create_view)

		<input type="text" name="paid" pattern="[0-9]{1,10}" class="form-control" required> 

	@else

		<input type="text" name="paid" value="{{ $object->paid }}" pattern="[0-9]{1,10}" class="form-control" required> 

	@endif
 </div>