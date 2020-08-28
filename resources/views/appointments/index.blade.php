@extends('layouts.main')

@section('content')

  @include('includes.messages')

  <div class="row">
    <div class="col-xs-9" id="searchDates">

      <div class="col-xs-4">
        <fieldset>
          <legend>
            <i class="fa fa-clock-o"></i> {{ @trans('aroaden.select_date_range') }}
          </legend>

          <div id="searchDates">
            <div class="input-group date col-xs-10" id="datepicker1">
              <p class="input-group-btn pad4"> 
                {{ @trans('aroaden.from') }} 
              </p>
              <input name="date_from" ref="date_from" type="text" class="form-control" required>

              <span class="input-group-addon">
                <span class="glyphicon glyphicon-calendar"></span>
              </span>
            </div>

            <div class="mar4"></div>

            <div class="input-group" id="datepicker2">
              <div class="input-group">
                <p class="input-group-btn pad4"> 
                  {{ @trans('aroaden.to') }}
                </p>
                <input name="date_to" ref="date_to" type="text" class="form-control" size="3" required>

                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>

              <div class="input-group-btn padleft5">
                <button @click="getAllData()" title="{{ @trans('aroaden.search') }}" class="btn btn-md btn-primary pad6">
                  &nbsp; <i class="fa fa-search"></i> &nbsp;
                </button>
              </div>                
            </div>
          </div>
        </fieldset>
      </div>

      <div class="col-xs-12">
        <br>

        <msg-label v-bind:msg="msg"></msg-label>
      </div>

    </div>
  </div>

  <br>

  <div class="row">
    <div class="col-sm-10">
      <div class="panel panel-default">
        <table class="table table-bordered table-hover" id="itemsTable">
          <thead>
            <tr class="fonsi14">
              <td class="wid230"></td>
              <td class="wid230">{{ Lang::get('aroaden.name') }}</td>
              <td class="wid70 textcent">{{ @trans('aroaden.date') }}</td>
              <td class="wid70 textcent">{{ @trans('aroaden.hour') }}</td>             
              <td class="wid350">{{ @trans('aroaden.notes') }}</td>
            </tr>
          </thead>
          <tfoot>
            <tr class="fonsi14">
              <td class="wid230"></td>
              <td class="wid230">{{ Lang::get('aroaden.name') }}</td>
              <td class="wid70 textcent">{{ @trans('aroaden.date') }}</td>
              <td class="wid70 textcent">{{ @trans('aroaden.hour') }}</td>             
              <td class="wid350">{{ @trans('aroaden.notes') }}</td>
             </tr>
          </tfoot>  
        </table>
      </div>
    </div> 
  </div>

@endsection

@section('footer_script')

  @include('includes.compo_vue.msg-label')

  <script type="text/javascript">
    (function (){
      defaulTableId = $("#itemsTable");
      var today = moment();
      var add1month = moment().add(1, 'M');
      today_db = today.format('YYYY-MM-DD');
      add1month_db = add1month.format('YYYY-MM-DD');
      today_es = today.format('DD-MM-YYYY');
      add1month_es = add1month.format('DD-MM-YYYY');
      var msg = "Citas entre "+ today_es +" y "+ add1month_es;

      function setsSearch (date_from, date_to) {
        tableObj.oSearch['sSearch'] = date_from +","+ date_to;
      }

      setsSearch(today_db, add1month_db);

      tableObj.aaSorting = [[2, "asc"]];

      tableObj.sDom = 
        "<'row'<'col-sm-5'l>>" +
        "<'row'<'col-sm-12'r>>" +
        "<'row'<'col-sm-7'i><'col-sm-5'p>>" +
        "<'row'<'col-sm-12't>>" +
        "<br>" +
        "<'row'<'col-sm-7'i><'col-sm-5'p>>"
      ;

      tableObj.oLanguage = {
        'sProcessing': 'Procesando...',
        'sLengthMenu': 'Selecciona _MENU_',
        'sZeroRecords': 'Citas no encontradas',
        'sInfo': 'De _START_ hasta _END_ de _TOTAL_ citas',
        'sInfoEmpty': 'No hay citas',
        'sInfoFiltered': '(filtrados de _MAX_ total de citas)',
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
          "bSortable": false,
          "bSearchable": false,
          "mRender": function (data, type, full) {
            var resultado = '<a href="{!! $routes['patients'] !!}/'+ full[0] +'" class="pad4" target="_blank">'+ full[1] +'</a>';
            return resultado;
          }
        },
        {
          "aTargets": [2],
          "sClass": "wid70 textcent"
        },
        {
          "aTargets": [3],
          "sClass": "wid70 textcent",         
          "bSortable": false,
          "bSearchable": false
        },
        {
          "aTargets": [4],
          "bSortable": false,
          "bSearchable": false
        }
      ];

      util.renderTable(tableObj);

      var vmsearchDates = new Vue({
        el: '#searchDates',
        data: {
          date_from: "",
          date_to: "",
          msg: ""
        },
        mounted(){
          this.$refs.date_from.value = today_es;
          this.$refs.date_to.value = add1month_es;
          this.msg = msg;
        },
        methods: {
          getAllData: function() {
            date_from = this.$refs.date_from.value;
            date_to = this.$refs.date_to.value;
            date_from_db = moment(date_from, "DD-MM-YYYY").format('YYYY-MM-DD');
            date_to_db = moment(date_to, "DD-MM-YYYY").format('YYYY-MM-DD');
            msg = "La fecha "+ date_from +" es posterior a "+ date_to;

            if (date_from_db > date_to_db)
              return util.showPopup(msg, false);

            msg = "Citas entre "+ date_from +" y "+ date_to;
            this.msg = msg;

            setsSearch(date_from_db, date_to_db);

            util.destroyTable();
            util.renderTable(tableObj);
          }
        }
      });

    })();

  </script>

@endsection