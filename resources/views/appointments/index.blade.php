@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!! csrf_token() !!}"/>

<div class="row">
  <div class="col-sm-12">
    <form>
  		<div class="input-group">
  		 	<div class="input-group-btn pad10"> 
          <p> &nbsp;<i class="fa fa-clock-o"></i> {{ @trans('aroaden.select') }}</p>
        </div>
  		  <div class="col-sm-3"> 
  		      <select name="select_val" class="form-control search_class">
  		        <option value="today_appointments" selected>{{ @trans('aroaden.today_appointments') }}</option> 
  		        <option value="1week_appointments">{{ @trans('aroaden.1week_appointments') }}</option> 
  		        <option value="1month_appointments">{{ @trans('aroaden.1month_appointments') }}</option>
              <option value="minus1week_appointments">{{ @trans('aroaden.minus1week_appointments') }}</option>
              <option value="minus1month_appointments">{{ @trans('aroaden.minus1month_appointments') }}</option>
  		      </select> 
  		  </div>
</div>  </form>  </div>  </div>

<div class="row">
  <div class="col-sm-12">
    <form>
      <input type="hidden" name="select_val" value="date_range">

      <div class="input-group pad4"> 
        <span class="input-group-btn pad10"> <p>{{ @trans('aroaden.date_from') }}</p> </span>
        <div class="col-sm-4"> 
          <input name="date_from" type="date" class="search_class" autofocus required>
        </div>
        <div class="col-sm-1">
          <span class="input-group-btn pad10">  <p>{{ @trans('aroaden.date_to') }}</p> </span> 
        </div>
        <div class="col-sm-4 input-group">
          <input name="date_to" type="date" class="search_class" required>
        </div>
      </div>
    
    </form>
</div> </div>


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
             <td class="wid50"></td>
             <td class="wid290">{{ @trans('aroaden.patient') }}</td>
             <td class="wid110">{{ @trans('aroaden.hour') }}</td>
             <td class="wid110">{{ @trans('aroaden.day') }}</td>
             <td class="wid230">{{ @trans('aroaden.notes') }}</td>
         </tr>
       </table>
 
      <div class="box400">

        <table class="table table-hover">
 
          @foreach ($main_loop as $obj)
            <tr>
                <td class="wid50">
                  <a href="{{ url("/$patients_route/$obj->idpat") }}" target="_blank" class="btn btn-default btn-sm" role="button">
                    <i class="fa fa-hand-pointer-o"></i>
                  </a> 
                </td>

                <td class="wid290"> 
                  <a href="{{ url("/$patients_route/$obj->idpat") }}" class="pad4" target="_blank">
                      {{ $obj->surname }}, {{ $obj->name }} 
                  </a>
                </td>

                <td class="wid110">{{ substr( $obj->hour, 0, -3 ) }}</td>
                <td class="wid110">{{ date( 'd-m-Y', strtotime($obj->day) ) }}</td>
                <td class="wid230">{{ $obj->notes }}</td>
            </tr>
          @endforeach

        </table>
      </div> </div> 

    @endif

</div> </div>


@endsection
	 
@section('js')
    @parent

	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/webshims.js') }}"></script>
@endsection

@section('footer_script')

  <script>
    
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
      }
    });

    $(document).ready(function() {
      $(".search_class").on('change', function(evt) {
        var $this = $(this);

        Module.run($this);

        evt.preventDefault();
        evt.stopPropagation();
      });

      var Module = (function( window, undefined ){
        function runApp($this) {
          var form = $this.parents('form');
          var data = form.serialize();

          var select_val = form[0].elements.select_val.value.trim();
          var _token = $('meta[name="_token"]').attr('content');

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
              html += '       <td class="wid50"> &nbsp; </td>';
              html += '       <td class="wid290">{{ @trans('aroaden.patient') }}</td>';
              html += '       <td class="wid110">{{ @trans('aroaden.hour') }}</td>';
              html += '       <td class="wid110">{{ @trans('aroaden.day') }}</td>';
              html += '       <td class="wid230">{{ @trans('aroaden.notes') }}</td>';
              html += '     </tr>';
              html += '   </table>';
              html += '  <div class="box400">';
              html += '    <table class="table table-hover">';

              $.each(response.main_loop, function(index, object){
                html += '  <tr>';
                html += '    <td class="wid50">';
                html += '      <a href="/{{ $patients_route }}/'+object.idpat+'" target="_blank" class="btn btn-default btn-sm" role="button">';
                html += '        <i class="fa fa-hand-pointer-o"></i>';
                html += '      </a>';
                html += '    </td>';
                html += '    <td class="wid290">';
                html += '      <a href="/{{ $patients_route }}/'+object.idpat+'" class="pad4" target="_blank">';
                html +=           object.surname + ', ' + object.name;
                html += '      </a>';
                html += '    </td>';
                html += '    <td class="wid110">' + object.hour.slice(0, -3); + '</td>';
                html += '    <td class="wid110">' + object.day.split("-").reverse().join("-") + '</td>';
                html += '    <td class="wid230">' + object.notes + '</td>';
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
          run: function($this) {
            runApp($this);
          }
        }

      })(window);

    });

  </script>

@endsection