
      <link href="{!! asset('assets/css/datatables.min.css') !!}" rel="stylesheet" type="text/css">
      <script type="text/javascript" src="{!! asset('assets/js/datatables.min.js') !!}"></script>

      <script type="text/javascript">
        
        var tableObj = {
          "sDom": 
            "<'row'<'col-sm-5'l><'col-sm-7'f>>" +
            "<'row'<'col-sm-12'r>>" +
            "<'row'<'col-sm-7'i><'col-sm-5'p>>" +
            "<'row'<'col-sm-12't>>" +
            "<br>" +
            "<'row'<'col-sm-7'i><'col-sm-5'p>>",
          "iDisplayStart": 0,
          "iDisplayLength": 15,
          "bAutoWidth": false,
          'bPaginate': true,
          'bLengthChange': true,
          "sPaginationType": "full_numbers",
          "bProcessing": true,
          "bServerSide": true,
          "sAjaxSource": "{!! $main_route !!}/list",
          "sServerMethod": "GET",
          "aLengthMenu": [
            [15, 25, 50, 100],
            [15, 25, 50, 100]
          ]
        };

      </script>