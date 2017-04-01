@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!!csrf_token()!!}"/>

<div class="row"> 
	<div class="col-sm-12"> 
		<div class="input-group pad4"> 
			<span class="input-group-btn pad4"> <p> Paciente:</p> </span>
			<div class="col-sm-3">
				<span class="input-group-btn">
					<a href="/Pacientes/create" role="button" class="btn btn-sm btn-primary">
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

				  <span class="input-group-btn pad4"> <p> &nbsp; Buscar en:</p> </span>

				  <div class="col-sm-2">

	      			<select name="busen" class="form-control busca_class" required>

	      				<option value="apepac" selected> Apellido/s </option>
	      				<option value="dni"> DNI </option>

					</select>

				  </div>

				  <div class="col-sm-4">
				   		<input type="search" name="busca" id="busca" class="form-control busca_class" placeholder="Buscar..." autofocus required>
				  </div>				  
			 </div>
	 </form>
</div>

	
<div class="row">
	<div class="col-sm-12" id="item_list">

	@if ($count == 0)

		<h3> No hay pacientes. </h3>

	@else

		  <p class="text-muted"> {!! $count !!} Pacientes.</p>

		  <div class="panel panel-default">
			 <table class="table">
			  	 <tr class="fonsi16 success">
					<td class="wid50">&nbsp;</td>
					<td class="wid290">Nombre</td>
					<td class="wid110">DNI</td>
					<td class="wid110">Tel&#xE9;fono1</td>
					<td class="wid230">Poblaci&#xF3;n</td>
					
				 </tr>
			 </table>
	 		 <div class="box400">

		 	  <table class="table table-hover">
				
				@foreach ($pacientes as $paciente)
						
					<tr> 
						<td class="wid50">
							<a href="{!!url("/Pacientes/$paciente->idpac")!!}" target="_blank" class="btn btn-default" role="button">
								<i class="fa fa-hand-pointer-o"></i>
							</a> 
						</td>

						<td class="wid290">
							<a href="{!!url("/Pacientes/$paciente->idpac")!!}" class="pad4" target="_blank">
								{!!$paciente->apepac!!}, {!!$paciente->nompac!!}
							</a>
						</td>

						<td class="wid110">{!!$paciente->dni!!}</td>
						<td class="wid110">{!!$paciente->tel1!!}</td>
						<td class="wid230">{!!$paciente->pobla!!}</td> 
						
					</tr>
								
				@endforeach
					
				<table class="table table-hover">
					<tr> 
						<div class="textcent">
							<hr>
							{!!$pacientes->links()!!}
						</div>
					</tr> 
				</table>
			
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

			$(".busca_class").on('keyup change', function(evt) {
			    if (evt.which <= 90 && evt.which >= 48 || evt.which == 8 || evt.which == 46 || evt.which == 173) {
					$('#item_list').html('<img src="/assets/img/loading.gif" /> &nbsp; &nbsp; <span class="text-muted"> Buscando... </span>');
				    var data = $("form").serialize();
	     
				    $.ajax({
				        type : 'POST',
				        url  : '/Pacientes/list',
				        dataType: "json",
				        data : data,     
				    }).done(function(response) {
				    	var html = '';

				    	if (response.msg !== false) {
				    		html = '<h3>' + response.msg + '</h3>';
				    	} else {
				    		html = '<p class="text-muted">' + response.count + ' Pacientes.</p>';
				    		html += '<div class="panel panel-default">';
				    		html += '   <table class="table">';
				    		html += '     <tr class="fonsi16 success">';
				    		html += '       <td class="wid50">&nbsp;</td>';
				    		html += '       <td class="wid290">Nombre</td>';
				    		html += '       <td class="wid110">DNI</td>';
				    		html += '       <td class="wid110">Tel&#xE9;fono1</td>';
				    		html += '       <td class="wid230">Poblaci&#xF3;n</td>';
				    		html += '     </tr>';
				    		html += '   </table>';
				    		html += '  <div class="box400">';
				    		html += '    <table class="table table-hover">';

				    		$.each(response.pacientes, function(index, object){
					    		html += '  <tr>';
					    		html += '    <td class="wid50">';
					    		html += '      <a href="/Pacientes/'+object.idpac+'" target="_blank" class="btn btn-default" role="button">';
					    		html += '        <i class="fa fa-hand-pointer-o"></i>';
					    		html += '      </a>';
					    		html += '    </td>';
					    		html += '    <td class="wid290">';
					    		html += '      <a href="/Pacientes/'+object.idpac+'" class="pad4" target="_blank">';
					    		html += 		  object.apepac + ', ' + object.nompac;
					    		html += '      </a>';
					    		html += '    </td>';
					    		html += '    <td class="wid110">' + object.dni + '</td>';
 					    		html += '    <td class="wid110">' + object.tel1 + '</td>';
					    		html += '    <td class="wid230">' + object.pobla + '</td>';
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