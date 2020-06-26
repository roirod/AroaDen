@extends('layouts.main')

@section('content')

  @include('includes.accounting_nav')

  @include('includes.messages')
  
  <div class="row">
    <div class="col-sm-10">
      <div class="panel panel-default">
        <table class="table table-striped table-bordered table-hover" id="table">
          <thead>
            <tr class="fonsi14">
              <td class="wid50"></td>
              <td class="wid230">{!! @trans("aroaden.patient") !!}</td>
              <td class="wid95 textcent">{!! @trans("aroaden.number") !!}</td>
              <td class="wid70 textcent">{!! @trans("aroaden.serial") !!}</td>
              <td class="wid110 textcent">{!! @trans("aroaden.type") !!}</td>              
              <td class="wid110 textcent">{!! @trans("aroaden.exp_date") !!}</td>
              <td class="wid50 textcent">{!! @trans("aroaden.edit") !!}</td>              
              <td class="wid50 textcent">{!! @trans("aroaden.pdf") !!}</td>
            </tr>
          </thead>
          <tfoot>
            <tr class="fonsi14">
              <td class="wid50"></td>
              <td class="wid230">{!! @trans("aroaden.patient") !!}</td>
              <td class="wid95 textcent">{!! @trans("aroaden.number") !!}</td>
              <td class="wid70 textcent">{!! @trans("aroaden.serial") !!}</td>
              <td class="wid110 textcent">{!! @trans("aroaden.type") !!}</td>              
              <td class="wid110 textcent">{!! @trans("aroaden.exp_date") !!}</td>
              <td class="wid50 textcent">{!! @trans("aroaden.edit") !!}</td>                            
              <td class="wid50 textcent">{!! @trans("aroaden.pdf") !!}</td>              
            </tr>
          </tfoot>  
        </table>          
      </div>
    </div> 
  </div>

@endsection

@section('footer_script')

  <link href="{!! asset('assets/css/datatables.min.css') !!}" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="{!! asset('assets/js/datatables.min.js') !!}"></script>

  <script type="text/javascript">   
  $(document).ready(function() {
      setTimeout(function(){
        $("#table").dataTable(tableObj);
      }, 180);

      tableObj.aaSorting = [[2, "asc"]];

      tableObj.oLanguage = {
        'sProcessing': 'Procesando...',
        'sLengthMenu': 'Selecciona _MENU_',
        'sZeroRecords': 'No hay registros.',
        'sInfo': 'De _START_ hasta _END_ de _TOTAL_ facturas',
        'sInfoEmpty': 'No hay pacientes',
        'sInfoFiltered': '(filtrados de _MAX_ total de facturas)',
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
          "sClass": "wid110 textcent"
        },
        {
          "aTargets": [3],
          "sClass": "wid110 textcent"
        },
        {
          "aTargets": [4],
          "sClass": "wid110 textcent",
          "bSortable": false,
          "bSearchable": false
        },
        {
          "aTargets": [5],
          "sClass": "wid110 textcent"
        },
        {
          "aTargets": [6],
          "bSortable": false,
          "bSearchable": false,
          "sClass": "wid60 textcent",
          "mData": null,            
          "mRender": function (data, type, full) {
            var result = '<a title="{!! @trans('aroaden.edit') !!}" href="'+ routes.invoices +'/'+ full[2] +'/edit" target="_blank" class="btn btn-success btn-sm">';
            result += '<i class="fa fa-edit"></i>';
            result += '</a>';
            return result;
          }
        },
        {
          "aTargets": [7],
          "bSortable": false,
          "bSearchable": false,
          "sClass": "wid60 textcent",
          "mData": null,            
          "mRender": function (data, type, full) {
            var result = '<a title="{!! @trans('aroaden.download_pdf') !!}" href="'+ routes.invoices +'/downloadPdf/'+ full[2] +'" class="btn btn-info btn-sm">';
            result += '<i class="fa fa-download"></i>';
            result += '</a>';
            return result;
          }
        }
      ];

    });

  </script>

@endsection