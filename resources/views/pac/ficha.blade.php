@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


<div class="row"> 
 <div class="col-sm-12"> 
	<div class="input-group pad4"> 
		<span class="input-group-btn pad4">  <p> Ficha: </p> </span>
		<div class="btn-toolbar" role="toolbar">
		 <div class="btn-group">
		    <a href="{!!url("/Pacientes/$idpac/fiedit")!!}" role="button" class="btn btn-sm btn-success">
		       <i class="fa fa-edit"></i> Editar
		    </a>
		 </div>	

</div> </div> </div> </div>

<div class="row">
  <div class="col-sm-12 fonsi16">

  	 <div class="col-sm-12">
		<i class="fa fa-minus-square"></i> Historial Médico:
		<br>
	 	<div class="box200"> {!! nl2br(e($ficha->histo)) !!} </div>
   	 </div>

   	
	<div class="col-sm-12">

		<br> <br>
		<i class="fa fa-minus-square"></i> Enfermedades:
		<br> 
		<div class="box200"> {!! nl2br(e($ficha->enfer)) !!} </div>
   	</div>


	<div class="col-sm-12">
		<br> <br>

		<i class="fa fa-minus-square"></i> Medicamentos:
		<br> 
		<div class="box200"> {!! nl2br(e($ficha->medic)) !!} </div>
	</div>


	<div class="col-sm-12">

		<br> <br>
		<i class="fa fa-minus-square"></i> Alergias:
		<br> 
		<div class="box200"> {!! nl2br(e($ficha->aler)) !!} </div>
    </div>
  	

    <div class="col-sm-12">

    	<br> <br>
		<i class="fa fa-minus-square"></i> Notas:
		<br> 
		<div class="box200"> {!! nl2br(e($ficha->notes)) !!} </div>
    </div> 	 

 </div>
</div> 

@endsection