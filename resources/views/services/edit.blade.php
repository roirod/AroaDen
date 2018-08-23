
{!! addText("Editar servicio") !!}

<form id="form" class="editServiceForm form" action="{{ url("/$services_route/$id") }}" method="POST">
	{!! csrf_field() !!}

	<input type="hidden" name="_method" value="PUT">

    @include('form_fields.edit_alternative')

@include('form_fields.edit.closeform')

<script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('form.editServiceForm').on('submit', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var obj = {
        url  : $(this).attr('action'),
        data : $(this).serialize(),
        method  : $(this).attr('method')
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