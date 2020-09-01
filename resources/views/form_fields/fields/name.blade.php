<div class="form-group col-sm-3"> 
	<label class="control-label text-left mar10">{{ Lang::get('aroaden.name') }}</label>
	@if ($is_create_view)

		<input type="text" class="form-control" name="name" value="{{ old('name') }}" @if($autofocus == 'name') autofocus @endif>

	@else

		<input type="text" class="form-control" name="name" value="{!! $object->name !!}"	@if($autofocus == 'hour') autofocus @endif> 

	@endif
</div>