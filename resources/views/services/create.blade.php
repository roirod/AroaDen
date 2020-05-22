@include('includes.services_nav')

@include('includes.messages')

<div class="row">
  <div class="col-sm-10">
    <fieldset>
      <legend>
        {!! @trans('aroaden.create_service') !!}
      </legend>

      <form id="form" class="createServiceForm form" action="{!! $routes['services'] !!}" method="post">

        @include('form_fields.common_alternative')

      </form>

    </fieldset>
  </div>
</div>

<script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('form.createServiceForm').on('submit', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var obj = {
        url  : $(this).attr('action'),
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
