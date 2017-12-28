<div class="form-group col-sm-4"> 
	<label class="control-label text-left mar10">{{ Lang::get('aroaden.name') }}</label>
	@if( $autofocus == 'name' )
		<input type="text" class="form-control" name="name" value="{{ old('name') }}" maxlength="111" autofocus required>
	@else
		<input type="text" class="form-control" name="name" value="{{ old('name') }}" maxlength="111" required>
	@endif
</div>