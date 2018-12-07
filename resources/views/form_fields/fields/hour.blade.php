<div class="form-group col-sm-3">
 	<label class="control-label text-left mar10">{{ @trans('aroaden.hour') }}</label> 
	@if ($is_create_view)

		<input type="time" name="hour" step="300" class="form-control" 
		@if($autofocus == 'hour') autofocus @endif required>

	@else

		<input type="time" name="hour" value="{!! substr($object->hour, 0, -3) !!}" step="300" class="form-control" 
		@if($autofocus == 'hour') autofocus @endif required>

	@endif
</div>