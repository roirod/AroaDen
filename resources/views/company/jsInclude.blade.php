  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/forgetChanges.js') !!}"></script>

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

        return Module.processAjax(obj);
      }); 

      $("#edit_button").on('click', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        var obj = {
          id  : 'ajax_content',          
          url  : $(this).attr('href')     
        };

        return Module.processAjax(obj);
      });

      $("form#form").on('submit', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        var obj = {
          id  : 'ajax_content',          
          url  : $(this).attr('action'),
          data : $(this).serialize(),
          type  : 'POST',
          popup: true          
        };

        return Module.processAjax(obj);
      }); 

      var Module = (function( window, undefined ) {
        function processAjax(obj) {
          util.processAjaxReturnsHtml(obj);
        }

        return {
          processAjax: function(obj) {
            processAjax(obj);
          }  
        }
      })(window);

    });

  </script>