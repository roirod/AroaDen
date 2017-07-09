@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

<div class="row"> 
 <div class="col-sm-12"> 
	<div class="input-group"> 
	<span class="input-group-btn pad10">  <p> Paciente: </p> </span>
	<div class="btn-toolbar pad10" role="toolbar">
	 <div class="btn-group">
	    <a href="{!!url("/Pacientes/$idpac/edit")!!}" role="button" class="btn btn-sm btn-success">
	       <i class="fa fa-edit"></i> Editar
	    </a>
	 </div>	
	<div class="btn-group">

	 	<form role="form" class="form" id="form" role="form" action="{!!url("/Pacientes/$idpac")!!}" method="POST">	
	  		{!! csrf_field() !!}

			<input type="hidden" name="_method" value="DELETE">

			<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
			<i class="fa fa-times"></i> Eliminar <span class="caret"></span>  </button>
			<ul class="dropdown-menu" role="menu"> 
				<li>
					@include('includes.delete_button')
				</li>
			</ul>			
 			
 		</form>

</div> </div> </div> </div> </div>


<div class="row pad10">
  <form role="form" action="{!!url('/Pacientes/upload')!!}" method="post" enctype="multipart/form-data">
  	  {!! csrf_field() !!}

       <input type="hidden" name="idpac" value="{!!$idpac!!}">
       <input type="hidden" name="profile_photo" value="1">
  
  	  <div class="input-group">
  	    <span class="input-group-btn pad4"> 
  	      <p>&nbsp;&nbsp; Subir foto perfil: &nbsp;&nbsp;</p> 
  	    </span> 
  	    <span class="input-group-btn"> 
  	      <input type="file" class="btn btn-default" name="files" />
  	    </span> 
  	    &nbsp;&nbsp;&nbsp;
  	    <span class="pad10"> 
  	      <button type="submit" class="btn btn-info">&nbsp;<i class="fa fa-upload"></i>&nbsp;</button>
  	    </span>
  	  </div>
  </form>
</div>

<hr>

<div class="row mar10"> 
  <div class="col-sm-12"> 
    <div class="row fonsi16">

		<div class="col-sm-2 pad4 max150">
			<img src="{!! $profile_photo !!}" class="max150 pad4">
		</div>

		<div class="col-sm-10">

			<div class="col-sm-8 pad4"> 
			<i class="fa fa-minus-square"></i> Paciente: &nbsp; {!!$paciente->surname!!},&nbsp;{!!$paciente->name!!} 
			</div>

			<div class="col-sm-3 pad4"> 
			<i class="fa fa-minus-square"></i> id: &nbsp; {!!$paciente->idpac!!} 
			</div>

			<div class="col-sm-7 pad4"> 
			<i class="fa fa-minus-square"></i> Poblaci&#xF3;n: &nbsp; {!!$paciente->city!!}
			 </div> 

			<div class="col-sm-8 pad4">
			<i class="fa fa-minus-square"></i> Direcci&#xF3;n: &nbsp; {!!$paciente->address!!} 
			</div> 

			 <div class="col-sm-4 pad4">
			<i class="fa fa-minus-square"></i> DNI: &nbsp; {!! $paciente->dni!!}
			 </div> 

			 <div class="col-sm-4 pad4">
			<i class="fa fa-minus-square"></i> Sexo: &nbsp; {!!$paciente->sex!!} 
			</div> 

			<div class="col-sm-4 pad4">
			<i class="fa fa-minus-square"></i> Tel&#xE9;fono1: &nbsp;{!!$paciente->tel1!!}
			 </div>

			 <div class="col-sm-4 pad4">
			<i class="fa fa-minus-square"></i> Tel&#xE9;fono2: &nbsp; {!! $paciente->tel2!!} 
			</div>

			 <div class="col-sm-4 pad4">
			<i class="fa fa-minus-square"></i> Tel&#xE9;fono3: &nbsp; {!!$paciente->tel3!!}
			 </div> 

			 <div class="col-sm-4 pad4"> 
			<i class="fa fa-minus-square"></i> F. nacimiento: &nbsp; {!!date ('d-m-Y', strtotime ($paciente->birth) )!!} 
			</div>

			 <div class="col-sm-3 pad4"> 	
			<i class="fa fa-minus-square"></i> Edad: &nbsp; {!!$edad!!} años 
			</div>

		</div>

		 <div class="col-sm-12 pad4"> 
		<i class="fa fa-minus-square"></i> Notas: <br>
		 <div class="box200"> {!! nl2br(e($paciente->notes)) !!} 
		 </div>
		 </div>

 </div> </div> </div>

