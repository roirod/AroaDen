@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!!csrf_token()!!}"/>

<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  <p> {{ Lang::get('aroaden.service') }} </p> </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{ url("/$main_route/create") }}" role="button" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
          </a>
        </div>  
</div> </div> </div> </div>

<div class="row">
	<form role="form" class="form">
		{!! csrf_field() !!}	 
		
		<div class="input-group">
			<span class="input-group-btn pad10"> <p> &nbsp; {{ Lang::get('aroaden.search_service') }}</p> </span>
			<div class="col-sm-4">
				<input type="search" name="string" id="string" class="form-control" placeholder="{{ Lang::get('aroaden.write_2_or_more') }}" autofocus required>
			</div>
			<div class="col-sm-3">
			  <a href="{{ url("/$main_route") }}" role="button" class="btn btn-md btn-danger">
			    <i class="fa fa-trash"></i> {{ Lang::get('aroaden.remove_text') }}
			  </a>
			</div>
		</div>
	</form>
</div>

<div class="row">
	<div class="col-sm-12" id="item_list">

	@if ($count == 0)

	    <p>
	      <span class="text-danger">{{ @trans('aroaden.no_services_on_db') }}</span>
	    </p>

	@else

	    <p>
	      <span class="label label-success"> {!! $count !!} {{ @trans('aroaden.services') }}</span>
	    </p>

	  	<div class="panel panel-default">
		  	<table class="table">
			  	 <tr class="fonsi15 success">
					<td class="wid290">{{ @trans('aroaden.service') }}</td>
					<td class="wid95 textcent">{{ @trans('aroaden.tax') }}</td>
					<td class="wid110 textcent">{{ @trans('aroaden.price') }}</td>					
					<td class="wid50"></td>
					<td class="wid50"></td>
					<td class="wid290"></td>
				 </tr>
			</table>
		 	<div class="box300">

		 	  <table class="table table-striped table-hover">

				  @foreach ($main_loop as $obj)

					 <tr>
						  <td class="wid290">{{ $obj->name }}</td>
						  <td class="wid95 textcent">{{ $obj->tax }} %</td>						  
						  <td class="wid110 textcent">{{ $obj->price }} €</td>

						  <td class="wid50">
						  	<a class="btn btn-xs btn-success" type="button" href="{{ url("/$main_route/$obj->idser/edit") }}">
						  		<i class="fa fa-edit"></i>
						  	</a>
						  </td>
						  
						  <td class="wid50"> 
						    <div class="btn-group"> 
						    	<form role="form" class="form" id="form" role="form" action="{!! url("/$main_route/$obj->idser") !!}" method="POST">		
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

			$("#string").on('keyup change', function(evt) {
				var string_val = $(this).val();
				var string_val_length = string_val.length;

				if (string_val != '' && string_val_length >= 2) {
					var event = evt;

			        Module.run(event);
				}

		        evt.preventDefault();
		        evt.stopPropagation();
      		});

			var Module = (function( window, undefined ){

				function runApp(event) {

				    if (event.which <= 90 && event.which >= 48 || event.which == 8 || event.which == 46 || event.which == 173) {
				    	var msg = '<img src="/assets/img/loading.gif"/> &nbsp; &nbsp; <span class="text-muted fonsi16">{{ Lang::get('aroaden.searching') }}</span>';
				    	$('#item_list').empty();
						$('#item_list').html(msg);

					    var data = $("form").serialize();
		     
					    $.ajax({

					        type : 'POST',
					        url  : '/{!! $main_route !!}/{!! $form_route !!}',
					        dataType: "json",
					        data : data

					    }).done(function(response) {
					    	var html = '';

				            if (response.error) {

				              html = '<p class="text-danger">' + response.msg + '</p>';

					    	} else {

					    		html = '<p id="searched"> <span class="label label-success">' + response.msg + ' {{ Lang::get('aroaden.services') }}</span></p>';

					    		html += '<div class="panel panel-default">';
					    		html += '   <table class="table">';
					    		html += '     <tr class="fonsi15 success">';
					    		html += '       <td class="wid290">{{ Lang::get('aroaden.service') }}</td>';
					    		html += '       <td class="wid95 textcent">{{ Lang::get('aroaden.tax') }}</td>';
					    		html += '       <td class="wid110 textcent">{{ Lang::get('aroaden.price') }}</td>';
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
						    		html += '    <td class="wid95 textcent">' + object.tax + ' %</td>';
						    		html += '    <td class="wid110 textcent">' + object.price + ' €</td>';			    		
						    		html += '    <td class="wid50">';
						    		html += '      <a href="/{!! $main_route !!}/' + object.idser + '/edit" class="btn btn-xs btn-success" role="button">';
						    		html += '        <i class="fa fa-edit"></i>';
						    		html += '      </a>';
						    		html += '    </td>';
						    		html += '    <td class="wid50">';
						    		html += '      <div class="btn-group">';
						    		html += '        <form class="form" id="form" action="/{!! $main_route !!}/'+object.idser+'" method="POST">';
						    		html += '          {!! csrf_field() !!}';
						    		html += '          <input type="hidden" name="_method" value="DELETE">';
						    		html += '          <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">';
						    		html += '            <i class="fa fa-times"></i> <span class="caret"></span>  </button>';
						    		html += '             <ul class="dropdown-menu" role="menu">';
						    		html += '               <li>';
						    		html += '					<button type="submit" class="btn btn-sm btn-danger del_btn">';
						    		html += '						<i class="fa fa-times"></i>{{ Lang::get('aroaden.delete') }}</button>';
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

					    	$('#item_list').empty();
					        $('#item_list').hide().html(html).fadeIn('slow');
					        $('#searched').prepend(' <span class="label label-primary">{{ Lang::get('aroaden.searched_text') }} ' + $('#string').val() + '</span>');

					    }).fail(function() {

					    	$('#item_list').empty();
					    	$('#item_list').hide().html('<h3>{{ Lang::get('aroaden.error') }}</h3>').fadeIn('slow');
					    	
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
