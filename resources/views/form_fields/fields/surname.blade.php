<div class="form-group col-sm-4"> 
	<label class="control-label text-left mar10">{{ Lang::get('aroaden.surname') }}</label>
	@if ($is_create_view)

		<input type="text" class="form-control" name="surname" value="{{ old('surname') }}" maxlength="111" 
		@if( $autofocus == 'surname' ) autofocus @endif required> 

	@else

		<input type="text" class="form-control" name="surname" value="{!! $object->surname !!}" maxlength="111" 
		@if($autofocus == 'hour') autofocus @endif required> 

	@endif
</div>