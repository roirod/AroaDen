@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addText(Lang::get('aroaden.add_treatments')) !!}

	<div class="row">
	 <div class="col-sm-12 mar10">

		<p class="pad4 fonsi15"> {{ $surname }}, {{ $name }} </p>

		<form id="select_form" class="form">
			<div class="form-group col-lg-6">
			    <label class="control-label text-left mar10">{{ Lang::get('aroaden.select_service') }}</label> 
				<select name="idser_select" id="idser_select" class="form-control" required>
					<option value="none" selected disabled="">{{ Lang::get('aroaden.select_service') }}</option>

					@foreach($services as $servi)
						<option value="{{ $servi->idser }}">{{ $servi->name }}({{ $servi->price }} €)</option>
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

		<form id="form" class="form save_form">

	        <input type="hidden" name="idpat" value="{{ $id }}">
	        <input type="hidden" name="idser" value="">
	        <input type="hidden" name="price" value="">

	        @include('form_fields.create_alternative')

		@include('form_fields.create.closeform')
	@include('form_fields.create.closediv')

@endsection


@section('footer_script')

	<meta name="_token" content="{!! csrf_token() !!}"/>

	<script>
		
		$(document).ready(function() {
			$.ajaxSetup({
			   	headers: { 
			   		'X-CSRF-Token' : $('meta[name=_token]').attr('content')
			   	}
			}); 

			$('input[name="units"]').on('change', function(evt) {
				var price = $('input[name="price"]').val();
				var paid = util.multiply(this.value, price);	

				$('input[name="paid"]').val(paid);

		        evt.preventDefault();
		        evt.stopPropagation();
			});

            var append = ' <a id="multiply_units_price" class="pad4 bgwi fuengrisoscu" title="{{ Lang::get('aroaden.multiply_units_price') }}"><i class="fa fa-lg fa-close"></i></a>';
            $('input[name="paid"]').parent().find('label').append(append);

            var append = ' <a id="put_zero" class="pad4 bgwi fuengrisoscu" title="{{ Lang::get('aroaden.put_zero') }}"><i class="fa fa-close fa-lg text-danger"></i></a>';
            $('input[name="paid"]').parent().find('label').append(append);

            $('#multiply_units_price').click(function (evt) {
                var price = $('input[name="price"]').val();
                var units = $('input[name="units"]').val();
                var paid = util.multiply(units, price);    

                $('input[name="paid"]').val(paid);

                evt.preventDefault();
                evt.stopPropagation();              
            });

			$('#put_zero').click(function (evt) {
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
		        $("#staff option:selected").removeAttr("selected");

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

				    	type: "POST",
				        url  : '/{{ $main_route }}/{{ $form_route }}',
				        dataType: "json",
				        data : data,

				    }).done(function(response) {

				    	$('input[name="units"]').val(1);
				    	$('input[name="day"]').val("");
				    	$('input[name="paid"]').val("");

				    	$('input[name="idser"]').attr('value', response.idser);
				    	$('input[name="price"]').attr('value', response.price);
				    	$('input[name="day"]').attr('value', util.getTodayDate());
				    	$('input[name="paid"]').val(response.price);

				    	$('#name_price').empty();
						var name_price = response.name + '(' + response.price + ' €)';
						$("#name_price").text(name_price);

	     				$('#loading').delay(600).empty();
						$("#ajax_content").delay(300).fadeIn(300).show(0);
          
				    }).fail(function() {

				    	$('#ajax_content').hide().html('<h3>{{ Lang::get('aroaden.error_message') }}</h3>').fadeIn('slow');

				    });
				}

				function saveAction() {
				    var data = $("form.save_form").serialize();
	    
				    $.ajax({

				    	type: "POST",
				        url  : '/{{ $main_route }}',
				        dataType: "json",
				        data : data,

				    }).done(function(response) {

				    	util.showPopup(response.msg);

				        location.reload();
	          
				        $("#idser_select").val('none').change();
				        $("#staff option:selected").removeAttr("selected");
	          
				    }).fail(function() {

				    	util.showPopup('{{ Lang::get('aroaden.error_message') }}', false);

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
	  <script type="text/javascript" src="{{ asset('assets/js/webshims.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/util.js') }}"></script>
@endsection