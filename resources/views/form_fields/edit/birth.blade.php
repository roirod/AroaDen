<div class="form-group col-sm-4">
	<label class="control-label text-left mar10">{{ @trans('aroaden.birth') }}</label>
	@if( isset($object->birth) ) 
		<input type="date" name="birth" value="{!! $object->birth !!}">
	@else
		<input type="date" name="birth">
	@endif
</div>