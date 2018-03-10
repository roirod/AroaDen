@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!! csrf_token() !!}"/>

<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  
      	<p>{{ Lang::get('aroaden.patient') }}</p>
      </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{url("/$main_route/create")}}" role="button" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
          </a>
        </div>  
</div> </div> </div> </div>
	
<div class="row">
	<div class="col-sm-12"> 
	  <div class="panel panel-default">
		 <table class="table table-hover stripe" id="PatientsTable">
        <thead>
  			  <tr class="fonsi15 success">
  					<td class="wid50">&nbsp;</td>
  					<td class="wid290">{{ Lang::get('aroaden.name') }}</td>
  					<td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
  					<td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
  					<td class="wid230">{{ Lang::get('aroaden.city') }}</td>
  				</tr>
        </thead>
        <tfoot>
          <tr class="fonsi15 success">
            <td class="wid50">&nbsp;</td>
            <td class="wid290">{{ Lang::get('aroaden.name') }}</td>
            <td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
            <td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
            <td class="wid230">{{ Lang::get('aroaden.city') }}</td>
           </tr>
        </tfoot>  
		 </table>					

		</div>
 </div> </div>

@endsection

@section('footer_script')

  <link href="{!! asset('assets/css/datatables.min.css') !!}" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="{!! asset('assets/js/datatables.min.js') !!}"></script>

	<script>
		
		$(document).ready(function() {
			$.ajaxSetup({
		   	headers: { 
		   		'X-CSRF-Token' : $('meta[name=_token]').attr('content')
		   	}
			});

			$("#PatientsTable").dataTable(PatientsTable);

      var PatientsTable = {
        'oLanguage': {
          'sProcessing': 'Procesando...',
          'sLengthMenu': 'Mostrar _MENU_ registros',
          'sZeroRecords': 'Registros no encontrados',
          'sInfo': 'Mostrando desde _START_ hasta _END_ de _TOTAL_ registros',
          'sInfoEmpty': 'No hay datos registrados',
          'sInfoFiltered': '(filtrados de _MAX_ total de entradas)',
          'sSearch': 'Búsqueda rápida:',
          "oPaginate": {
            "sFirst":    "❮❮",
            "sLast":     "❯❯",
            "sNext":     "❯",
            "sPrevious": "❮"
          },
        },
        'bPaginate': true,
        'bLengthChange': true,
        "sPaginationType": "full_numbers",
        "bProcessing": true,
        "sDom": "<'row'<'col-sm-6'lfipr><'col-sm-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "bServerSide": true,
        "iDisplayStart": 0,
        "iDisplayLength": 25,
        "bAutoWidth": false,
        "sAjaxSource": "/{!! $main_route !!}/{!! $form_route !!}",        
        "sServerMethod": "POST",
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
              var resultado = '<a href="{!! url("/$main_route") !!}/'+ full[0] +'" target="_blank" class="btn btn-default" role="button"><i class="fa fa-hand-pointer-o"></i></a>';
              return resultado;
            }
          },
          {
            "aTargets": [1],
            "mRender": function (data, type, full) {
              var resultado = '<a href="{!! url("/$main_route") !!}/'+ full[0] +'" class="pad4" target="_blank">'+ full[1] +'</a>';
              return resultado;
            }
          }
        ],

      };
  	});

	</script>

@endsection