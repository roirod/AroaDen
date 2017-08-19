@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


<meta name="_token" content="{!!csrf_token()!!}"/>

<div id="nueurl" value="{!!$nueurl!!}"> </div>
<div id="delurl" value="{!!$delurl!!}"> </div>


<div class="row">
 	<div class="col-sm-12">
   	<div class="panel panel-default">

    	<table class="table">
		     <tr class="fonsi16 success">
				  <td class="wid140">Tratamiento</td>
				  <td class="wid95 textcent">Precio</td>
				  <td class="wid50 textcent">Cantidad</td>
				  <td class="wid50"></td>
				  <td class="wid230"></td>
		     </tr>
    	</table>

   		<div class="box230">
	   		<table class="table table-striped">      	  	

		   		@foreach ($main_loop as $servicio)

					<tr>
					 	<form id="nueform">

							<input type="hidden" name="idpac" value="{!!$id!!}">
							<input type="hidden" name="idser" value="{!!$servicio->idser!!}">
							<input type="hidden" name="price" value="{!!$servicio->price!!}">
							<input type="hidden" name="tax" value="{!!$servicio->tax!!}">
							<input type="hidden" name="code" value="{!!$code!!}">						 	

							  <td class="wid140">{!!$servicio->name!!}</td>

							  <td class="wid95 textcent">{!!$servicio->price!!} €</td>

							  <td class="wid50 textcent">
							  	 	<div class="form-group">
							  			<input type="number" min="1" step="1" value="1" class="form-control" name="units" required>
							  		</div>
							  </td>

							  <td class="wid50">
								  	<button type="submit" class="btn btn-sm btn-info">
								  		<i class="fa fa-plus"></i>
								  	</button>
							  </td>

							  <td class="wid230"></td>

						</form>  
					</tr>	

				  @endforeach
  	  
				</table> 

</div> </div> </div> </div>


{!! addtexto("Añadidos") !!}


<div class="row">
 	<div class="col-sm-12">
   	<div class="panel panel-default">

    	<table class="table">
		     <tr class="fonsi16 success">
				  <td class="wid140">Tratamiento</td>
				  <td class="wid95 textcent">Precio</td>
				  <td class="wid95 textcent">Cantidad</td>
				  <td class="wid50"></td>
				  <td class="wid230"></td>
		     </tr>
    	</table>

   		<div class="box400">
	   		<table class="table table-striped">
	   			<tbody id="presup">

	   			</tbody>
	   		</table>
	   	</div> 

</div> </div> </div>


@endsection
	 
@section('js')
    @parent

	  	<script type="text/javascript" src="{!! asset('assets/js/presnue.js') !!}"></script>
	  	<script type="text/javascript" src="{!! asset('assets/js/presdel.js') !!}"></script>

@endsection