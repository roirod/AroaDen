@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

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
    <fieldset>
  	  <div class="panel panel-default">

        <table class="table table-hover stripe" id="PatientsTable">
          <thead>
        	  <tr class="fonsi15 bgtra fonbla">
        			<td class="wid50">&nbsp;</td>
        			<td class="wid290">{{ Lang::get('aroaden.name') }}</td>
        			<td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
        			<td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
        			<td class="wid230">{{ Lang::get('aroaden.city') }}</td>
        		</tr>
          </thead>
          <tfoot>
            <tr class="fonsi15 bgtra fonbla">
              <td class="wid50">&nbsp;</td>
              <td class="wid290">{{ Lang::get('aroaden.name') }}</td>
              <td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
              <td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
              <td class="wid230">{{ Lang::get('aroaden.city') }}</td>
             </tr>
          </tfoot>  
        </table>					

		  </div>
    </fieldset>
  </div> 
</div>

@endsection

@section('footer_script')

  <link href="{!! asset('assets/css/datatables.min.css') !!}" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="{!! asset('assets/js/datatables.min.js') !!}"></script>

	<script>
		
		$(document).ready(function() {
      setTimeout(function(){
        $("#PatientsTable").dataTable(PatientsTable);
      }, 180);

      var PatientsTable = {
        'oLanguage': {
          'sProcessing': 'Cargando...',
          'sLengthMenu': 'Mostrar _MENU_',
          'sZeroRecords': 'Pacientes no encontrados',
          'sInfo': 'Mostrando desde _START_ hasta _END_ de _TOTAL_ pacientes',
          'sInfoEmpty': 'No hay pacientes',
          'sInfoFiltered': '(filtrados de _MAX_ total de pacientes)',
          'sSearch': 'Búsqueda:',
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
        "iDisplayLength": 25,
        "bAutoWidth": false,
        'bPaginate': true,
        'bLengthChange': true,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "{!! $patients_route !!}/list",
        "fnServerData": function ( sSource, aoData, fnCallback, oSettings ) {
          oSettings.jqXHR = $.ajax({
            "dataType": 'json',
            "method": "GET",
            'headers': {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            "url": sSource,
            "data": aoData,
            "success": fnCallback,
            "error": function (e) {
              console.dir(e);
              console.log(e.message);
            }
          });
        },
        "aLengthMenu": [
          [25, 50, 100, 500, 1000, 10000, -1],
          [25, 50, 100, 500, 1000, 10000, "Todos"],
        ],
        "aoColumnDefs": [
          {
            "aTargets": [0],
            "mData": null,
            "bSortable": false,
            "bSearchable": false,
            "mRender": function (data, type, full) {
              var resultado = '<a href="{!! $patients_route !!}/'+ full[0] +'" target="_blank" class="btn btn-default btn-sm" role="button"><i class="fa fa-hand-pointer-o"></i></a>';
              return resultado;
            }
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

@endsection