<div class="form-group col-sm-3">
	<label class="control-label text-left mar10">{{ @trans('aroaden.birth') }}</label>
	<br>
	@if( isset($object->birth) ) 
		<input type="date" name="birth" value="{!! $object->birth !!}">
	@else
		<input type="date" name="birth">
	@endif
</div>