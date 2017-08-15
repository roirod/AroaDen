@extends('layouts.main')

@section('content')

    @include('includes.pacnav')

    @include('includes.messages')
    @include('includes.errors')

    {{ addtexto("Editar Cita") }}

    @include('form_fields.edit.opendiv')

        <p class="pad4 fonsi15"> {{ $surname }}, {{ $name }} </p>

        @include('form_fields.edit.openform')

            @include('form_fields.edit_alternative')

        @include('form_fields.edit.closeform')

    @include('form_fields.edit.closediv')

@endsection

@section('js')
    @parent

	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/main.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/guarda.js') !!}"></script>
@endsection