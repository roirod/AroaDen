@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')


<div id="del_url" value="{!! $del_url !!}"> </div>

<meta name="_token" content="{!! csrf_token() !!}"/>

<div class="row">
  	<div class="col-sm-3"> 

	 	<form class="form" action="{!! url("/$main_route/delCode") !!}" method="POST">	
	 		{!! csrf_field() !!}

			<input type="hidden" name="uniqid" value="{!! $uniqid !!}">	
			<input type="hidden" name="idpat" value="{!! $idpat !!}">	

			<div class="input-group"> 
				<span class="input-group-btn pad10">  <p> Eliminar todo </p> </span>
				<div class="btn-toolbar pad4" role="toolbar">

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

  <div class="col-sm-4"> 
 	 <div class="input-group">
   	<span class="input-group-btn pad10"> <p> Finalizar </p> </span>
  		<div class="btn-toolbar pad4" role="toolbar"> 
    		<div class="btn-group">
	      		<a href="{!! url("/$main_route/$idpat") !!}" role="button" class="btn btn-sm btn-primary">
	          		Finalizar
	       		</a>
       		</div>
       	</div>
     </div>
</div> 


</div>


<div class="row">
 	<div class="col-sm-12">
   	<div class="panel panel-default">

    	<table class="table">
		     <tr class="fonsi15 success">
				  <td class="wid140">Tratamiento</td>
				  <td class="wid95 textcent"> Cantidad </td>
				  <td class="wid95 textcent"> Precio </td>
				  <td class="wid50"></td>
				  <td class="wid230"></td>
		     </tr>
    	</table>

   		<div class="box230">
	   		<table class="table table-striped">

	   			<tbody id="budgets_list">   	  	

			   		@foreach ($budgets as $bud)

							<tr>
							 	<form id="del_budgets_form">
									<input type="hidden" name="idbud" value="{!! $bud->idbud !!}">
									<input type="hidden" name="uniqid" value="{!! $uniqid !!}">

									  <td class="wid140">{!! $bud->name !!}</td>
									  <td class="wid95 textcent">{!! $bud->units !!} </td>
									  <td class="wid95 textcent">{!! numformat($bud->price) !!} €</td>
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




<div class="col-sm-12">

	<form class="form mode" action="{!! url("/$main_route/mode") !!}" method="POST">	
	 	{!! csrf_field() !!}

		<div class="form-group"> 
		    <label class="control-label text-left mar10">Texto:</label>
		    <textarea class="form-control" name="text" rows="4">{!! $budgetstext->text !!}</textarea> 
		</div>
</div>

<div class="col-sm-12 text-right">

		<input type="hidden" name="uniqid" value="{!! $uniqid !!}">	
		<input type="hidden" name="idpat" value="{!! $idpat !!}">

		<button type="submit" formtarget="_blank" name="mode" value="print" class="btn btn-default btn-md">Imprimir</button>
		<button type="submit" formtarget="_blank" name="mode" value="create" class="btn btn-primary btn-md">Ver</button>
		<button type="submit" name="mode" value="save_text" class="btn btn-success btn-md save_text">Guardar texto</button>
	</form>
	
</div>


@endsection
	 
@section('footer_script')

	<script>
		
		$(document).ready(function() {
			$.ajaxSetup({
			   	headers: { 
			   		'X-CSRF-Token' : $('meta[name=_token]').attr('content')
			   	}
			}); 

			$('.save_text').click(function (evt) {
				Module.saveText();

		        evt.preventDefault();
		        evt.stopPropagation();				
			});

			var Module = (function( window, undefined ){
				function saveText() {
				    var data = $("form.mode").serialize();
				    data += '&mode=save_text';

				    $.ajax({

				        type : 'POST',
				        url  : '/{{ $main_route }}/mode',
				        dataType: "json",
				        data : data,

				    }).done(function(response) {

						util.showPopup(response.msg);
	          
				    }).fail(function() {

						util.showPopup('{{ Lang::get('aroaden.error_message') }}', false);

				    });
				}

		        return {
		          saveText: function() {
		            saveText();
		          }
		        }

		    })(window);

    	});

  	</script>

@endsection

@section('js')
    @parent

	  	<script type="text/javascript" src="{!! asset('assets/js/del_budgets.js') !!}"></script>
@endsection