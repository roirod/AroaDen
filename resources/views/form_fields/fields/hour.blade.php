<div class="form-group col-sm-2">
 	<label class="control-label text-left mar10">{{ @trans('aroaden.hour') }}</label>
    <div class='input-group date' id='timepicker1'>
        
	@if ($is_create_view)

		<input type="text" name="hour" class="form-control" @if($autofocus == 'hour') autofocus @endif required>

	@else

		<input type="text" name="hour" value="{!! substr($object->hour, 0, -3) !!}" class="form-control" @if($autofocus == 'hour') autofocus @endif required>

	@endif

        <span class="input-group-addon">
            <span class="glyphicon glyphicon-time"></span>
        </span>
    </div>
</div>