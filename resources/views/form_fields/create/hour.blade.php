<div class="form-group col-sm-3">
 	<label class="control-label text-left mar10">{{ @trans('aroaden.hour') }}</label> 
	@if( $autofocus == 'hour' )
		<input type="time" name="hour" step="300" class="form-control" autofocus required>
	@else
		<input type="time" name="hour" step="300" class="form-control" required>
	@endif  
</div>