@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!!csrf_token()!!}"/>

<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  <p> Paciente: </p> </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{url("/$main_route/create")}}" role="button" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> Nuevo
          </a>
        </div>  
</div> </div> </div> </div>


@include('form_fields.show.search_in')

	
<div class="row">
	<div class="col-sm-12" id="item_list">

	@if ($count == 0)

		<p> No hay pacientes. </p>

	@else

		<p>
			<span class="label label-success"> {!! $count !!} Pacientes </span>
		</p>

		  <div class="panel panel-default">
			 <table class="table">
			  	 <tr class="fonsi15 success">
					<td class="wid50">&nbsp;</td>
					<td class="wid290">Nombre</td>
					<td class="wid110">DNI</td>
					<td class="wid110">Tel&#xE9;fono1</td>
					<td class="wid230">Poblaci&#xF3;n</td>
					
				 </tr>
			 </table>
	 		 <div class="box400">

		 	  <table class="table table-hover">
				
				@foreach ($main_loop as $paciente)
						
					<tr> 
						<td class="wid50">
							<a href="{!! url("/$main_route/$paciente->idpac")!!}" target="_blank" class="btn btn-default" role="button">
								<i class="fa fa-hand-pointer-o"></i>
							</a> 
						</td>

						<td class="wid290">
							<a href="{!! url("/$main_route/$paciente->idpac")!!}" class="pad4" target="_blank">
								{!!$paciente->surname!!}, {!!$paciente->name!!}
							</a>
						</td>

						<td class="wid110">{!!$paciente->dni!!}</td>
						<td class="wid110">{!!$paciente->tel1!!}</td>
						<td class="wid230">{!!$paciente->city!!}</td> 
						
					</tr>
								
				@endforeach
					
				<table class="table table-hover">
					<tr> 
						<div class="textcent">
							<hr>
							{!!$main_loop->links()!!}
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
				var busca_val = $('#busca').val();
				var busca_val_length = busca_val.length;

				if (busca_val != '' && busca_val_length >= 2) {
					var event = evt;

			        Module.run(event);
				}

		        evt.preventDefault();
		        evt.stopPropagation();
      		});

			var Module = (function( window, undefined ){

				function runApp(event) {

				    if (event.which <= 90 && event.which >= 48 || event.which == 8 || event.which == 46 || event.which == 173) {
				    	var wait = '<img src="/assets/img/loading.gif"/> &nbsp; &nbsp; <span class="text-muted"> Buscando... </span>';
						$('#item_list').html(wait);

					    var data = $("form").serialize();
		     
					    $.ajax({

					        type : 'POST',
					        url  : '/{!! $main_route !!}/{!! $form_route !!}',
					        dataType: "json",
					        data : data,

					    }).done(function(response) {
					    	var html = '';

					    	if (response.msg !== false) {

					    		html = '<p>' + response.msg + '</p>';

					    	} else {

					    		html = '<p id="buscado"> <span class="label label-success">' + response.count + ' Pacientes</span></p>';

					    		html += '<div class="panel panel-default">';
					    		html += '   <table class="table">';
					    		html += '     <tr class="fonsi15 success">';
					    		html += '       <td class="wid50">&nbsp;</td>';
					    		html += '       <td class="wid290">Nombre</td>';
					    		html += '       <td class="wid110">DNI</td>';
					    		html += '       <td class="wid110">Tel&#xE9;fono1</td>';
					    		html += '       <td class="wid230">Poblaci&#xF3;n</td>';
					    		html += '     </tr>';
					    		html += '   </table>';
					    		html += '  <div class="box400">';
					    		html += '    <table class="table table-hover">';

					    		$.each(response.main_loop, function(index, object){
						    		html += '  <tr>';
						    		html += '    <td class="wid50">';
						    		html += '      <a href="/Pacientes/'+object.idpac+'" target="_blank" class="btn btn-default" role="button">';
						    		html += '        <i class="fa fa-hand-pointer-o"></i>';
						    		html += '      </a>';
						    		html += '    </td>';
						    		html += '    <td class="wid290">';
						    		html += '      <a href="/Pacientes/'+object.idpac+'" class="pad4" target="_blank">';
						    		html += 		  object.surname + ', ' + object.name;
						    		html += '      </a>';
						    		html += '    </td>';
						    		html += '    <td class="wid110">' + object.dni + '</td>';
	 					    		html += '    <td class="wid110">' + object.tel1 + '</td>';
						    		html += '    <td class="wid230">' + object.city + '</td>';
						    		html += '  </tr>';
					    		});

						    	html += '    </table>';
					    		html += '  </div> </div>';
					    		html += ' </div> </div>';				    		
					    	}

					        $('#item_list').hide().html(html).fadeIn('slow');
					        $('#buscado').prepend(' <span class="label label-primary"> Texto buscado: ' + $('#busca').val() + '</span>');

					    }).fail(function() {

					    	$('#item_list').hide().html('<h3> Hubo un problema. </h3>').fadeIn('slow');
					    	
					    });
				    }
				}

		        return {
		          run: function(event) {
		            runApp(event);
		          }
		        }

		    })(window);

    	});

  	</script>

@endsection
