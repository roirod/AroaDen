@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addText("Añadir Tratamientos al Paciente") !!}

	<div class="row">
	 <div class="col-sm-12 mar10">

		<p class="pad4 fonsi15"> {{ $surname }}, {{ $name }} </p>

		<form role="form" id="select_form" class="form">
			<div class="form-group col-lg-6">
			    <label class="control-label text-left mar10">Selecciona servicio:</label> 
				<select name="idser_select" id="idser_select" class="form-control" required>
					<option value="none" selected>Selecciona un servicio</option>

					@foreach($servicios as $servici)
						<option value="{{$servici->idser}}">{{$servici->name}} | precio: {{$servici->price}} €</option>
					@endforeach
				</select>
			</div>

		@include('form_fields.create.closeform')
	@include('form_fields.create.closediv')


	<div id="loading"></div>

	<hr>

	<div class="row">
	 <div class="col-sm-12 mar10" id="ajax_content">

	    <p class="pad4" id="name_price"></p>   

		<form role="form" id="form" class="form save_form">

	        <input type="hidden" name="idpac" value="{{$id}}">
	        <input type="hidden" name="idser" value="">
	        <input type="hidden" name="price" value="">

	        @include('form_fields.create_alternative')

		@include('form_fields.create.closeform')
	@include('form_fields.create.closediv')

@endsection


@section('footer_script')

	<meta name="_token" content="{!!csrf_token()!!}"/>

	<script>
		
		$(document).ready(function() {
			$.ajaxSetup({
			   	headers: { 
			   		'X-CSRF-Token' : $('meta[name=_token]').attr('content')
			   	}
			}); 

			$('input[name="units"]').on('change', function(evt) {
				var price = $('input[name="price"]').val();
				multi(this.value, price);

		        evt.preventDefault();
		        evt.stopPropagation();
			});

	    	var append = ' <span><small><a id="borrar" class="pad4">borrar</a></small></span>';
	    	$('input[name="paid"]').parent().find('label').append(append);

			$('#borrar').click(function (evt) {
				$('input[name="paid"]').val(0);

		        evt.preventDefault();
		        evt.stopPropagation();				
			});

			$("#ajax_content").hide();

			$(document).on('submit','form.save_form', function(evt){
				Module.saveAction();

		        evt.preventDefault();
		        evt.stopPropagation();
			});


			$("#idser_select").on('change', function(evt) {
		        $("#per1").val(0).change();
		        $("#per2").val(0).change();

				var val = $("#idser_select").val();

				if (val != "none") {

			    	var msg = '<img src="/assets/img/loading.gif"/>';
					$('#loading').html(msg);

			        Module.processSelect();

				} else {

					$("#ajax_content").hide();
				}

		        evt.preventDefault();
		        evt.stopPropagation();
      		});

			var Module = (function( window, undefined ){
				function processSelect() {
				    var data = $("#select_form").serialize();
	    
				    $.ajax({

				        type : 'POST',
				        url  : '/{{ $main_route }}/{{ $form_route }}',
				        dataType: "json",
				        data : data,

				    }).done(function(response) {
				    	$('input[name="units"]').val(1);
				    	$('input[name="day"]').val("");
				    	$('input[name="paid"]').val("");

				    	$('input[name="idser"]').attr('value', response.idser);
				    	$('input[name="price"]').attr('value', response.price);

				    	$('input[name="paid"]').val(response.price);

				    	$('#name_price').empty();
						var name_price = response.name + ' | precio: ' + response.price + ' €';
						$("#name_price").text(name_price);

	     				$('#loading').empty();
						$("#ajax_content").show().fadeIn('slow');
          
				    }).fail(function() {

				    	$('#ajax_content').hide().html('<h3> Hubo un problema. </h3>').fadeIn('slow');
				    });
				}

				function saveAction() {
				    var data = $("form.save_form").serialize();
	    
				    $.ajax({

				        type : 'POST',
				        url  : '/{{ $main_route }}',
				        dataType: "json",
				        data : data,

				    }).done(function(response) {

						swal({
				            title: response.msg,
				            type: 'success',
				            showConfirmButton: false,	            
				            timer: 1000
				        });

				        location.reload();
	          
				        $("#idser_select").val('none').change();
				        $("#per1").val(0).change();
				        $("#per2").val(0).change();
	          
				    }).fail(function() {
						swal({
				            text: 'Error!!!',
				            type: 'warning'
				        });
				    });
				}

		        return {
		          processSelect: function() {
		            processSelect();
		          },
		          saveAction: function() {
		            saveAction();
		          }
		        }

		    })(window);

    	});

  	</script>

@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/calcula.js') }}"></script>
@endsection