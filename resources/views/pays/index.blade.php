@extends('layouts.main')

@section('content')

  @include('includes.accounting_nav')

  @include('includes.messages')
  @include('includes.errors')

  <div class="row">
  	<div class="col-sm-12">
      <fieldset>
    	  <div class="panel panel-default">

          <table class="table table-hover stripe" id="PaysTable">
            <thead>
          	  <tr class="fonsi15 bgtra fonbla">
    		        <td class="wid50"></td>
    		        <td class="wid290">Paciente</td>
    		        <td class="wid110 textcent">Total</td>
    		        <td class="wid110 textcent">Pagado</td> 
    		        <td class="wid110 textcent">Resto</td>
          		</tr>
            </thead>
            <tfoot>
              <tr class="fonsi15 bgtra fonbla">
    		        <td class="wid50"></td>
    		        <td class="wid290">Paciente</td>
    		        <td class="wid110 textcent">Total</td>
    		        <td class="wid110 textcent">Pagado</td> 
    		        <td class="wid110 textcent">Resto</td>
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

  <script type="text/javascript">		
	$(document).ready(function() {
      setTimeout(function(){
        $("#PaysTable").dataTable(PaysTable);
      }, 180);

      var PaysTable = {
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
          "<'row'<'col-sm-12'l>>" +
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
        "sAjaxSource": "{!! $main_route !!}/list",
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
            "bSortable": false,
            "bSearchable": false,
            "bVisible": false,    
            "sClass": "wid50"
          },
          {
            "aTargets": [1],
            "bSortable": false,
            "bSearchable": false,
            "sClass": "wid290",
            "mData": null,            
            "mRender": function (data, type, full) {
              var result = '<a href="'+ full[0] +'" class="pad4" target="_blank">'+ full[1] +'</a>';
              return result;
            }
          },
          {
            "aTargets": [2],
            "bSortable": false,
            "bSearchable": false,
            "sClass": "wid110 textcent",
            "mData": null,            
            "mRender": function (data, type, full) {
              var result = full[2] + ' €';
              return result;
            }
          },
          {
            "aTargets": [3],
            "bSortable": false,
            "bSearchable": false,
            "sClass": "wid110 textcent",
            "mData": null,            
            "mRender": function (data, type, full) {
              var result = full[3] + ' €';
              return result;
            }
          },
          {
            "aTargets": [4],
            "bSortable": false,
            "bSearchable": false,
            "sClass": "wid110 textcent",
            "mData": null,            
            "mRender": function (data, type, full) {
              var result = full[4] + ' €';
              return result;
            }
          }
        ],
      };
  	});

	</script>

@endsection
