<script>
  $(document).ready(function() {
    $('a[href="{{ url("/$services_route") }}"]').on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var obj = {         
        url  : $(this).attr('href') + '/ajaxIndex'
      };

      return util.processAjaxReturnsHtml(obj);
    });

    $('a[href="{{ url("/$services_route/create") }}"]').on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var obj = {   
        url  : $(this).attr('href')
      };

      return util.processAjaxReturnsHtml(obj);
    });

    $('a.editService').on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      lastRoute = routes.services_route + '/ajaxIndex';

      var obj = {
        url  : $(this).attr('href')
      };

      return util.processAjaxReturnsHtml(obj);
    });

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

    $("#string").on('keyup change', function(event) {
      event.preventDefault();
      event.stopPropagation();

      var string_val = $(this).val();
      var string_val_length = string_val.length;

      if (string_val != '' && string_val_length > 1) {
        if ((event.which <= 90 && event.which >= 48) || event.which == 8 || event.which == 46 || event.which == 173) {
          var data = $("form#searchService").serialize();

          var obj = {
            data  : data,
            url  : '{!! $services_route !!}/search',
            id  : 'item_list'
          };

          util.processAjaxReturnsHtml(obj);
        }
      }
    });

  });
</script>