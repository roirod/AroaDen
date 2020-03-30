<div class="form-group col-sm-3"> 
	<label class="control-label text-left mar10">{{ Lang::get('aroaden.name') }}</label>
	@if ($is_create_view)

		<input type="text" class="form-control" name="name" value="{{ old('name') }}" maxlength="111" 
		@if($autofocus == 'name') autofocus @endif required>

	@else

		<input type="text" class="form-control" name="name" value="{!! $object->name !!}" maxlength="111" 
		@if($autofocus == 'hour') autofocus @endif required> 

	@endif
</div>