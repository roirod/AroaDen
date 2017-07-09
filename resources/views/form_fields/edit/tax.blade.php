<div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ Lang::get('aroaden.tax') }}</label>
	<select name="tax" class="form-control" required>
 
		@foreach ($tax_types as $clave => $valor)  	
			 
			@foreach ($tax_types as $clave => $valor)

				@if ($object->tax == $valor)

					<option value="{{$valor}}" selected> {{$clave}} </option>

				@else

					<option value="{{$valor}}"> {{$clave}} </option>

				@endif

			@endforeach

		@endforeach
 
	</select>
</div>