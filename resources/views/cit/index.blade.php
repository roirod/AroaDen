@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12">
  	<form role="form" class="form" action="{{ url('/Citas/ver') }}" method="post">
  		{!! csrf_field() !!}

  		<div class="input-group">
  		 	<span class="input-group-btn pad4"> <p> &nbsp;<i class="fa fa-clock-o"></i> Citas: </p> </span>
  		  <div class="col-sm-2"> 
  		    <span class="input-group-btn"> 
  		      <select name="selec" class="form-control">
  		        <option value="hoy" selected>hoy</option> 
  		        <option value="1semana">+1 semana</option>
  		        <option value="1mes">+1 mes</option>
  		        <option value="3mes">+3 meses</option> 
  		        <option value="1ano">+1 año</option>
  		        <option value="menos1mes">-1 mes</option>
  		        <option value="menos3mes">-3 meses</option>
  		        <option value="menos1ano">-1 año</option>
  		        <option value="menos5ano">-5 años</option>
  		        <option value="menos20ano">-20 años</option>
  		        <option value="todas">todas</option>
  		      </select> 
  		    </span>
  		  </div>
  		  <div class="col-sm-2">
  		    <span> 
  		      <button class="btn btn-default" name="veord" type="submit"> ver <i class="fa fa-arrow-circle-right"></i></button>
  		    </span>
  		  </div>
</div>  </form>  </div>  </div>

<div class="row">
  <div class="col-sm-12">
    <form role="form" class="form" action="{{ url('/Citas/ver') }}" method="post">
      {!! csrf_field() !!}

      <div class="input-group pad10"> 
        <span class="input-group-btn pad4">  <p> Fecha de: </p> </span>
        <div class="col-sm-4"> 
          <input name="fechde" type="date" required>
        </div>
        <div class="col-sm-1">
          <span class="input-group-btn pad4">  <p> hasta: </p> </span> 
        </div>
        <div class="col-sm-4 input-group">
          <input name="fechha" type="date" required>
        <input type="hidden" name="selec" value="rango">
        <button class="btn btn-default" name="veo" type="submit"> ver <i class="fa fa-arrow-circle-right"></i></button>
        </div>
      </div>
    
    </form>
</div> </div>


<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 
<br> <br> <br> <br> <br> <br> <br> <br> <br> <br> 


@endsection
	 
@section('js')
    @parent

	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	  
@endsection