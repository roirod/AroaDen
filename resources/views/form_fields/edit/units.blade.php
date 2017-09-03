<div class="form-group col-sm-2">
	<label class="control-label text-left mar10">Cantidad:</label>          
	@if( $autofocus == 'units' )
		<input type="number" min="1" step="1" name="units" value="{{ $object->units }}" class="form-control" autofocus required>
	@else
		<input type="number" min="1" step="1" name="units" value="{{ $object->units }}" class="form-control" required>
	@endif
</div>