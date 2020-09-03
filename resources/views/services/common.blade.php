  
  <div class="row">
    <div class="col-sm-12">
      <fieldset>

        <legend>
          {!! $legend !!}
        </legend>

        @include('form_fields.show.form_errors')

        <form class="form saveform">
          {!! csrf_field() !!}

          @if (!$is_create_view)
            <input type="hidden" name="_method" value="PUT">
          @endif

          @include('form_fields.common_alternative')
        </form>

      </fieldset>
    </div>
  </div>

  @if ($is_create_view)

    <script type="text/javascript">
      var rq_url = '{!! url("/$main_route") !!}';
    </script>

  @else

    <script type="text/javascript">
      var rq_url = '{!! url("/$main_route/$id") !!}';
    </script>

  @endif

  <script type="text/javascript">
    $(document).ready(function() {
      $('form.saveform').on('submit', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        price = $(selector).val().trim();
        util.validateCurrency(price);

        var obj = {
          url  : rq_url,
          data : $(this).serialize()
        };

        util.processAjaxReturnsJson(obj).done(function(response) {
          if (response.error)
            return util.showPopup(response.msg, false);

          var obj = {
            url  : routes.services + '/ajaxIndex'
          };

          util.processAjaxReturnsHtml(obj);
          return util.showPopup();
        });
      });
    });
  </script>



