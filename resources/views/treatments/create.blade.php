@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	
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

    		<div class="row" id="ajax_content">
          <hr class="pad4 mar10">

          <div class="col-sm-12 mar10">
            <p class="label label-info fonsi15 pad10 mar10" id="name_price"></p>

            <div class="mar10"></div>
            <br>

            <form class="form save_form" action="/{{ $main_route }}">
			        <input type="hidden" name="idpat" value="{{ $id }}">
			        <input type="hidden" name="idser" value="">
			        <input type="hidden" name="price" value="">

			        @include('form_fields.common_alternative')
            </form>

  		    </div>
    		</div>

      </fieldset>
    </div>
  </div>


  @include('treatments.common')


  <script type="text/javascript">
    
    redirectRoute = '/{{ $routes['patients'].'/'.$id }}';

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

          var ajax_data = {
            url  : '/{{ $main_route }}/{{ $form_route }}',
            data : data
          };

          util.processAjaxReturnsJson(ajax_data).done(function(response) {
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