<div class="form-group col-sm-9">
  <label class="control-label text-left mar10">{{ @trans('aroaden.notes') }}</label>
  @if ($is_create_view)

    <textarea class="form-control" name="notes" rows="11"></textarea>

  @else

    <textarea class="form-control" name="notes" rows="11">{!! $notes !!}</textarea>

  @endif
</div>