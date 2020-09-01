<div class="form-group col-sm-2">
  <label class="control-label text-left mar10">{{ @trans('aroaden.day') }}</label>
  <div class='input-group date' id='datepicker1'>

	@if ($is_create_view)
        
    <input type="text" name="day" class="form-control"/>

	@else

    <input type="text" name="day" value="{!! convertYmdToDmY($object->day) !!}" class="form-control">

	@endif

    <span class="input-group-addon">
      <span class="glyphicon glyphicon-calendar"></span>
    </span>
  </div>
</div>