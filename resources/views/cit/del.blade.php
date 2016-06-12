@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

 <div class="row"> 
  	<div class="col-sm-12 mar10">
  	
   	<p class="pad10">
   		Eliminar cita:
   	</p>

	 	<form role="form" class="form" id="form" role="form" action="{!!url("/Citas/$idcit")!!}" method="POST">	
	  		{!! csrf_field() !!}

			<input type="hidden" name="_method" value="DELETE">

			<input type="hidden" name="idpac" value="{{$idpac}}">

			<div class="col-sm-12">
				<span class="lead pad4"> 

					<p>
						Día: {!! date ('d-m-Y', strtotime ($cita->diacit)) !!} 
						<br>
						Hora: {!! $cita->horacit !!}
						<br>
						Notas: {!! $cita->notas !!}
					</p>

				</span>
 			</div>

	@include('includes.delbuto')
 			
 		</form>
 	</div>
 </div>
 
 @endsection