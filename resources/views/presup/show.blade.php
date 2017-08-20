@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12"> 
 	 <div class="input-group">
   	<span class="input-group-btn pad10"> <p> Presupuesto: </p> </span>
  		<div class="btn-toolbar pad4" role="toolbar"> 
    		<div class="btn-group">
	      		<a href="{!!url("/$main_route/$idpac/create")!!}" role="button" class="btn btn-sm btn-primary">
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

		    	<?php $code = $presu->code; ?>

		    	@if( isset($code2) && $code != $code2 )
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

				<?php $code2 = $presu->code; ?>

				<tr>
		 			<td class="wid110"> {!!DatTime($presu->code)!!} </td>
		 			<td class="wid180">{!!$presu->name!!}</td>
		 			<td class="wid95 textcent">{!!$presu->units!!}</td>
		 			<td class="wid95 textcent">{!!$presu->price!!} €</td>
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
		<form role="form" id="form" class="form" action="{!!url("/$main_route/presuedit")!!}" method="post">
			{!! csrf_field() !!}

			<input type="hidden" name="idpac" value="{!!$idpac!!}">	

			<div class="input-group">

				<div class="col-sm-9">
					<select name="code" class="form-control">

						@foreach ($presgroup as $presgro)

							<option value="{!!$presgro->code!!}">{!!DatTime($presgro->code)!!}</option>
									
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