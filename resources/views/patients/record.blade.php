@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')


<div class="row"> 
 <div class="col-sm-12"> 
	<div class="input-group"> 
	<span class="input-group-btn pad10">  <p> Ficha: </p> </span>
	<div class="btn-toolbar pad4" role="toolbar">
	 <div class="btn-group">
	    <a href="{!! url("/$main_route/$id/$form_route") !!}" role="button" class="btn btn-sm btn-success">
	       <i class="fa fa-edit"></i> Editar
	    </a>
	 </div>	
</div> </div> </div> </div>

<div class="row">
  <div class="col-sm-12 fonsi15">

  	 <div class="col-sm-12">
		<i class="fa fa-minus-square"></i> Historial Médico:
		<br>
	 	<div class="box200"> {!! nl2br(e($record->histo)) !!} </div>
   	 </div>

   	
	<div class="col-sm-12">

		<br> <br>
		<i class="fa fa-minus-square"></i> Enfermedades:
		<br> 
		<div class="box200"> {!! nl2br(e($record->enfer)) !!} </div>
   	</div>


	<div class="col-sm-12">
		<br> <br>

		<i class="fa fa-minus-square"></i> Medicamentos:
		<br> 
		<div class="box200"> {!! nl2br(e($record->medic)) !!} </div>
	</div>


	<div class="col-sm-12">

		<br> <br>
		<i class="fa fa-minus-square"></i> Alergias:
		<br> 
		<div class="box200"> {!! nl2br(e($record->aler)) !!} </div>
    </div>
  	

    <div class="col-sm-12">

    	<br> <br>
		<i class="fa fa-minus-square"></i> Notas:
		<br> 
		<div class="box200"> {!! nl2br(e($record->notes)) !!} </div>
    </div> 	 

 </div>
</div> 

@endsection