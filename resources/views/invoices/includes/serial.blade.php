
<div class="form-group col-sm-2"> 
  <label class="control-label text-left mar10">{{ Lang::get('aroaden.serial') }}</label>

  @if ($is_create_view)

    <input class="form-control" type="number" name="serial" min="0" max="{{ date('Y') + 100 }}" step="1" value="{{ date('Y') }}" required>

  @else

    <input class="form-control" type="number" name="serial" min="0" max="{{ date('Y') + 100 }}" step="1" value="{{ $serial }}" required>

  @endif
</div>