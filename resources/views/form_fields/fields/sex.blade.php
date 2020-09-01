<div class="form-group col-sm-2">
 	<label class="control-label text-left mar10">{{ @trans('aroaden.sex') }}</label>
 	<select name="sex" class="form-control" required>
	@if ($is_create_view)

 		<option value="male" selected>{{ @trans('aroaden.male') }}</option>
  	<option value="female">{{ @trans('aroaden.female') }}</option> 

	@else

		<option value="male" @if( $object->sex == 'male' ) selected @endif>{{ @trans('aroaden.male') }}</option>
		<option value="female" @if( $object->sex == 'female' ) selected @endif>{{ @trans('aroaden.female') }}</option> 

	@endif
   </select>
</div>