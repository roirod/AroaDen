@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12">

	<div class="col-sm-12 pad10">
	    @include('form_fields.show.name')
	</div>

 	<div class="input-group">
   		<span class="input-group-btn pad10"> <p> Presupuesto </p> </span>
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
			  <td class="wid290"></td>
	     </tr>
    </table>
   	<div class="box260">
	   	<table class="table table-striped">      	  	

		    @foreach($budgets as $bud)

		    	@if( (isset($created_at) && $created_at != $bud->created_at) || !isset($created_at) )

		    		<tr class="danger">
			     	  <td class="wid110"></td>
					  <td class="wid180"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid290 textcent"></td>
	   				</tr>
		    		<tr class="info">
			     	  <td class="wid110"></td>
					  <td class="wid180"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid290 textcent"></td>
		    		</tr>

					<tr>
			 		  <td class="wid110"> {!! DatTime($bud->created_at) !!} </td>
					  <td class="wid180"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid95 textcent"></td>
					  <td class="wid290 textcent"></td>
					</tr>

	   			@endif

				<tr>
		 			<td class="wid110"></td>
		 			<td class="wid180">{!! $bud->name !!}</td>
		 			<td class="wid95 textcent">{!! $bud->units !!}</td>
		 			<td class="wid95 textcent">{!! $bud->price !!} €</td>
		 			<td class="wid290"></td>
				</tr>

		    	<?php $created_at = $bud->created_at; ?>
			
				
		    @endforeach
	    
	 	</table>
	</div>
</div> </div> </div>		
			
<hr> 
<br>

<div class="row">
  <div class="col-sm-12"> 
  		<div class="input-group">
   		<span class="input-group-btn pad10"> 
   		<p> Editar presupuesto: </p> </span>
</div> </div> </div>


<div class="row">
  	<div class="col-sm-12">

  		@if (count($budgets_group) == 0)

  			<p class="pad10"> No hay presupuestos a editar </p>

  		@else

			<form id="form" class="form" action="{!! url("/$main_route/editBudget") !!}" method="post">
				{!! csrf_field() !!}

				<input type="hidden" name="idpat" value="{!!$idpat!!}">	

				<div class="input-group">

					<div class="col-sm-9">
						<select name="uniqid" class="form-control">

							@foreach ($budgets_group as $bud_group)

								<option value="{!! $bud_group->uniqid !!}">{!! DatTime($bud_group->created_at) !!}</option>
										
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