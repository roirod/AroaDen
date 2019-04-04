@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<div class="row pad4">
  <div class="col-sm-3">
    <p> 
      &nbsp;<i class="fa fa-clock-o"></i> {{ @trans('aroaden.select') }}
    </p>
    <form>
      {!! csrf_field() !!}
      <select name="select_val" class="form-control select_val">
        <option value="today_appointments" selected>{{ @trans('aroaden.today_appointments') }}</option> 
        <option value="1week_appointments">{{ @trans('aroaden.1week_appointments') }}</option> 
        <option value="1month_appointments">{{ @trans('aroaden.1month_appointments') }}</option>
        <option value="minus1week_appointments">{{ @trans('aroaden.minus1week_appointments') }}</option>
        <option value="minus1month_appointments">{{ @trans('aroaden.minus1month_appointments') }}</option>
      </select> 
    </form>  
  </div>

  <form>
    {!! csrf_field() !!}
    <input type="hidden" name="select_val" value="date_range">

    <div class="col-sm-3">
      <div class="input-group date pad4" id="datepicker1">
        <p class="input-group-btn pad4"> {{ @trans('aroaden.date_from') }} </p>
        <input name="date_from" type="text" autofocus required>
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
      <div class="input-group date pad4" id="datepicker2">
        <p class="input-group-btn pad4"> {{ @trans('aroaden.date_to') }} </p>
        <input name="date_to" type="text" required>
        <span class="input-group-addon">
          <span class="glyphicon glyphicon-calendar"></span>
        </span>
      </div>
      <div class="pad10">
        <input type="button" class="btn btn-sm btn-primary searchButton" value="{{ Lang::get('aroaden.search') }}">
      </div>
    </div>
  </form>
</div>

<div class="row">
  <div class="col-sm-12" id="item_list">

  @if ($count == 0)

    <p>
      <span class="text-danger">{{ @trans('aroaden.no_appointments_today') }} {{ @trans('aroaden.today') }}</span>
    </p>

  @else

    <p>
      <span class="label label-success">{{ @trans('aroaden.today_appointments') }}</span>
    </p>

    <div class="panel panel-default"> 
      <table class="table">
         <tr class="fonsi15 success">
             <td class="wid290">{{ @trans('aroaden.patient') }}</td>
             <td class="wid95">{{ @trans('aroaden.day') }}</td>
             <td class="wid95">{{ @trans('aroaden.hour') }}</td>             
             <td class="wid290">{{ @trans('aroaden.notes') }}</td>
             <td class="wid290"></td>             
         </tr>
       </table>
 
      <div class="box400">
        <table class="table table-hover">
 
          @foreach ($main_loop as $obj)
            <tr>
                <td class="wid290"> 
                  <a href="{{ url("/$patients_route/$obj->idpat") }}" class="pad4" target="_blank">
                      {{ $obj->surname }}, {{ $obj->name }} 
                  </a>
                </td>
                <td class="wid95">{{ date( 'd-m-Y', strtotime($obj->day) ) }}</td>
                <td class="wid95">{{ substr( $obj->hour, 0, -3 ) }}</td>
                <td class="wid290">{{ $obj->notes }}</td>
                <td class="wid290"></td>
            </tr>
          @endforeach

        </table>
      </div> </div> 

    @endif

</div> </div>

@endsection

@section('footer_script')
  <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/moment-es.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('assets/datetimepicker/css/datetimepicker.min.css') }}" />
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/js/datetimepicker.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/datepicker1.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/datetimepicker/datepicker2.js') }}"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $(".select_val").change(function() {
        var _this = $(this);

        return Module.findAppointments(_this);
      });

      $(".searchButton").click(function() {
        var _this = $(this);

        return Module.findAppointments(_this);
      });

      var Module = (function( window, undefined ){
        function runApp(_this) {
          var form = _this.closest("form");
          var data = form.serialize();

          var select_val = form[0].elements.select_val.value.trim();

          if (select_val == 'date_range') {

            var date_from = form[0].elements.date_from.value.trim();
            var date_to = form[0].elements.date_to.value.trim();

            if (date_from != '' && date_to != '')
              return sendAjaxRequest(data);

          } else {

            return sendAjaxRequest(data);

          }
        }

        function sendAjaxRequest(data) {
          util.showLoadingGif('item_list');

          var obj = {
            data  : data,
            url  : '/{!! $main_route !!}/list'
          };

          util.processAjaxReturnsJson(obj).done(function(response) {
            var html = '';

            if (response.error) {

              html = '<p class="text-danger">' + response.msg + '</p>';

            } else {

              html = '<p><span class="label label-success">' + response.msg + '</span></p>';

              html += '<div class="panel panel-default">';
              html += '   <table class="table">';
              html += '     <tr class="fonsi15 success">';
              html += '       <td class="wid290">{{ @trans('aroaden.patient') }}</td>';
              html += '       <td class="wid95">{{ @trans('aroaden.day') }}</td>';
              html += '       <td class="wid95">{{ @trans('aroaden.hour') }}</td>';
              html += '       <td class="wid290">{{ @trans('aroaden.notes') }}</td>';
              html += '       <td class="wid290"></td>';
              html += '     </tr>';
              html += '   </table>';
              html += '  <div class="box400">';
              html += '    <table class="table table-hover">';

              $.each(response.main_loop, function(index, object){
                html += '  <tr>';
                html += '    <td class="wid290">';
                html += '      <a href="/{{ $patients_route }}/'+object.idpat+'" class="pad4" target="_blank">';
                html +=           object.surname + ', ' + object.name;
                html += '      </a>';
                html += '    </td>';
                html += '    <td class="wid95">' + object.day.split("-").reverse().join("-") + '</td>';
                html += '    <td class="wid95">' + object.hour.slice(0, -3); + '</td>';
                html += '    <td class="wid290">' + object.notes + '</td>';
                html += '    <td class="wid290"></td>';
                html += '  </tr>';
              });

              html += '    </table>';
              html += '  </div> </div>';
              html += ' </div> </div>';               
            }

            $('#item_list').empty();
            $('#item_list').hide().html(html).fadeIn('slow');

          }).fail(function() {

            $('#item_list').empty();
            $("#item_list").hide().html('<h3>{{ @trans('aroaden.error_message') }}</h3>').fadeIn('slow');
            
          });
        }
             
        return {
          findAppointments: function(_this) {
            runApp(_this);
          }
        }

      })(window);
    });

  </script>

@endsection