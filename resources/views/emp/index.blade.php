@extends('layouts.main')

@section('content')

@include('includes.empnav')

@include('includes.messages')
@include('includes.errors')

<br> 
    
<div class="row"> 
  <div class="col-sm-12">
    <div class="input-group pad4"> 
      <span class="input-group-btn pad4">
      	<p> Datos empresa:</p>
      </span>
      <div class="col-sm-3">
        <span>
        	<a href="{{url("/Empresa/1/edit")}}" role="button" class="btn btn-sm btn-success"><i class="fa fa-edit"> </i> Editar</a>
        </span>
	  </div>
	</div>
  </div>
</div>   

<div class="row">
  <div class="col-sm-12 fonsi16">

  	 <div class="col-sm-5 pad10">
  		<i class="fa fa-minus-square"></i> Nombre: &nbsp; {{$empre->nom}} 
  	 </div> 
  	 <div class="col-sm-4 pad10"> 
 		<i class="fa fa-minus-square"></i> Poblaci&#xF3;n: &nbsp; {{$empre->pobla}}
 	 </div>
 	 <div class="col-sm-5 pad10"> 
 	 	<i class="fa fa-minus-square"></i> Direcci&#xF3;n: &nbsp; {{$empre->direc}}
 	 </div>
 	 <div class="col-sm-3 pad10">
 	 	<i class="fa fa-minus-square"></i> NIF: &nbsp;{{$empre->nif}}
 	 </div>
 	 <div class="col-sm-3 pad10">
 	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; {{$empre->tel1}}
 	 </div>
 	 <div class="col-sm-3 pad10">
	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; {{$empre->tel2}} 
	 </div> 
	 <div class="col-sm-3 pad10">
	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; {{$empre->tel3}}
	 </div>
	 <div class="col-sm-12 pad10">
		<i class="fa fa-minus-square"></i> Notas: <br>
	 	<div class="box200"> {!! nl2br(e($empre->notas)) !!} </div>
   	 </div>
     <div class="row mar10"> 
		<div class="col-sm-12 pad10"> <hr> <br><br> 
			<i class="fa fa-minus-square"></i> Texto de presupuestos: <br> 
			<div class="box200">  {!! nl2br(e($empre->presutex)) !!} </div> 
     	</div>
  	 </div>

 </div>
</div> 


@endsection





