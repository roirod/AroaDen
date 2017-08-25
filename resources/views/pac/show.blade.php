@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

<div class="row"> 
 <div class="col-sm-12"> 
	<div class="input-group"> 
	<span class="input-group-btn pad10">  <p> Paciente: </p> </span>
	<div class="btn-toolbar pad4" role="toolbar">
	 <div class="btn-group">
	    <a href="{!!url("/$main_route/$id/edit")!!}" role="button" class="btn btn-sm btn-success">
	       <i class="fa fa-edit"></i> Editar
	    </a>
	 </div>	
	<div class="btn-group">
	 	<form role="form" class="form" id="form" role="form" action="{!!url("/$main_route/$id")!!}" method="POST">	
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


@include('form_fields.show.upload_photo')


<hr>

<div class="row mar10"> 
  <div class="col-sm-12"> 
    <div class="row fonsi16">

		@include('form_fields.show.profile_photo')

		<div class="col-sm-10">

			@include('form_fields.show.name')

			@include('form_fields.show.idpac')

			@include('form_fields.show.city')

			@include('form_fields.show.address')

			@include('form_fields.show.dni')

			@include('form_fields.show.sex')

			@include('form_fields.show.tel1')

			@include('form_fields.show.tel2')

			@include('form_fields.show.tel3')

			@include('form_fields.show.birth')

			@include('form_fields.show.age')
			
		</div>

		@include('form_fields.show.notes')

 </div> </div> </div>

<hr> <br>

<div class="row">
  <div class="col-sm-12"> 
  <div class="input-group">
   <span class="input-group-btn pad10"> <p> Citas: </p> </span>
   <div class="btn-toolbar pad4" role="toolbar"> 
    <div class="btn-group">
       <a href="{!!url("/Citas/$id/create")!!}" role="button" class="btn btn-sm btn-primary">
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
 			<td class="wid95">{!! mb_substr($cita->hour, 0, -3) !!}</td>
 			<td class="wid95">{!!date('d-m-Y', strtotime($cita->day) )!!}</td>
 			<td class="wid50">	
				<a href="{!!url("/Citas/$cita->idcit/edit")!!}" class="btn btn-xs btn-success" role="button" title="Editar">
					<i class="fa fa-edit"></i>
				</a>
			</td>
			<td class="wid50"> 	
				<div class="btn-group">

				 	<form role="form" class="form" id="form" role="form" action="{!!url("/Citas/$cita->idcit")!!}" method="POST">		
				  		{!! csrf_field() !!}

						<input type="hidden" name="_method" value="DELETE">

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
   	<div class="btn-toolbar pad4" role="toolbar"> 
   	<div class="btn-group">
       <a href="{!!url("/Trapac/$id/create")!!}" role="button" class="btn btn-sm btn-primary">
          <i class="fa fa-plus"></i> Añadir
       </a>
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
    		<td class="wid140">{!!$tratam->servicios_name!!}</td> 
			<td class="wid70 textcent">{!!numformat($tratam->price)!!} €</td>
			<td class="wid70 textcent">{!!$tratam->units!!}</td>
			<td class="wid70 textcent">{!!numformat($tratam->units * $tratam->price)!!} €</td>
			<td class="wid70 textcent">{!!numformat($tratam->paid)!!} €</td>
			<td class="wid70">{!!date ('d-m-Y', strtotime ($tratam->day) )!!}</td>

			<td class="wid50 textcent">
				<a href="{!!url("/Trapac/$tratam->idtra/edit")!!}" class="btn btn-xs btn-success" role="button" title="Editar">
					<i class="fa fa-edit"></i>
				</a>
			</td>

			<td class="wid50 textcent"> 	
				<div class="btn-group">

				 	<form role="form" class="form" id="form" role="form" action="{!!url("/Trapac/$tratam->idtra")!!}" method="POST">	
				  		{!! csrf_field() !!}

						<input type="hidden" name="_method" value="DELETE">

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