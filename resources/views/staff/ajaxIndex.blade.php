
  @include('includes.staff_nav')

  @include('includes.messages')
  
	<div class="row"> 
	  <div class="col-sm-12"> 
	    <div class="input-group"> 
	      <span class="input-group-btn pad10">  
	      	<p>{{ Lang::get('aroaden.staff') }}</p>
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
        <table class="table table-striped table-bordered table-hover" id="staffTable">
          <thead>
        	  <tr class="fonsi14">
              <td class="wid110"></td>          	  
        			<td class="wid230">{{ Lang::get('aroaden.name') }}</td>
        			<td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
        			<td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
        			<td class="wid350">{{ Lang::get('aroaden.positions') }}</td>
        		</tr>
          </thead>
          <tfoot>
            <tr class="fonsi14">
              <td class="wid110"></td>
              <td class="wid230">{{ Lang::get('aroaden.name') }}</td>
              <td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
              <td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
              <td class="wid350">{{ Lang::get('aroaden.positions') }}</td>
             </tr>
          </tfoot>  
        </table>
	    </div>
    </div> 
  </div>
 
  <script type="text/javascript">
    $(document).ready(function() {

      setTimeout(function(){
        $("#staffTable").dataTable(tableObj);
      }, 180);

      tableObj.aaSorting = [[1, "asc"]];

      tableObj.oLanguage = {
        'sProcessing': 'Procesando...',
        'sLengthMenu': 'Selecciona _MENU_',
        'sZeroRecords': '{{ @trans('aroaden.no_query_results') }}',
        'sInfo': 'De _START_ hasta _END_ de _TOTAL_ profesionales',
        'sInfoEmpty': '{{ @trans('aroaden.empty_db') }}',
        'sInfoFiltered': '(filtrados de _MAX_ total de profesionales)',
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
            var resultado = '<a href="{!! $routes['staff'] !!}/'+ full[0] +'" class="pad4" target="_blank">'+ full[1] +'</a>';
            return resultado;
          }
        }
      ];

    });

  </script>