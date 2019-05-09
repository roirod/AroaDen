<div class="form-group col-sm-2">
  <label class="control-label text-left mar10">{{ @trans('aroaden.birth') }}</label>
  <div class='input-group date' id='datepicker1'>

	@if ($is_create_view)
        
    	<input type='text' name="birth" class="form-control" required/>

	@else

		<input type="text" name="birth" value="{!! convertYmdToDmY($object->birth) !!}" class="form-control" required>

	@endif

    <span class="input-group-addon">
      <span class="glyphicon glyphicon-calendar"></span>
    </span>
  </div>
</div>