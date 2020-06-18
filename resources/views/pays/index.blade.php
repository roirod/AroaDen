@extends('layouts.main')

@section('content')

  @include('includes.accounting_nav')

  @include('includes.messages')
  
  <div class="row">
  	<div class="col-sm-9">
  	  <div class="panel panel-default">
        <table class="table table-striped table-bordered table-hover" id="PaysTable">
          <thead>
            <tr class="fonsi15">
  		        <td class="wid50"></td>
  		        <td class="wid290">Paciente</td>
  		        <td class="wid110 textcent">Total</td>
  		        <td class="wid110 textcent">Pagado</td> 
              <td class="wid110 textcent text-danger danger">Resto</td>
        		</tr>
          </thead>
          <tfoot>
            <tr class="fonsi15">
  		        <td class="wid50"></td>
  		        <td class="wid290">Paciente</td>
  		        <td class="wid110 textcent">Total</td>
  		        <td class="wid110 textcent">Pagado</td> 
  		        <td class="wid110 textcent text-danger danger">Resto</td>
            </tr>
          </tfoot>  
        </table>					
		  </div>
    </div> 
  </div>

@endsection

@section('footer_script')

  <script type="text/javascript">		
    $(document).ready(function() {

      setTimeout(function(){
        $("#PaysTable").dataTable(tableObj);
      }, 180);

      tableObj.aaSorting = [[4, "desc"]];

      tableObj.oLanguage = {
        'sProcessing': 'Procesando...',
        'sLengthMenu': 'Selecciona _MENU_',
        'sZeroRecords': 'No hay registros.',
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
          "bVisible": false,    
          "sClass": "wid50"
        },
        {
          "aTargets": [1],
          "sClass": "wid290",
          "bSortable": true,
          "bSearchable": true,
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
            var result = full[2] + ' ' + Alocale["currency_symbol"];
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
            var result = full[3] + ' ' + Alocale["currency_symbol"];
            return result;
          }
        },
        {
          "aTargets": [4],
          "bSearchable": false,
          "sClass": "wid110 textcent",
          "mData": null,            
          "mRender": function (data, type, full) {
            var result = full[4] + ' ' + Alocale["currency_symbol"];
            return result;
          }
        }
      ];

    });

	</script>

@endsection
