
  @if (!$is_create_view)

    <div class="col-sm-12 pad10">
      @include('form_fields.show.name')
    </div>

  @endif

  @include('form_fields.create_edit')