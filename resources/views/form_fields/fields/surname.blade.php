<div class="form-group col-sm-3"> 
	<label class="control-label text-left mar10">{{ Lang::get('aroaden.surname') }}</label>
	@if ($is_create_view)

		<input type="text" class="form-control" name="surname" value="{{ old('surname') }}" @if( $autofocus == 'surname' ) autofocus @endif> 

	@else

		<input type="text" class="form-control" name="surname" value="{!! $object->surname !!}" @if($autofocus == 'hour') autofocus @endif> 

	@endif
</div>