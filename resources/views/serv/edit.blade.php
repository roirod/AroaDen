@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')


{{ addtexto("Editar servicio") }}


<div class="row">
  <div class="col-sm-12">
  
	<form role="form" id="form" class="form" action="{{url("/$main_route/$id")}}" method="POST">
		{!! csrf_field() !!}

		<input type="hidden" name="_method" value="PUT">

		@include('form_fields.edit')
  
 	</form>

 </div> </div>


 <br> <br> <br> <br> <br> <br> 
  <br> <br> <br> <br> <br> <br> <br>


@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
@endsection