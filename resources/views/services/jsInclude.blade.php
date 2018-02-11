<script>
  $(document).ready(function() {

    $.ajaxSetup({
        headers: { 
          'X-CSRF-Token' : $('meta[name=_token]').attr('content')
        }
    });

    $('a[href="{{ url("/$main_route") }}"]').on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var obj = {
        id  : 'ajax_content',          
        url  : $(this).attr('href') + '/ajaxIndex'
      };

      return util.processAjaxReturnsHtml(obj);
    });

    $('a.editService').on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var obj = {
        id  : 'ajax_content',
        url  : $(this).attr('href')
      };

      return util.processAjaxReturnsHtml(obj);
    }); 

    $("form#form").on('submit', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var obj = {
        id  : 'ajax_content',          
        url  : $(this).attr('action'),
        data : $(this).serialize(),
        type  : 'POST'     
      };

      return util.processAjaxReturnsHtml(obj);
    });

    $("#string").on('keyup change', function(event) {
      var string_val = $(this).val();
      var string_val_length = string_val.length;

      if (string_val != '' && string_val_length > 1) {
        if ((event.which <= 90 && event.which >= 48) || event.which == 8 || event.which == 46 || event.which == 173) {
          Module.runApp();
        }
      }

      event.preventDefault();
      event.stopPropagation();
    });

    var Module = (function( window, undefined ){
      function runApp() {
        util.showLoadingGif('item_list');

        var data = $("form#string_form").serialize();

        var obj = {
          data  : data,          
          url  : '/{!! $main_route !!}/{!! $form_route !!}'
        };

        util.processAjaxReturnsJson(obj).done(function(response) {
          var html = '';

          if (response.error) {

            html = '<p class="text-danger">' + response.msg + '</p>';
            $('#item_list').empty();
            $('#item_list').hide().html(html).fadeIn('slow');

          } else {

            response["main_route"] = "{!! $main_route !!}";

            var templateHandlebars = $("#templateHandlebars").html();
            var compileTemplate = Handlebars.compile(templateHandlebars);
            var compiledHtml = compileTemplate(response);

            $('#item_list').hide().html(compiledHtml).fadeIn('slow');
          }

          $('#searched').prepend(' <span class="label label-primary">{{ Lang::get('aroaden.searched_text') }} ' + $('#string').val() + '</span>');

        }).fail(function() {

          $('#item_list').empty();
          $('#item_list').hide().html('<h3>{{ Lang::get('aroaden.error_message') }}</h3>').fadeIn('slow');
          
        });

      }

      return {
        runApp: function() {
          runApp();
        }
      }

    })(window);

  });

</script>