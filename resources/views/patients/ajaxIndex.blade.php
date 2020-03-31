  <div class="row"> 
    <div class="col-sm-12"> 
      <div class="input-group"> 
        <span class="input-group-btn pad10">  
        	<p>{{ Lang::get('aroaden.patient') }}</p>
        </span>
        <div class="btn-toolbar pad4" role="toolbar"> 
          <div class="btn-group">
            <a href="{{ url("/$main_route/create") }}" role="button" class="btn btn-sm btn-primary">
              <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
            </a>
          </div>  
  </div> </div> </div> </div>
  	
  <div class="row">
  	<div class="col-sm-12">
  	  <div class="panel panel-default">
        <table class="table table-striped table-bordered table-hover" id="PatientsTable">
          <thead>
        	  <tr class="fonsi15">
              <td class="wid290"></td>
        			<td class="wid290">{{ Lang::get('aroaden.name') }}</td>
        			<td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
        			<td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
        			<td class="wid230">{{ Lang::get('aroaden.city') }}</td>
        		</tr>
          </thead>
          <tfoot>
            <tr class="fonsi15">
              <td class="wid290"></td>
              <td class="wid290">{{ Lang::get('aroaden.name') }}</td>
              <td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
              <td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
              <td class="wid230">{{ Lang::get('aroaden.city') }}</td>
             </tr>
          </tfoot>  
        </table>
		  </div>
    </div> 
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      setTimeout(function(){
        $("#PatientsTable").dataTable(PatientsTable);
      }, 180);

      var PatientsTable = {
        "aaSorting": [[ 1, "asc" ]],
        'oLanguage': {
          'sProcessing': 'Procesando...',
          'sLengthMenu': 'Selecciona _MENU_',
          'sZeroRecords': 'Pacientes no encontrados',
          'sInfo': 'De _START_ hasta _END_ de _TOTAL_ pacientes',
          'sInfoEmpty': 'No hay pacientes',
          'sInfoFiltered': '(filtrados de _MAX_ total de pacientes)',
          'sSearch': 'Buscar:',
          "oPaginate": {
            "sFirst":    "❮❮",
            "sLast":     "❯❯",
            "sNext":     "❯",
            "sPrevious": "❮"
          },
        },
        "sDom": 
          "<'row'<'col-sm-5'l><'col-sm-7'f>>" +
          "<'row'<'col-sm-12'r>>" +
          "<'row'<'col-sm-7'i><'col-sm-5'p>>" +
          "<'row'<'col-sm-12't>>" +
          "<br>" +
          "<'row'<'col-sm-7'i><'col-sm-5'p>>",
        "iDisplayStart": 0,
        "iDisplayLength": iDisplayLength,
        "bAutoWidth": false,
        'bPaginate': true,
        'bLengthChange': true,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "{!! $patients_route !!}/list",
        "sServerMethod": "GET",
        "aLengthMenu": aLengthMenu,
        "aoColumnDefs": [
          {
            "aTargets": [0],
            "bSortable": false,
            "bSearchable": false,
            "bVisible": false
          },
          {
            "aTargets": [1],
            "mRender": function (data, type, full) {
              var resultado = '<a href="{!! $patients_route !!}/'+ full[0] +'" class="pad4" target="_blank">'+ full[1] +'</a>';
              return resultado;
            }
          }
        ],
      };
    });
  </script>
