@extends('layouts.main')

@section('content')

  @include('includes.messages')
  
  <div class="row">
    <div class="col-xs-9">

      <div class="col-xs-3">
        <fieldset>
          <legend>
            <i class="fa fa-clock-o"></i> {{ @trans('aroaden.select') }}
          </legend>

          <form>
            <select name="select" class="form-control select">
              <option value="today" selected>{{ @trans('aroaden.today_appointments') }}</option> 
              <option value="1week">{{ @trans('aroaden.1week_appointments') }}</option> 
              <option value="1month">{{ @trans('aroaden.1month_appointments') }}</option>
              <option value="minus1week">{{ @trans('aroaden.minus1week_appointments') }}</option>
              <option value="minus1month">{{ @trans('aroaden.minus1month_appointments') }}</option>
            </select> 
          </form> 
        </fieldset>
      </div>

      <div class="col-xs-4">
        <fieldset>
          <legend>
            <i class="fa fa-clock-o"></i> {{ @trans('aroaden.select_date_range') }}
          </legend>

          <form>
            <input type="hidden" name="select" value="date_range">

            <div class="input-group date col-xs-10" id="datepicker1">
              <p class="input-group-btn pad4"> 
                {{ @trans('aroaden.from') }} 
              </p>
              <input name="date_from" type="text" class="form-control"required>

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
                <input name="date_to" type="text" class="form-control" size="3" required>

                <span class="input-group-addon">
                  <span class="glyphicon glyphicon-calendar"></span>
                </span>
              </div>

              <div class="input-group-btn padleft5">
                <button class="btn btn-md btn-primary searchButton pad6">
                  &nbsp; <i class="fa fa-search"></i> &nbsp;
                </button>
              </div>                
            </div>
          </form>

        </fieldset>
      </div>

    </div>
  </div>

  <hr>

  <div class="row">
    <div class="col-xs-12">
      <div id="item_list">

        @include('appointments.tableStaff')

      </div>
    </div>
  </div>

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
      $(".select").change(function() {
        var _this = $(this);

        return Module.findAppointments(_this);
      });

      $(".searchButton").click(function(evt) {
        evt.preventDefault();
        var _this = $(this);

        return Module.findAppointments(_this);
      });

      var Module = (function( window, undefined ){
        function runApp(_this) {
          var form = _this.closest("form");
          var data = form.serialize();

          var select = form[0].elements.select.value.trim();

          if (select == 'date_range') {

            var date_from = form[0].elements.date_from.value.trim();
            var date_to = form[0].elements.date_to.value.trim();

            if (date_from != '' && date_to != '')
              return sendAjaxRequest(data);

          } else {

            return sendAjaxRequest(data);

          }
        }

        function sendAjaxRequest(data) {
          var obj = {
            data  : data,
            id  : 'item_list',
            method  : 'POST',            
            url  : '/{!! $main_route !!}/list'
          };

          return util.processAjaxReturnsHtml(obj);
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