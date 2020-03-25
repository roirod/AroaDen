@include('includes.services_nav')

<div class="row">
  <div class="col-sm-10">
    <fieldset>
      <legend>
        {!! @trans('aroaden.create_service') !!}
      </legend>

      <form id="form" class="createServiceForm form" action="{!! $services_route !!}" method="post">
        @include('form_fields.common_alternative')

      @include('form_fields.fields.closeform')
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
        data : $(this).serialize(),
      };

      lastRoute = routes.services_route + '/ajaxIndex';

      util.processAjaxReturnsJson(obj).done(function(response) {
        if (response.error) {

          return util.showPopup(response.content, false);

        } else {

          var obj = {
            url  : lastRoute
          };

          util.processAjaxReturnsHtml(obj);
          return util.showPopup("{{ Lang::get('aroaden.success_message') }}");

        }
      });
    });
  });
</script>
