  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/forgetChanges.js') !!}"></script>

  <script>
    
    $(document).ready(function() {
      $('a[href="{{ url("/$company_route") }}"]').on('click', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        var obj = {      
          url  : $(this).attr('href') + '/ajaxIndex'
        };

        return util.processAjaxReturnsHtml(obj);
      }); 

      $("#edit_button").on('click', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        var obj = {      
          url  : $(this).attr('href')
        };

        return util.processAjaxReturnsHtml(obj);
      });

      $("form#form").on('submit', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        var obj = {  
          url  : $(this).attr('action'),
          data : $(this).serialize(),
          method  : 'POST',
          popup: true          
        };

        return util.processAjaxReturnsHtml(obj);
      }); 
    });

  </script>