@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')
  
  <div class="col-sm-12 pad10">
    @include('form_fields.show.name')
  </div>  

  <div class="row">
    <div class="col-sm-12">
      <fieldset>
        <legend>
          {!! @trans('aroaden.edit_treatments') !!}
        </legend>

        <div class="row pad4">
          <div class="col-sm-9">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                 <tr class="fonsi14 bggrey">
                   <td class="wid140">{{ @trans('aroaden.name') }}</td>
                   <td class="wid95">{{ @trans('aroaden.price') }}</td>
                   <td class="wid70">{{ @trans('aroaden.units') }}</td>             
                   <td class="wid70">{{ @trans('aroaden.tax') }}</td>
                   <td class="wid70">{{ @trans('aroaden.total') }}</td>
                   <td class="wid70">{{ @trans('aroaden.paid') }}</td>
                   <td class="wid70">{{ @trans('aroaden.day') }}</td>
                 </tr>
                </thead>
                <tbody>
                 <tr class="fonsi13">
                   <td class="wid140">{{ $treatment->name }}</td>
                   <td class="wid95">{{ numformat(calcTotal($treatment->price, $treatment->tax, false)) }} €</td>
                   <td class="wid70">{{ $treatment->units }}</td>             
                   <td class="wid70">{{ $treatment->tax }} %</td>
                   <td class="wid70">{{ numformat(calcTotal($treatment->price, $treatment->tax, false) * $treatment->units) }} €</td>
                   <td class="wid70">{{ numformat($treatment->paid) }} €</td>
                   <td class="wid70">{{ date('d-m-Y', strtotime ($treatment->day) ) }}</td>
                 </tr>
                </tbody>
             </table>
          </div>
        </div>

        <hr>

        @php
          {{ $object = $treatment; }}
        @endphp

        <form class="form save_form" action="/{{ $main_route.'/'.$id }}">
          <input type="hidden" name="_method" value="PUT">

          @include('form_fields.common_alternative')
        </form>

      </fieldset>
    </div>
  </div>

  @include('treatments.common')

  <script type="text/javascript">

    redirectRoute = '/{{ $routes['patients'].'/'.$idnav }}';
    
    $(document).ready(function() {
      $('#multiply_units_price').click(function (evt) {
        price = '{{ $treatment->price }}';
        tax = '{{ $treatment->tax }}';

        return getPaid();
      });
    });

  </script>

@endsection