<hr> <br>

<div class="row">
  <div class="col-sm-12"> 
  <div class="input-group">
   <span class="input-group-btn pad10"> <p> Citas: </p> </span>
   <div class="btn-toolbar pad10" role="toolbar"> 
    <div class="btn-group">
       <a href="{!!url("/Citas/$idpac/create")!!}" role="button" class="btn btn-sm btn-primary">
          <i class="fa fa-plus"></i> Nueva
       </a>
</div> </div> </div>  </div> </div>

  <div class="row"> <div class="col-sm-12">
   <div class="panel panel-default">
    <table class="table">
     <tr class="fonsi16 success">
		  <td class="wid95">Hora</td>
		  <td class="wid95">Día</td>
		  <td class="wid50"></td>
		  <td class="wid50"></td> 		  
		  <td class="wid290">Notas</td>
     </tr>
    </table>
   	<div class="box260">
   	<table class="table table-striped">      	  	

    @foreach($citas as $cita)
		<tr>
 			<td class="wid95">{!!$cita->hour!!}</td>
 			<td class="wid95">{!!date('d-m-Y', strtotime($cita->day) )!!}</td>
 			<td class="wid50">	
				<a href="{!!url("/Citas/$idpac/$cita->idcit/edit")!!}" class="btn btn-xs btn-success" role="button" title="Editar">
					<i class="fa fa-edit"></i>
				</a>
			</td>
			<td class="wid50"> 	
				<div class="btn-group">

				 	<form role="form" class="form" id="form" role="form" action="{!!url("/Citas/$cita->idcit")!!}" method="POST">		
				  		{!! csrf_field() !!}

						<input type="hidden" name="_method" value="DELETE">
						<input type="hidden" name="idpac" value="{{$idpac}}">

						<button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-times"></i> <span class="caret"></span>  </button>
						<ul class="dropdown-menu" role="menu"> 
							<li>
								@include('includes.delete_button')
							</li>
						</ul>			
			 		</form>

				</div> 
			</td>
			<td class="wid290">{!!$cita->notes!!}</td>
		</tr>
    @endforeach
    
 	</table>

 </div> </div> </div> </div>		
			
<hr> <br>

<div class="row">
  <div class="col-sm-12"> 
  <div class="input-group">
   <span class="input-group-btn pad10">  <p> Tratramientos: </p> </span>
   	<div class="btn-toolbar pad10" role="toolbar"> 
   	<div class="btn-group">
	  	<form class="form" id="form" role="form" action="{!!url("/Trapac/crea")!!}" method="POST">
			{!! csrf_field() !!}
			<input type="hidden" name="idpac" value="{!!$idpac!!}">

			<button type="submit" class="text-left btn btn-primary btn-sm">Añadir
				<i class="fa fa-plus"></i> 
			</button>
		</form>
</div> </div> </div> 
*Pe1 y Pe2: personal1 y personal2.
</div> </div>

