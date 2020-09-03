<div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ @trans('aroaden.units') }}</label>
	@if ($is_create_view)

		<input type="number" min="1" value="1" step="1" name="units" class="form-control" @if( $autofocus == 'units' ) autofocus @endif required>

	@else

		<input type="number" min="1" step="1" name="units" value="{{ $object->units }}" class="form-control" @if( $autofocus == 'units' ) autofocus @endif required>

	@endif
</div>