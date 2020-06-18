  @include('includes.services_nav')

  @include('includes.messages')

  <div class="row">
    <div class="col-sm-10">
      <fieldset>
        <legend>
          {!! @trans('aroaden.edit_service') !!}
        </legend>

        <form id="form" class="editServiceForm form" action="{{ url($routes['services']."/$id") }}" method="POST">
        	<input type="hidden" name="_method" value="PUT">

          @include('form_fields.common_alternative')
        </form>
      </fieldset>
    </div>
  </div>    

  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $('form.editServiceForm').on('submit', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        price = $(selector).val().trim();
        util.validateCurrency(price);

        var obj = {
          url  : $(this).attr('action'),
          data : $(this).serialize()
        };

        lastRoute = routes.services + '/ajaxIndex';

        util.processAjaxReturnsJson(obj).done(function(response) {
          if (response.error)
            return util.showPopup(response.msg, false);

          var obj = {
            url  : lastRoute
          };

          util.processAjaxReturnsHtml(obj);
          return util.showPopup();
        });

      });
    });
  </script>