<div class="row">
 <div class="col-sm-12">
 <div class="panel panel-default">
  <table class="table"> 
	  <tr class="fonsi16 success">
		  <td class="wid140">Servicio</td>
		  <td class="wid70 textcent">Precio</td>
		  <td class="wid70 textcent">Cantidad</td>
		  <td class="wid70 textcent">Total</td>
		  <td class="wid70 textcent">Pagado</td>
		  <td class="wid70">Fecha</td>
		  <td class="wid50 textcent"></td>
		  <td class="wid50 textcent"></td> 
		  <td class="wid50 textcent">*Pe1</td>
		  <td class="wid50 textcent">*Pe2</td>
	   </tr> 
   </table> 
   <div class="box260">
   <table class="table table-striped">

    @foreach($tratampacien as $tratam)		
    	<tr>
    		<td class="wid140">{!!$tratam->name!!}</td> 
			<td class="wid70 textcent">{!!numformat($tratam->price)!!} €</td>
			<td class="wid70 textcent">{!!$tratam->units!!}</td>
			<td class="wid70 textcent">{!!numformat($tratam->units * $tratam->price)!!} €</td>
			<td class="wid70 textcent">{!!numformat($tratam->paid)!!} €</td>
			<td class="wid70">{!!date ('d-m-Y', strtotime ($tratam->date) )!!}</td>

			<td class="wid50 textcent">
				<a href="{!!url("/Trapac/$idpac/$tratam->idtra/edit")!!}" class="btn btn-xs btn-success" role="button" title="Editar">
					<i class="fa fa-edit"></i>
				</a>
			</td>

			<td class="wid50 textcent"> 	
				<div class="btn-group">

				 	<form role="form" class="form" id="form" role="form" action="{!!url("/Trapac/$tratam->idtra")!!}" method="POST">	
				  		{!! csrf_field() !!}

						<input type="hidden" name="_method" value="DELETE">
						<input type="hidden" name="idpac" value="{{$idpac}}">

						<button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-times"></i> <span class="caret"></span>  </button>
						<ul class="dropdown-menu" role="menu"> 
							<li>
								@include('includes.delete_button')
							</li>
						</ul>			
			 			
			 		</form>

				</div> 
			</td>  			
			
			@if ( $tratam->per1 === 0 )
				<td class="wid50 textcent"> <i class="fa fa-hand-rock-o"></i> </td> 
			@else 
				<td class="wid50 textcent">
					<a href="{!!url("/Personal/$tratam->per1")!!}" target="_blank" class="btn btn-xs btn-default" role="button">
						<i class="fa fa-hand-pointer-o"></i>
					</a>
				</td> 
			@endif
			 
			@if ( $tratam->per2 === 0 )
				<td class="wid50 textcent"> <i class="fa fa-hand-rock-o"></i> </td>
			@else
				<td class="wid50 textcent">
					<a href="{!!url("/Personal/$tratam->per2")!!}" target="_blank" class="btn btn-xs btn-default" role="button">
						<i class="fa fa-hand-pointer-o"></i>
					</a>
				</td> 
			@endif		 
		</tr>
	@endforeach

    </table>

</div> </div> </div> </div>		

<hr> <br>			

<div class="row">
  <div class="col-sm-12"> 

<?php
 addtexto("Pagos");
?>
 
	@foreach( $suma as $sum )

	 	<div class="row mar10 fonsi16">
	 	    <div class="col-sm-5">
	 	      <table class="table table-bordered">
	 	     	<tr class="text-info pad10">
		 	     	 <td class="wid180"> <i class="fa fa-minus"></i> &nbsp; Suma tratamientos:</td>
		 	     	 <td class="wid95 textder"> {!!numformat($sum->total_sum)!!} €</td>
	 	     	</tr> 
	 		    <tr class="text-info pad10">
	 		    	<td class="wid180"> <i class="fa fa-minus"></i> &nbsp; Pagado:</td>
	 		    	<td class="wid95 textder"> {!!numformat($sum->total_paid)!!} € </td>
	 		    </tr>
	 		    <tr class="text-danger pad10">
	 		    	<td class="wid180"> <i class="fa fa-minus"></i> &nbsp; Resto:</td>
	 		    	<td class="wid95 textder"> {!!numformat($sum->rest)!!} € </td>
	 		    </tr>
	 		  </table>

	 		</div>
	 	</div>

	@endforeach	 
 
  </div>
</div>
 
@endsection