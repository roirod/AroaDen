<div class="form-group col-sm-2">
  <label class="control-label text-left mar10">{{ @trans('aroaden.day') }}</label>
  <div class='input-group date' id='datepicker1'>

	@if ($is_create_view)
        
    <input type='text' name="day" class="form-control" required/>

	@else

    @php
      $day = convertYmdToDmY($object->day);
    @endphp

		<input type="text" name="day" value="" class="form-control" required>

    <script type="text/javascript">
        $(document).ready(function() {
          $('input[name="day"]').val('{{ $day }}');
        });
    </script>

	@endif

    <span class="input-group-addon">
      <span class="glyphicon glyphicon-calendar"></span>
    </span>
  </div>
</div>