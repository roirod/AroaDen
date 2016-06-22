@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<div class="row"> 
	<div class="col-sm-12"> 
		<div class="input-group pad4"> 
			<span class="input-group-btn pad4"> <p> Paciente:</p> </span>
			<div class="col-sm-3">
				<span class="input-group-btn">
					<a href="/Pacientes/create" role="button" class="btn btn-sm btn-primary">
						<i class="fa fa-plus"></i> Nuevo
					</a> 
				</span>
			</div>
		</div> 
	</div>
</div>


<div class="row">
	 <form role="form" class="form" action="{!!url("/Pacientes/ver")!!}" method="post">

		 	{!! csrf_field() !!}
	
			 <div class="input-group">

				  <span class="input-group-btn pad4"> <p> &nbsp; Buscar en:</p> </span>

				  <div class="col-sm-2">

	      			<select name="busen" class="form-control" required>

	      				<option value="apepac" selected> Apellido/s </option>
	      				<option value="dni"> DNI </option>

					</select>

				  </div>

				  <div class="col-sm-4">
				   		<input type="search" name="busca" class="form-control" placeholder="buscar..." autofocus required>
				  </div>				  

				  <div class="col-sm-1">
				   <button class="btn btn-default" type="submit"> <i class="fa fa-arrow-circle-right"></i></button>
				  </div> 
			 </div>
	 </form>
</div>

	
<div class="row">
	<div class="col-sm-12">
	  <div class="panel panel-default">
		  	<table class="table">
		  	 <tr class="fonsi16 success">
				<td class="wid50">&nbsp;</td>
				<td class="wid290">Nombre</td>
				<td class="wid110">DNI</td>
				<td class="wid110">Tel&#xE9;fono1</td>
				<td class="wid230">Poblaci&#xF3;n</td>
				
			 </tr>
		</table>
 	<div class="box400">

 	 <table class="table table-hover">
		
		@foreach ($pacientes as $paciente)
				
			<tr> 
				<td class="wid50">
					<a href="{!!url("/Pacientes/$paciente->idpac")!!}" target="_blank" class="btn btn-default" role="button">
						<i class="fa fa-hand-pointer-o"></i>
					</a> 
				</td>

				<td class="wid290">
					<a href="{!!url("/Pacientes/$paciente->idpac")!!}" class="pad4" target="_blank">
						{!!$paciente->apepac!!}, {!!$paciente->nompac!!}
					</a>
				</td>

				<td class="wid110">{!!$paciente->dni!!}</td>
				<td class="wid110">{!!$paciente->tel1!!}</td>
				<td class="wid230">{!!$paciente->pobla!!}</td> 
				
			</tr>
						
		@endforeach
			
		<table class="table table-hover">
			<tr> 
				<div class="textcent">
					<hr>
					{!!$pacientes->links()!!}
				</div>
			</tr> 
		</table>
	
	</table>

</div> </div> </div> </div>

@endsection