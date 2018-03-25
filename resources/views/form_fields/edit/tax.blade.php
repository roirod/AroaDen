<div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ Lang::get('aroaden.tax') }}</label>
	<select name="tax" class="form-control" required>
		 
		@foreach ($tax_types as $key => $value)

			@if ($object->tax == $value)

				<option value="{{ $value }}" selected>{{ $key }}</option>

			@else

				<option value="{{ $value }}">{{ $key }}</option>

			@endif

		@endforeach
 
	</select>
</div>