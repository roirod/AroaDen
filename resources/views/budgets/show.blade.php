@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12"> 
 	 <div class="input-group">
   	<span class="input-group-btn pad10"> <p> Presupuesto: </p> </span>
  		<div class="btn-toolbar pad4" role="toolbar"> 
    		<div class="btn-group">
	      		<a href="{!! url("/$main_route/$idpat/create") !!}" role="button" class="btn btn-sm btn-primary">
	          		<i class="fa fa-plus"></i> Nuevo
	       		</a>
       		</div>
       	</div>
     </div>
</div> </div>


<div class="row">
 <div class="col-sm-12">
   <div class="panel panel-default">
    <table class="table">
     <tr class="fonsi15 success">
     	  <td class="wid110">Fecha</td>
		  <td class="wid180">Tratamiento</td>
		  <td class="wid95 textcent">Cantidad</td>
		  <td class="wid95 textcent">Precio</td>
		  <td class="wid180"></td>
     </tr>
    </table>
   	<div class="box260">
	   	<table class="table table-striped">      	  	

		    @foreach($presup as $presu)

		    	<?php $created_at = $presu->created_at; ?>

		    	@if( isset($created_at2) && $created_at != $created_at2 )
		    		<tr class="info">
			     	  <td class="wid110"></td>
					  <td class="wid180"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid180 textcent"></td>
		    		</tr> 
		    		<tr class="danger">
			     	  <td class="wid110"></td>
					  <td class="wid180"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid180 textcent"></td>
	   				</tr>
		    		<tr class="info">
			     	  <td class="wid110"></td>
					  <td class="wid180"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid180 textcent"></td>
		    		</tr>
	   			@endif

				<?php $created_at2 = $presu->created_at; ?>

				<tr>
		 			<td class="wid110"> {!! DatTime($presu->created_at) !!} </td>
		 			<td class="wid180">{!! $presu->name !!}</td>
		 			<td class="wid95 textcent">{!! $presu->units !!}</td>
		 			<td class="wid95 textcent">{!! $presu->price !!} €</td>
		 			<td class="wid180"></td>
				</tr>
				
		    @endforeach
	    
	 	</table>

 </div> </div> </div> </div>		
			
<hr> <br>

<div class="row">
  <div class="col-sm-12"> 
  		<div class="input-group">
   		<span class="input-group-btn pad10"> 
   		<p> Editar presupuesto: </p> </span>
</div> </div> </div>


<div class="row">
  	<div class="col-sm-12">

  		@if (count($presgroup) == 0)

  			<p class="pad10"> No hay presupuestos a editar </p>

  		@else

			<form role="form" id="form" class="form" action="{!! url("/$main_route/presuedit") !!}" method="post">
				{!! csrf_field() !!}

				<input type="hidden" name="idpat" value="{!!$idpat!!}">	

				<div class="input-group">

					<div class="col-sm-9">
						<select name="uniqid" class="form-control">

							@foreach ($presgroup as $presgro)

								<option value="{!!$presgro->uniqid!!}">{!! DatTime($presgro->created_at) !!}</option>
										
							@endforeach

						</select>
					</div>	

					<div class="col-sm-2">
						<button class="btn btn-default" type="submit">
							&nbsp; <i class="fa fa-arrow-circle-right"></i> &nbsp; 
						</button>
					</div>	

				</div>
			</form>

  		@endif

</div> </div>
 
@endsection