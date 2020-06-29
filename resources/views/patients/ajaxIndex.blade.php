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
  	<div class="col-sm-10">
  	  <div class="panel panel-default">
        <table class="table table-striped table-bordered table-hover" id="PatientsTable">
          <thead>
        	  <tr class="fonsi14">
              <td class="wid230"></td>
        			<td class="wid230">{{ Lang::get('aroaden.name') }}</td>
        			<td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
        			<td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
        			<td class="wid230">{{ Lang::get('aroaden.city') }}</td>
        		</tr>
          </thead>
          <tfoot>
            <tr class="fonsi14">
              <td class="wid230"></td>
              <td class="wid230">{{ Lang::get('aroaden.name') }}</td>
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
        $("#PatientsTable").dataTable(tableObj);
      }, 180);

      tableObj.aaSorting = [[1, "asc"]];

      tableObj.oLanguage = {
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
        }
      };

      tableObj.aoColumnDefs = [
        {
          "aTargets": [0],
          "bSortable": false,
          "bSearchable": false,
          "bVisible": false
        },
        {
          "aTargets": [1],
          "mRender": function (data, type, full) {
            var resultado = '<a href="{!! $routes['patients'] !!}/'+ full[0] +'" class="pad4" target="_blank">'+ full[1] +'</a>';
            return resultado;
          }
        }
      ];

    });

  </script>
