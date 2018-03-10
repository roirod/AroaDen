<script>
  $(document).ready(function() {

    $.ajaxSetup({
      headers: { 
        'X-CSRF-Token' : $('meta[name=_token]').attr('content')
      }
    });

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

    $('form.serviceForm').on('submit', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var _this = $(this);

      processReturnsJson(_this);
    });

    function processReturnsJson(_this) {
      var obj = {     
        url  : _this.attr('action'),
        data : _this.serialize(),
        type  : _this.attr('method'),
      };

      lastRoute = routes.services_route + '/ajaxIndex';

      util.processAjaxReturnsJson(obj).done(function(response) {
        if (response.error) {

          util.showPopup(response.content, false);

        } else {

          var obj = {
            url  : lastRoute
          };

          util.processAjaxReturnsHtml(obj);
          util.showPopup("{{ Lang::get('aroaden.success_message') }}");

        }
      });
    }

    $("#string").on('keyup change', function(event) {
      var string_val = $(this).val();
      var string_val_length = string_val.length;

      if (string_val != '' && string_val_length > 1) {
        if ((event.which <= 90 && event.which >= 48) || event.which == 8 || event.which == 46 || event.which == 173) {
          Module.findService();
        }
      }

      event.preventDefault();
      event.stopPropagation();
    });

    var Module = (function( window, undefined ){
      function findService() {
        util.showLoadingGif('item_list');

        var data = $("form#string_form").serialize();

        var obj = {
          data  : data,          
          url  : '/{!! $services_route !!}/{!! $form_route !!}'
        };

        util.processAjaxReturnsJson(obj).done(function(response) {
          if (response.error)
            return util.showContentOnPage('item_list', response.msg, true);

          util.renderTemplateOnPage('item_list', 'servicesList', response);
          util.showSearchText();
        });
      }

      return {
        findService: function() {
          findService();
        }
      }

    })(window);

  });

</script>