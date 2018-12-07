@extends('layouts.main')

@section('content')

    @include('includes.patients_nav')

    @include('includes.messages')
    @include('includes.errors')

    {{ addText("Editar Cita") }}

    @include('form_fields.fields.opendiv')

        <p class="pad4 fonsi15"> {{ $surname }}, {{ $name }} </p>

        @include('form_fields.fields.openform')

            @include('form_fields.common_alternative')

        @include('form_fields.fields.closeform')

    @include('form_fields.fields.closediv')

@endsection

@section('js')
    @parent

	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/webshims.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/forgetChanges.js') !!}"></script>
@endsection