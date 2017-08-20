@extends('layouts.main')

@section('content')

    @include('includes.pacnav')

    @include('includes.messages')
    @include('includes.errors')

    {{ addtexto("Editar Tratamiento") }}

    <div class="row">
     <div class="col-sm-12 mar10">

        <p class="pad4 fonsi15"> {{ $surname }}, {{ $name }} </p>
        <hr>

        <p class="pad4 fonsi15">
            {{ $object->name }}:
            <br>
            <br>
            Precio: {{ $object->price }} €.
            <br>
            Cantidad: {{ $object->units }}.
            <br>
            IVA: {{ $object->tax }}.
            <br>
            Total: {{ numformat($object->units * $object->price) }} €.      
            <br>
            Pagado: {{ $object->paid }} €.
            <br>
            Fecha: {{ date('d-m-Y', strtotime ($object->day) ) }}.        
        </p>

        <hr>

        @include('form_fields.edit.openform')

            @include('form_fields.edit_alternative')

        @include('form_fields.edit.closeform')

    @include('form_fields.edit.closediv')

@endsection


@section('footer_script')

    <script>
        
        $(document).ready(function() {

            $('input[name="units"]').on('change', function(evt) {
                var price = {{ $object->price }};
                multi(this.value, price);

                evt.preventDefault();
                evt.stopPropagation();
            });

            var append = ' <span><small><a id="borrar" class="pad4">borrar</a></small></span>';
            $('input[name="paid"]').parent().find('label').append(append);

            $('#borrar').click(function (evt) {
                $('input[name="paid"]').val(0);

                evt.preventDefault();
                evt.stopPropagation();              
            });

        });

    </script>

@endsection

@section('js')
    @parent   
      <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
      <script type="text/javascript" src="{{ asset('assets/js/calcula.js') }}"></script>
@endsection