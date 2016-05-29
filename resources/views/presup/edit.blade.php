@extends('layouts.main')

@include('includes.other')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


<div id="delurl" value="{{$delurl}}"> </div>

<meta name="_token" content="{!!csrf_token()!!}"/>


<div class="row">
  	<div class="col-sm-12"> 

	 	<form role="form" class="form" role="form" action="{!!url("/Presup/delcod")!!}" method="POST">	
	 		{!! csrf_field() !!}

			<input type="hidden" name="cod" value="{!!$cod!!}">	
			<input type="hidden" name="idpac" value="{!!$idpac!!}">	

			<div class="input-group"> 
				<span class="input-group-btn pad10">  <p> Eliminar todo: </p> </span>
				<div class="btn-toolbar pad10" role="toolbar">

					<div class="btn-group">
						<button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-times"></i> Eliminar 
							<span class="caret"></span> 
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><button type="submit"> <i class="fa fa-times"></i> Eliminar </button></li>
						</ul>
					</div>
				</div> 
			</div> 

		</form>

 	</div> 
</div>


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

   		<div class="box230">
	   		<table class="table table-striped">

	   			<tbody id="presup">   	  	

			   		@foreach ($presup as $presu)

							<tr>
							 	<form id="delform">

									<input type="hidden" name="idpre" value="{!!$presu->idpre!!}">
									<input type="hidden" name="cod" value="{!!$cod!!}">

									  <td class="wid140">{!!$presu->nomser!!}</td>

									  <td class="wid95 textcent">{!!$presu->precio!!} €</td>

									  <td class="wid95 textcent">{!!$presu->canti!!} €</td>

									  <td class="wid50">
									    <div class="btn-group"> 
									    	<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
									    	  <i class="fa fa-times"></i> 
									    	  <span class="caret"></span>
									    	</button>
									    	<ul class="dropdown-menu" role="menu">
									  			<li>
									  				<button type="submit">
									  					<i class="fa fa-times"></i> Borrar
									  				</button>
									  			</li>
									  		</ul>  
									  	</div>	
									   </td>

									  <td class="wid230"></td>

								</form>  
							</tr>	

					  	@endforeach

					</tbody>
  	  
				</table> 

</div> </div> </div> </div>


<div class="row pad20">
	<div class="col-sm-9"> </div>

	<div class="col-sm-3">
		<form class="form" role="form" action="presmod.php" method="post">
			<button type="submit" formtarget="_blank" name="presupmod" value="2" class="btn btn-default btn-md">Imprimir</button>
			<button type="submit" formtarget="_blank" name="presupmod" value="1" class="btn btn-primary btn-md">Crear</button>
		</form>
	</div>
</div>


@endsection
	 
@section('js')
    @parent

	  	<script type="text/javascript" src="{{ asset('assets/js/presdel.js') }}"></script>

@endsection