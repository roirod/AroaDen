	@if ($is_create_view || (isset($object) && $object->type == 'basic'))

		<div class="form-group col-lg-10">
			<label class="control-label text-left mar10">{{ @trans('aroaden.permissions') }}</label> 
			<select name="type" class="form-control" required>
				<option value="basic" selected="selected">{{ @trans('aroaden.basic') }}</option>
				<option value="normal">{{ @trans('aroaden.normal') }}</option>
			</select>
		</div>

	@elseif (isset($object) && $object->type == 'normal' && $object->username != 'admin')

		<div class="form-group col-lg-10">
			<label class="control-label text-left mar10">{{ @trans('aroaden.permissions') }}</label> 
			<select name="type" class="form-control" required>
		  		<option value="basic">{{ @trans('aroaden.basic') }}</option>
		  		<option value="normal" selected="selected">{{ @trans('aroaden.normal') }}</option>
			</select>
		</div>

	@endif

