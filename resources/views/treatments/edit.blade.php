@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')
  @include('includes.errors')

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
          <div class="col-sm-12">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                 <tr class="fonsi15">
                   <td class="wid140">{{ @trans('aroaden.name') }}</td>
                   <td class="wid95">{{ @trans('aroaden.price') }}</td>
                   <td class="wid95">{{ @trans('aroaden.units') }}</td>             
                   <td class="wid95">{{ @trans('aroaden.tax') }}</td>
                   <td class="wid95">{{ @trans('aroaden.total') }}</td>
                   <td class="wid95">{{ @trans('aroaden.paid') }}</td>
                   <td class="wid140">{{ @trans('aroaden.day') }}</td>
                 </tr>
                </thead>
                <tbody>
                 <tr class="fonsi15">
                   <td class="wid140">{{ $treatment->name }}</td>
                   <td class="wid95">{{ $treatment->price }} €</td>
                   <td class="wid95">{{ $treatment->units }}</td>             
                   <td class="wid95">{{ $treatment->tax }} %</td>
                   <td class="wid95">{{ numformat($treatment->units * $treatment->price) }} €</td>
                   <td class="wid95">{{ $treatment->paid }} €</td>
                   <td class="wid140">{{ date('d-m-Y', strtotime ($treatment->day) ) }}</td>
                 </tr>
                </tbody>
             </table>
          </div>
        </div>

        <hr>

        @php
        {{
          $object = $treatment;
        }}
        @endphp

        @include('form_fields.fields.openform')

          <input type="hidden" name="price" value="{{ $object->price }}">

          @include('form_fields.common_alternative')

        @include('form_fields.fields.closeform')

      </fieldset>
    </div>
  </div>


  <script type="text/javascript">
    $(document).ready(function() {
      $('#multiply_units_price').click(function (evt) {
        var price = {{ $treatment->price }};
        
        return getPaid(price);
      });
    });
  </script>


  @include('treatments.common')


@endsection



