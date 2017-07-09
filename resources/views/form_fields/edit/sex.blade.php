<div class="form-group col-sm-2">  <label class="control-label text-left mar10">Sexo:</label>
 <select name="sex" class="form-control" required>    
	@if( $object->sex == 'hombre' )
		<option value="hombre" selected> hombre </option>
		<option value="mujer"> mujer </option> 
	@else
		<option value="hombre"> hombre </option>
		<option value="mujer" selected> mujer </option> 
	@endif	        
</select> </div>