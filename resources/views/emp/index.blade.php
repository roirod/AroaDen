@extends('layouts.main')

@section('content')

@include('includes.empnav')

@include('includes.messages')
@include('includes.errors')

<br> 
    
<div class="row"> 
 <div class="col-sm-12"> 
  <div class="input-group pad4"> 
    <span class="input-group-btn pad4">  <p> Datos empresa: </p> </span>
    <div class="btn-toolbar" role="toolbar">
     <div class="btn-group">
        <a href="{!!url("/$main_route/editData")!!}" role="button" class="btn btn-sm btn-success">
           <i class="fa fa-edit"></i> Editar
        </a>
     </div>
</div> </div> </div> </div> 

<div class="row">
  <div class="col-sm-12 fonsi16">

  	 <div class="col-sm-5 pad10">
  		<i class="fa fa-minus-square"></i> Nombre: &nbsp; <span class="text-muted"> {{$empre->company_name}} </span>  
  	 </div> 
  	 <div class="col-sm-4 pad10"> 
 		<i class="fa fa-minus-square"></i> Poblaci&#xF3;n: &nbsp; <span class="text-muted"> {{$empre->company_city}} </span> 
 	 </div>
 	 <div class="col-sm-5 pad10"> 
 	 	<i class="fa fa-minus-square"></i> Direcci&#xF3;n: &nbsp; <span class="text-muted"> {{$empre->company_address}} </span>
 	 </div>
 	 <div class="col-sm-3 pad10">
 	 	<i class="fa fa-minus-square"></i> NIF: &nbsp; <span class="text-muted"> {{$empre->company_nif}} </span>
 	 </div>
 	 <div class="col-sm-3 pad10">
 	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; <span class="text-muted"> {{$empre->company_tel1}} </span>
 	 </div>
 	 <div class="col-sm-3 pad10">
	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; <span class="text-muted"> {{$empre->company_tel2}} </span>
	 </div> 
	 <div class="col-sm-3 pad10">
	 	<i class="fa fa-minus-square"></i> Tel&#xE9;fono: &nbsp; <span class="text-muted"> {{$empre->company_tel3}} </span>
	 </div>

   <br>
	 <div class="col-sm-12 pad10">
  		<i class="fa fa-minus-square"></i> Notas: <br>
  	 	<div class="box200"> {!! nl2br(e($empre->company_notes)) !!} </div>

      <hr> <br>
   </div>

   <br>
   <div class="col-sm-12 pad10">
      <i class="fa fa-minus-square"></i> Texto de facturas: <br>
      <div class="box200"> {!! nl2br(e($empre->invoice_text)) !!} </div>

      <hr> <br>
   </div>

    <div class="row"> 
		  <div class="col-sm-12 pad20"> 
			   <i class="fa fa-minus-square"></i> Texto de presupuestos: <br> 
			   <div class="box200">  {!! nl2br(e($empre->budget_text)) !!} </div> 
     	</div>
  	 </div>

 </div>
</div> 


@endsection





