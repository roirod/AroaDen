
@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')
  
  <div class="row">
    <div class="col-sm-12 pad10">
      @include('form_fields.show.name')
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <fieldset>
        <legend>
          {!! @trans('aroaden.edit_invoice') !!}
        </legend>

        <form id="form">
          <input type="hidden" name="_method" value="PUT">
   
          <div class="row">
            <div class="col-sm-11">

              @include('invoices.includes.type')

              <div class="form-group col-sm-2"> 
                <label class="control-label text-left mar10">{{ Lang::get('aroaden.number') }}</label>
                <input class="form-control" type="number" name="number" min="1" step="1" value="{{ $number }}" required>
              </div>

              @include('invoices.includes.serial')

              @include('invoices.includes.place')

              @include('invoices.includes.exp_date')

            </div>

            @include('invoices.includes.data_section')

          </div>

          <br>

          <div class="row fonsi12">

            <div class="col-sm-6">
              <p class="fonsi14">
                {!! @trans("aroaden.treatments") !!}             
              </p>

              <div class="panel panel-default">
                <table class="table table-striped table-bordered table-hover">            
                  <tr class="">
                    <td class="wid120">{!! @trans("aroaden.treatment") !!}</td>
                    <td class="wid60 textcent">{!! @trans("aroaden.date") !!}</td>                   
                    <td class="wid60 textcent">{!! @trans("aroaden.units") !!}</td>
                    <td class="wid40 textcent">{!! @trans("aroaden.tax") !!}</td>  
                  </tr>
                </table>

                <div class="box260">
                  <table class="table table-striped table-bordered table-hover">

                    @foreach($treatments as $treat)
                      <tr>
                        <td class="wid120">{!! $treat->name !!}</td>
                        <td class="wid60 textcent">{!! date ('d-m-Y', strtotime ($treat->day) ) !!}</td>
                        <td class="wid60 textcent">{!! $treat->units !!}</td>
                        <td class="wid40 textcent">{!! $treat->tax !!} %</td>       
                      </tr>   
                    @endforeach

                  </table>
                </div>
              </div>
            </div>

            @include('invoices.includes.notes')

            <div class="row">
              <div class="col-sm-12">
                @include('form_fields.save')
              </div>
            </div>

          </div>

        </form>

      </fieldset>
    </div>
  </div>

  @include('invoices.includes.scripts')

  <script type="text/javascript">
    $(document).ready(function() {
      $("#form").on('submit', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        var ajax_data = {  
          url  : '{!! url("/$main_route/$number") !!}',
          data : $(this).serialize()
        };

        util.processAjaxReturnsJson(ajax_data).done(function(response) {
          if (response.error) {
            util.showPopup(response.msg, false);
            return util.redirectTo('{!! url("/$main_route/$number/edit") !!}');
          }

          util.showPopup();
          return util.redirectTo('{!! url("/$main_route/$idpat") !!}');
        });

      }); 
    });
  </script>

@endsection
