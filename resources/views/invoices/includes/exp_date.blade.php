
<div class="form-group col-sm-2">
  <label class="control-label text-left mar10">{{ @trans('aroaden.exp_date') }}</label>
  <div class='input-group date' id='datepicker1'>

  @if ($is_create_view)
        
    <input type='text' name="exp_date" class="form-control" required/>

  @else

    @php
      $exp_date = convertYmdToDmY($exp_date);
    @endphp

    <input type="text" name="exp_date" value="" class="form-control" required>

    <script type="text/javascript">
      $(document).ready(function() {
        $('input[name="exp_date"]').val('{{ $exp_date }}');
      });
    </script>

  @endif

    <span class="input-group-addon">
      <span class="glyphicon glyphicon-calendar"></span>
    </span>
  </div>
</div>