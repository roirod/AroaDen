
@if ($error)

  <div class="row">
    <div class="col-sm-9">
      <p class="label label-warning fonsi15">
        {{ $msg }}
      </p>
    </div>
  </div>

  <br>

@else

  <div class="mar4"></div>

  <div class="row">
    <div class="col-sm-9">    

      @if ($count === 0)

        <p class="label label-default fonsi15">
          {{ $msg }}
        </p>

      @else

        <p class="label label-success fonsi15">
          <span class="badge">{!! $count !!}</span>
          {{ $msg }}
        </p>

      @endif
      
    </div>
  </div>

  <br>

  @if ($count !== 0)

    <div class="row">
      <div class="col-sm-12">
        <div class="panel panel-default"> 
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr class="fonsi15">
                   <td class="wid290">{{ @trans('aroaden.patient') }}</td>
                   <td class="wid95 textcent">{{ @trans('aroaden.date') }}</td>
                   <td class="wid95 textcent">{{ @trans('aroaden.hour') }}</td>             
                   <td class="wid450">{{ @trans('aroaden.notes') }}</td>
               </tr>
              </thead>
           </table>

          <div id="table_div_content">
            <div class="box300">
              <table class="table table-striped table-bordered table-hover">

                @foreach ($main_loop as $obj)
                  <tr>
                      <td class="wid290"> 
                        <a href="{{ url($routes['patients'] ."/". $obj->idpat) }}" class="pad4" target="_blank">
                            {{ $obj->surname }}, {{ $obj->name }} 
                        </a>
                      </td>
                      <td class="wid95 textcent">{{ date( 'd-m-Y', strtotime($obj->day) ) }}</td>
                      <td class="wid95 textcent">{{ substr( $obj->hour, 0, -3 ) }}</td>
                      <td class="wid450">{{ $obj->notes }}</td>
                  </tr>
                @endforeach

              </table>
            </div>
          </div>

          <table class="table table-striped table-bordered table-hover">
            <tfoot>
              <tr class="fonsi15">
                 <td class="wid290">{{ @trans('aroaden.patient') }}</td>
                 <td class="wid95 textcent">{{ @trans('aroaden.date') }}</td>
                 <td class="wid95 textcent">{{ @trans('aroaden.hour') }}</td>             
                 <td class="wid450">{{ @trans('aroaden.notes') }}</td>
              </tr>
            </tfoot>
          </table>

        </div>
      </div>
    </div>

  @endif

@endif
