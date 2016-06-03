@extends('layouts.main')

@include('includes.other')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12"> 
 	 <div class="input-group">
   	<span class="input-group-btn pad10"> <p> Presupuesto: </p> </span>
  		<div class="btn-toolbar pad10" role="toolbar"> 
    		<div class="btn-group">
	      		<a href="{!!url("/Presup/$idpac/create")!!}" role="button" class="btn btn-sm btn-primary">
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
     <tr class="fonsi16 success">
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

		    	<?php $cod = $presu->cod; ?>

		    	@if( isset($cod2) && $cod != $cod2 )
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

				<?php $cod2 = $presu->cod; ?>

				<tr>
		 			<td class="wid110"> {!!DatTime($presu->cod)!!} </td>
		 			<td class="wid180">{!!$presu->nomser!!}</td>
		 			<td class="wid95 textcent">{!!$presu->canti!!}</td>
		 			<td class="wid95 textcent">{!!$presu->precio!!} €</td>
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
		<form role="form" id="form" class="form" action="{!!url('/Presup/presuedit')!!}" method="post">
			{!! csrf_field() !!}

			<input type="hidden" name="idpac" value="{!!$idpac!!}">	

			<div class="input-group">

				<div class="col-sm-9">
					<select name="cod" class="form-control">

						@foreach ($presgroup as $presgro)

							<option value="{!!$presgro->cod!!}">{!!DatTime($presgro->cod)!!}</option>
									
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
</div> </div>
 
@endsection