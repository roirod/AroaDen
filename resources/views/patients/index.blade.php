@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<meta name="_token" content="{!! csrf_token() !!}"/>

<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  
      	<p>{{ Lang::get('aroaden.patient') }}</p>
      </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{url("/$main_route/create")}}" role="button" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
          </a>
        </div>  
</div> </div> </div> </div>


@include('form_fields.show.search_in')

	
<div class="row">
	<div class="col-sm-12" id="item_list">

	@if ($count == 0)

	    <p>
	      <span class="text-danger">{{ @trans('aroaden.no_patients') }}</span>
	    </p>

	@else

		<p>
			<span class="label label-success"> {!! $count !!} {{ Lang::get('aroaden.patients') }}</span>
		</p>

		  <div class="panel panel-default">
			 <table class="table">
			  	 <tr class="fonsi15 success">
					<td class="wid50">&nbsp;</td>
					<td class="wid290">{{ Lang::get('aroaden.name') }}</td>
					<td class="wid110">{{ Lang::get('aroaden.dni') }}</td>
					<td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>
					<td class="wid230">{{ Lang::get('aroaden.city') }}</td>
				 </tr>
			 </table>
	 		 <div class="box400">

		 	  <table class="table table-hover">
				
				@foreach ($main_loop as $obj)
						
					<tr> 
						<td class="wid50">
							<a href="{!! url("/$main_route/$obj->idpat") !!}" target="_blank" class="btn btn-default" role="button">
								<i class="fa fa-hand-pointer-o"></i>
							</a> 
						</td>

						<td class="wid290">
							<a href="{!! url("/$main_route/$obj->idpat") !!}" class="pad4" target="_blank">
								{!! $obj->surname !!}, {!! $obj->name !!}
							</a>
						</td>

						<td class="wid110">{!! $obj->dni !!}</td>
						<td class="wid110">{!! $obj->tel1 !!}</td>
						<td class="wid230">{!! $obj->city !!}</td> 
						
					</tr>
								
				@endforeach
					
				<table class="table table-hover">
					<tr> 
						<div class="textcent">
							<hr>
							{!! $main_loop->links() !!}
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

			$(".string_class").on('keyup change', function(event) {
				var string_val = $('#string').val();
				var string_val_length = string_val.length;

				if (string_val != '' && string_val_length > 1) {
					if (event.which <= 90 && event.which >= 48 || event.which == 8 || event.which == 46 || event.which == 173) {
			      Module.run();
          }
				}

        event.preventDefault();
        event.stopPropagation();
  		});

			var Module = (function( window, undefined ){
				function runApp() {
        	util.showLoadingGif('item_list');

          var data = $("form").serialize();

          var obj = {
            data  : data,          
            url  : '/{!! $main_route !!}/{!! $form_route !!}'
          };

          util.processAjaxReturnsJson(obj).done(function(response) {
          	var html = '';

                if (response.error) {

                  html = '<p class="text-danger">' + response.msg + '</p>';

          	} else {

          		html = '<p id="searched"> <span class="label label-success">' + response.msg + ' {{ Lang::get('aroaden.patients') }}</span></p>';

          		html += '<div class="panel panel-default">';
          		html += '   <table class="table">';
          		html += '     <tr class="fonsi15 success">';
          		html += '       <td class="wid50">&nbsp;</td>';
          		html += '       <td class="wid290">{{ Lang::get('aroaden.name') }}</td>';
          		html += '       <td class="wid110">{{ Lang::get('aroaden.dni') }}</td>';
          		html += '       <td class="wid110">{{ Lang::get('aroaden.tele1') }}</td>';
          		html += '       <td class="wid230">{{ Lang::get('aroaden.city') }}</td>';
          		html += '     </tr>';
          		html += '   </table>';
          		html += '  <div class="box400">';
          		html += '    <table class="table table-hover">';

          		$.each(response.main_loop, function(index, object){
            		html += '  <tr>';
            		html += '    <td class="wid50">';
            		html += '      <a href="/{{ $patients_route }}/'+object.idpat+'" target="_blank" class="btn btn-default" role="button">';
            		html += '        <i class="fa fa-hand-pointer-o"></i>';
            		html += '      </a>';
            		html += '    </td>';
            		html += '    <td class="wid290">';
            		html += '      <a href="/{{ $patients_route }}/'+object.idpat+'" class="pad4" target="_blank">';
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

          	$('#item_list').empty();
            $('#item_list').hide().html(html).fadeIn('slow');
            $('#searched').prepend(' <span class="label label-primary"> {{ Lang::get('aroaden.searched_text') }} ' + $('#string').val() + '</span>');

          }).fail(function() {

          	$('#item_list').empty();
          	$('#item_list').hide().html('<h3>{{ Lang::get('aroaden.error_message') }}</h3>').fadeIn('slow');
          	
          });
				}

        return {
          run: function() {
            runApp();
          }
        }

	    })(window);

  	});

	</script>

@endsection
