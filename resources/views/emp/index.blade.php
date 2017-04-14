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
        	<a href="{{url("/Empresa/editData")}}" role="button" class="btn btn-sm btn-success"><i class="fa fa-edit"> </i> Editar</a>
        </span>
	  </div>
	</div>
  </div>
</div>   

<div class="row">
  <div class="col-sm-12 fonsi16">

  	 <div class="col-sm-5 pad10">
  		<i class="fa fa-minus-square"></i> Nombre: &nbsp; <span class="text-muted"> {{$empre->empre_nom}} </span>  
  	 </div> 
  	 <div class="col-sm-4 pad10"> 
 		<i class="fa fa-minus-square"></i> Poblaci&#xF3;n: &nbsp; <span class="text-muted"> {{$empre->empre_pobla}} </span> 
 	 </div>
 	 <div class="col-sm-5 pad10"> 
 	 	<i class="fa fa-minus-square"></i> Direcci&#xF3;n: &nbsp; <span class="text-muted"> {{$empre->empre_direc}} </span>
 	 </div>
 	 <div class="col-sm-3 pad10">
 	 	<i class="fa fa-minus-square"></i> NIF: &nbsp; <span class="text-muted"> {{$empre->empre_nif}} </span>
 	 </div>
 	 <div class="col-sm-3 pad10">
 	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; <span class="text-muted"> {{$empre->empre_tel1}} </span>
 	 </div>
 	 <div class="col-sm-3 pad10">
	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; <span class="text-muted"> {{$empre->empre_tel2}} </span>
	 </div> 
	 <div class="col-sm-3 pad10">
	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; <span class="text-muted"> {{$empre->empre_tel3}} </span>
	 </div>

   <br>
	 <div class="col-sm-12 pad10">
  		<i class="fa fa-minus-square"></i> Notas: <br>
  	 	<div class="box200"> {!! nl2br(e($empre->empre_notas)) !!} </div>

      <hr> <br>
   </div>

   <br>
   <div class="col-sm-12 pad10">
      <i class="fa fa-minus-square"></i> Texto de facturas: <br>
      <div class="box200"> {!! nl2br(e($empre->factutex)) !!} </div>

      <hr> <br>
   </div>

    <div class="row"> 
		  <div class="col-sm-12 pad20"> 
			   <i class="fa fa-minus-square"></i> Texto de presupuestos: <br> 
			   <div class="box200">  {!! nl2br(e($empre->presutex)) !!} </div> 
     	</div>
  	 </div>

 </div>
</div> 


@endsection





