<div class="form-group col-sm-2">
 <label class="control-label text-left mar10">{{ @trans('aroaden.sex') }}</label>
 <select name="sex" class="form-control" required>    
	@if( $object->sex == 'male' )
		<option value="male" selected>{{ @trans('aroaden.male') }}</option>
		<option value="female">{{ @trans('aroaden.female') }}</option> 
	@else
		<option value="male">{{ @trans('aroaden.male') }}</option>
		<option value="female" selected>{{ @trans('aroaden.female') }}</option> 
	@endif	        
</select> </div>