<div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ Lang::get('aroaden.tax') }}</label>
	<select name="tax" class="form-control" required>
 
		@foreach ($tax_types as $clave => $valor) 
			 <option value="{{$valor}}">{{$clave}}</option>
		@endforeach
 
	</select>
</div>