 <div class="form-group col-sm-2">
	<label class="control-label text-left mar10">{{ @trans('aroaden.dni') }}</label>
	<input type="text" class="form-control" name="dni" pattern="[A-Z0-9]{9}" maxlength="9" value="{{ old('dni') }}" required>
</div>