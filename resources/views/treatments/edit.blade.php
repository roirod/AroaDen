@extends('layouts.main')

@section('content')

    @include('includes.patients_nav')

    @include('includes.messages')
    @include('includes.errors')

    <script type="text/javascript">
        $(document).ready(function() {
            var append = ' <a id="multiply_units_price" class="pad4 bgwi fuengrisoscu" title="{{ Lang::get('aroaden.multiply_units_price') }}"><i class="fa fa-lg fa-close"></i></a>';
            $('input[name="paid"]').parent().find('label').append(append);

            var append = ' <a id="put_zero" class="pad4 bgwi fuengrisoscu" title="{{ Lang::get('aroaden.put_zero') }}"><i class="fa fa-close fa-lg text-danger"></i></a>';
            $('input[name="paid"]').parent().find('label').append(append);

            $('#multiply_units_price').click(function (evt) {
                var price = {{ $treatment->price }};
                var units = $('input[name="units"]').val();
                var paid = util.multiply(units, price);    

                $('input[name="paid"]').val(paid);

                evt.preventDefault();
                evt.stopPropagation();              
            });

            $('#put_zero').click(function (evt) {
                $('input[name="paid"]').val(0);

                evt.preventDefault();
                evt.stopPropagation();              
            });
        });
    </script>

    <div class="col-sm-12 pad10">
        @include('form_fields.show.name')
    </div>  

    <div class="row">
      <div class="col-sm-12">
        <fieldset>
          <legend>
            {!! @trans('aroaden.edit_treatments') !!}
          </legend>

            <p class="pad4 fonsi15">
                {{ $treatment->name }}
                <br>
                Precio: {{ $treatment->price }} €
                <br>
                Cantidad: {{ $treatment->units }}
                <br>
                IVA: {{ $treatment->tax }} %
                <br>
                Total: {{ numformat($treatment->units * $treatment->price) }} €
                <br>
                Pagado: {{ $treatment->paid }} €
                <br>
                Fecha: {{ date('d-m-Y', strtotime ($treatment->day) ) }}     
            </p>

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

@endsection

@section('js')
    @parent   
    <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

    <script type="text/javascript" src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/moment-es.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/datetimepicker/css/datetimepicker.min.css') }}" />
    <script type="text/javascript" src="{{ asset('assets/datetimepicker/js/datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/datetimepicker/datepicker1.js') }}"></script>
@endsection