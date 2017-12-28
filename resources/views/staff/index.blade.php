@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!! csrf_token() !!}"/>

<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  
      	<p>{{ Lang::get('aroaden.staff') }}</p>
      </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{ url("/$main_route/create") }}" role="button" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
          </a>
        </div>  
</div> </div> </div> </div>

	
@include('form_fields.show.search_in')

	
<div class="row">
	<div class="col-sm-12" id="item_list">

	@if ($count == 0)

	    <p>
	      <span class="text-danger">{{ @trans('aroaden.no_staff_on_db') }}</span>
	    </p>

	@else

	    <p>
	      <span class="label label-success"> {!! $count !!} {{ Lang::get('aroaden.professionals') }} </span>
	    </p>

		<div class="panel panel-default">
		  <table class="table">
		 	<tr class="fonsi15 success">
				<td class="wid50">&nbsp;</td>
				<td class="wid290">{{ Lang::get('aroaden.name') }}</td>
				<td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
				<td class="wid110">{{ Lang::get('aroaden.position') }}</td>
				<td class="wid110 textcent">{{ Lang::get('aroaden.tele1') }}</td>
			</tr>
		  </table>
	 	 <div class="box400">
	 	  <table class="table table-hover">
		
			@foreach ($main_loop as $obj)	
				<tr> 
					<td class="wid50"> 
						<a class="btn btn-default" href="{{ url("/$main_route/$obj->idper") }}" target="_blank" role="button">
							<i class="fa fa-hand-pointer-o"></i>
						</a>
					</td>

					<td class="wid290">
						<a href="{{ url("/$main_route/$obj->idper") }}" class="pad4" target="_blank">
							{{ $obj->surname }}, {{ $obj->name }}
						</a>
					</td>

					<td class="wid110">{ {$obj->dni }}</td>
					<td class="wid110">{{ $obj->position }}</td> 
					<td class="wid110 textcent">{{ $obj->tel1 }}</td>
				</tr>		
			@endforeach
		
			<table class="table table-hover">
				<tr>
			 		<div class="textcent">
				 		<hr>
				 		{{ $main_loop->links() }}
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

			$(".string_class").on('keyup change', function(evt) {
				var string_val = $('#string').val();
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
					        data : data,     

					    }).done(function(response) {
					    	var html = '';

				            if (response.error) {

					    		html = '<p class="text-danger">' + response.msg + '</p>';

					    	} else {

					    		html = '<p id="searched"> <span class="label label-success">' + response.msg + ' {{ Lang::get('aroaden.professionals') }}</span></p>';

					    		html += '<div class="panel panel-default">';
					    		html += '   <table class="table">';
					    		html += '     <tr class="fonsi15 success">';
					    		html += '       <td class="wid50">&nbsp;</td>';
					    		html += '       <td class="wid290">{{ Lang::get('aroaden.name') }}</td>';
					    		html += '       <td class="wid110">{{ Lang::get('aroaden.dni') }}</td>';
					    		html += '       <td class="wid110">{{ Lang::get('aroaden.position') }}</td>';
					    		html += '       <td class="wid110 textcent">{{ Lang::get('aroaden.tele1') }}</td>';
					    		html += '     </tr>';
					    		html += '   </table>';
					    		html += '  <div class="box400">';
					    		html += '    <table class="table table-hover">';

	   							$.each(response.main_loop, function(index, object){			
						    		html += '  <tr>';
						    		html += '    <td class="wid50">';
						    		html += '      <a href="/{!! $main_route !!}/'+object.idper+'" target="_blank" class="btn btn-default" role="button">';
						    		html += '        <i class="fa fa-hand-pointer-o"></i>';
						    		html += '      </a>';
						    		html += '    </td>';
						    		html += '    <td class="wid290">';
						    		html += '      <a href="/{!! $main_route !!}/'+object.idper+'" class="pad4" target="_blank">';
						    		html += 		  object.surname + ', ' + object.name;
						    		html += '      </a>';
						    		html += '    </td>';
						    		html += '    <td class="wid110">' + object.dni + '</td>';
	 					    		html += '    <td class="wid110">' + object.position + '</td>';
						    		html += '    <td class="wid110 textcent">' + object.tel1 + '</td>';
						    		html += '  </tr>';
								});

						    	html += '    </table>';
					    		html += '  </div> </div>';
					    		html += ' </div> </div>';				    		
					    	}

					    	$('#item_list').empty();				                 
					        $('#item_list').hide().html(html).fadeIn('slow');
					        $('#searched').prepend(' <span class="label label-primary"> {{ Lang::get('aroaden.searched_text') }} ' + $('#string').val() + '</span>');

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