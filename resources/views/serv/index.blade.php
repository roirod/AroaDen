@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!!csrf_token()!!}"/>

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
	<form role="form" class="form">
		{!! csrf_field() !!}	 
		
		<div class="input-group">
			<span class="input-group-btn pad4"> <p> &nbsp; Buscar Servicio:</p> </span>
			<div class="col-sm-4">
				<input type="search" name="busca" id="busca" class="form-control" placeholder="buscar..." autofocus required>
			</div>
		</div>
	</form>
</div>

<div class="row">
	<div class="col-sm-12" id="item_list">

	@if ($count == 0)

		<h3> No hay servicios. </h3>

	@else

		<p class="text-muted"> {!! $count !!} Servicios.</p>

	  	<div class="panel panel-default">
		  	<table class="table">
			  	 <tr class="fonsi16 success">
					<td class="wid290">Servicio</td>
					<td class="wid110 textcent">Precio</td>
					<td class="wid95 textcent">IVA</td>
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
						    	<form role="form" class="form" id="form" role="form" action="{!!url("/Servicios/$servicio->idser")!!}" method="POST">		
							  		{!! csrf_field() !!}

									<input type="hidden" name="_method" value="DELETE">

									<button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-times"></i> <span class="caret"></span>  </button>
									<ul class="dropdown-menu" role="menu"> 
										<li>
											@include('includes.delbuto')
										</li>
									</ul>			
						 		</form>
						  	</div>	
						   </td>

						  <td class="wid290"></td>
					 </tr>
					  
				  @endforeach

				</table>
			</div> </div>

			@endif

 </div> </div>

@endsection

@section('footer_script')

	<script>
		
		$(document).ready(function() {
			$.ajaxSetup({
			   	headers: { 
			   		'X-CSRF-Token' : $('meta[name=_token]').attr('content')
			   	}
			}); 

			$("#busca").on('keyup change', function(evt) {
			    if (evt.which <= 90 && evt.which >= 48 || evt.which == 8 || evt.which == 46 || evt.which == 173) {
					$('#item_list').html('<img src="/assets/img/loading.gif" /> &nbsp; &nbsp; <span class="text-muted"> Buscando... </span>');
				    var data = $("form").serialize();
	     
				    $.ajax({
				        type : 'POST',
				        url  : '/Servicios/list',
				        dataType: "json",
				        data : data,     
				    }).done(function(response) {
				    	var html = '';

				    	if (response.msg !== false) {
				    		html = '<h3>' + response.msg + '</h3>';
				    	} else {
				    		html = '<p class="text-muted">' + response.count + ' Servicios.</p>';
				    		html += '<div class="panel panel-default">';
				    		html += '   <table class="table">';
				    		html += '     <tr class="fonsi16 success">';
				    		html += '       <td class="wid290">Servicio</td>';
				    		html += '       <td class="wid110 textcent">Precio</td>';
				    		html += '       <td class="wid95 textcent">IVA</td>';
				    		html += '       <td class="wid50"></td>';
				    		html += '       <td class="wid50"></td>';
				    		html += '       <td class="wid290"></td>';
				    		html += '     </tr>';
				    		html += '   </table>';
				    		html += '  <div class="box400">';
				    		html += '    <table class="table table-hover">';

				    		$.each(response.servicios, function(index, object){
					    		html += '  <tr>';
					    		html += '    <td class="wid290">' + object.nomser + '</td>';
					    		html += '    <td class="wid110 textcent">' + object.precio + ' €</td>';
					    		html += '    <td class="wid95 textcent">' + object.iva + ' %</td>';
					    		html += '    <td class="wid50">';
					    		html += '      <a href="/Servicios/'+object.idser+'/edit" class="btn btn-xs btn-success" role="button">';
					    		html += '        <i class="fa fa-edit"></i>';
					    		html += '      </a>';
					    		html += '    </td>';
					    		html += '    <td class="wid50">';
					    		html += '      <div class="btn-group">';
					    		html += '        <form class="form" id="form" action="/Servicios/'+object.idser+'" method="POST">';
					    		html += '          {!! csrf_field() !!}';
					    		html += '          <input type="hidden" name="_method" value="DELETE">';
					    		html += '          <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">';
					    		html += '            <i class="fa fa-times"></i> <span class="caret"></span>  </button>';
					    		html += '             <ul class="dropdown-menu" role="menu">';
					    		html += '               <li>';
					    		html += '                  <button onclick="showAlert(); return false;" class="btn btn-sm btn-danger"> <i class="fa fa-times"></i> Eliminar </button>';
					    		html += '               </li>';
					    		html += '             </ul>';
					    		html += '         </form>';
					    		html += '      </div>';
					    		html += '    </td>';
					    		html += '    <td class="wid290">';
								html += '  </tr>';
				    		});

					    	html += '    </table>';
				    		html += '  </div> </div>';
				    		html += ' </div> </div>';				    		
				    	}

				        $("#item_list").html(html);	   		          
				    }).fail(function() {
				    	$("#item_list").html('<h3> Hubo un problema. </h3>');
				    });
			    }

				evt.preventDefault();
				evt.stopPropagation();
			});

		});

	</script>

@endsection