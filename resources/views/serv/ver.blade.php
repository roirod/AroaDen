@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<div class="row"> 
	<div class="col-sm-12"> 
		<div class="input-group pad4"> <span class="input-group-btn pad4"> <p> Servicio:</p> </span>
			<div class="col-sm-3">
				<span class="input-group-btn">
					<a href="/Servicios/create" role="button" class="btn btn-sm btn-primary">
						<i class="fa fa-plus"></i> Nuevo
					</a> 
				</span>
			</div>
		</div> 
	</div>
</div>
	
<div class="row">
	<form role="form" class="form" action="{{url('/Servicios/ver')}}" method="post">
		{!! csrf_field() !!}	 
		
		<div class="input-group">
			<span class="input-group-btn pad4"> <p> &nbsp; Buscar Servicio:</p> </span>
			<div class="col-sm-4">
				<input type="search" name="busca" class="form-control" placeholder="buscar..." autofocus required>
			</div>
			<div class="col-sm-1">
				<button class="btn btn-default" type="submit"> 
					<i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>
		</div>
	</form>
</div>


<div class="row">
  <div class="col-sm-12 pad4 text-success fonsi16">
	&nbsp;&nbsp; Has buscado: {{$busca}}
 </div> </div>


<div class="row">
	<div class="col-sm-12">
	  <div class="panel panel-default">
		  	<table class="table">
			  	 <tr class="fonsi16 success">
					<td class="wid290">Servicio</td>
					<td class="wid110 textcent">Precio</td>
					<td class="wid110 textcent">IVA</td>
					<td class="wid50"></td>
					<td class="wid50"></td>
					<td class="wid290"></td>
				 </tr>
			</table>
		 	<div class="box300">

		 	  <table class="table table-striped table-hover">

				  @foreach ($servicios as $servicio)

					 <tr>
						  <td class="wid290">{{$servicio->nomser}}</td>
						  <td class="wid110 textcent">{{$servicio->precio}} €</td>
						  <td class="wid95 textcent">{{$servicio->iva}} %</td>

						  <td class="wid50">
						  	<a class="btn btn-xs btn-success" type="button" href="{{url("Servicios/$servicio->idser/edit")}}">
						  		<i class="fa fa-edit"></i>
						  	</a>
						  </td>
						  
						  <td class="wid50"> 
						    <div class="btn-group"> 
						    	<button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
						    	  <i class="fa fa-times"></i>
						    	  <span class="caret"></span>
						    	</button>
						    	<ul class="dropdown-menu" role="menu">
						  			<li>
						  				<a role="button" href="{{url("Servicios/$servicio->idser/del")}}">
						  					<i class="fa fa-times"></i> Eliminar
						  				</a>
						  			</li>
						  		</ul>  
						  	</div>	
						   </td>

						  <td class="wid290"></td>
					 </tr>
					  
				  @endforeach

  	  
</table> </div> </div> </div> </div>

@endsection