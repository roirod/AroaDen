@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!!csrf_token()!!}"/>

<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  <p> Servicio: </p> </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{url("/$main_route/create")}}" role="button" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> Nuevo
          </a>
        </div>  
</div> </div> </div> </div>

<div class="row">
	<form role="form" class="form">
		{!! csrf_field() !!}	 
		
		<div class="input-group">
			<span class="input-group-btn pad10"> <p> &nbsp; Buscar Servicio:</p> </span>
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

	    <p>
	      <span class="label label-success"> {!! $count !!} Servicios </span>
	    </p>

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

				  @foreach ($main_loop as $servicio)

					 <tr>
						  <td class="wid290">{{$servicio->name}}</td>
						  <td class="wid110 textcent">{{$servicio->price}} €</td>
						  <td class="wid95 textcent">{{$servicio->tax}} %</td>

						  <td class="wid50">
						  	<a class="btn btn-xs btn-success" type="button" href="{{url("/$main_route/$servicio->idser/edit")}}">
						  		<i class="fa fa-edit"></i>
						  	</a>
						  </td>
						  
						  <td class="wid50"> 
						    <div class="btn-group"> 
						    	<form role="form" class="form" id="form" role="form" action="{!!url("/$main_route/$servicio->idser")!!}" method="POST">		
							  		{!! csrf_field() !!}

									<input type="hidden" name="_method" value="DELETE">

									<button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-times"></i> <span class="caret"></span>  </button>
									<ul class="dropdown-menu" role="menu"> 
										<li>
											@include('includes.delete_button')
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
				var event = evt;

		        Module.run(event);

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

					    		html = '<h3>' + response.msg + '</h3>';

					    	} else {

					    		html = '<p> <span class="label label-success">' + response.count + ' Servicios</span></p>';

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

					    		$.each(response.main_loop, function(index, object){
						    		html += '  <tr>';
						    		html += '    <td class="wid290">' + object.name + '</td>';
						    		html += '    <td class="wid110 textcent">' + object.price + ' €</td>';
						    		html += '    <td class="wid95 textcent">' + object.tax + ' %</td>';
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
						    		html += '					<button type="submit" class="btn btn-sm btn-danger del_btn">';
						    		html += '						<i class="fa fa-times"></i> Eliminar </button>';
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

					        $('#item_list').hide().html(html).fadeIn('slow');	          
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
