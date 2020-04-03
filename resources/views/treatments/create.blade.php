@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

  <div class="col-sm-12 pad10">
    @include('form_fields.show.name')
  </div>	

  <div class="row">
    <div class="col-sm-12">
      <fieldset>
        <legend>
          {!! @trans('aroaden.add_treatments') !!}
        </legend>

  	    <div class="row">
  	      <div class="col-sm-11">
    				<form id="select_form" class="form">
    					<div class="form-group col-lg-4">
                <label class="control-label text-left mar10">{{ Lang::get('aroaden.select') }}</label>

    						<select name="idser_select" id="idser_select" class="form-control" required>
    							<option value="none" selected disabled="">{{ Lang::get('aroaden.select_service') }}</option>

    							@foreach($services as $servi)
    								<option value="{{ $servi->idser }}">{{ $servi->name }}({{ $servi->price }} €)</option>
    							@endforeach

    						</select>
    					</div>
            </form>
    			</div>
    		</div>

    		<div id="loading"></div>

    		<hr>

    		<div class="row">
  		    <div class="col-sm-12 mar10" id="ajax_content">
            <p class="label label-info fonsi15 pad10 mar10" id="name_price"></p>

            <div class="mar10"></div>
            <br>

  			    @include('form_fields.fields.openform')

			        <input type="hidden" name="idpat" value="{{ $id }}">
			        <input type="hidden" name="idser" value="">
			        <input type="hidden" name="price" value="">

			        @include('form_fields.common_alternative')

  				  @include('form_fields.fields.closeform')
  		    </div>
    		</div>

      </fieldset>
    </div>
  </div>


  @include('treatments.common')


  <script type="text/javascript">
    
    $('input[name="units"]').on('change', function(evt) {
      var price = $('input[name="price"]').val();
      
      return getPaid(price);
    });

    $('#multiply_units_price').click(function (evt) {
      var price = $('input[name="price"]').val();
      
      return getPaid(price);    
    });

    $("#ajax_content").hide();

    $(document).ready(function() {
      $("#idser_select").on('change', function(evt) {
        $("#staff option:selected").removeAttr("selected");

        var val = $("#idser_select").val();

        if (val != "none") {

          util.showLoadingGif('loading');

          Module.processSelect();

        } else {

          $("#ajax_content").hide();
          
        }
      });

      var Module = (function( window, undefined ){
        function processSelect() {
          var data = $("#select_form").serialize();

          $.ajax({

            type: "POST",
            url  : '/{{ $main_route }}/{{ $form_route }}',
            dataType: "json",
            data : data

          }).done(function(response) {

            $('input[name="units"]').val(1);
            $('input[name="paid"]').val("");
            $('input[name="idser"]').attr('value', response.idser);
            $('input[name="price"]').attr('value', response.price);         
            $('input[name="paid"]').val(response.price);

            $('#name_price').empty();
            var name_price = response.name + '(' + response.price + ' €)';
            $("#name_price").text(name_price);

            //$('input[name="day"]').attr('value', util.getTodayDate());

            $('#loading').empty();
            $("#ajax_content").hide().fadeIn(300).show(0);
       
          }).fail(function() {

            $('#ajax_content').hide().html('<h3>{{ Lang::get('aroaden.error_message') }}</h3>').fadeIn('slow');

          });
        }

        return {
          processSelect: function() {
            processSelect();
          }
        }

      })(window);
    });

  </script>

@endsection