
  @include('includes.messages')

	<fieldset>
	  <legend>
	  	{!! @trans('aroaden.company_edit_data') !!}
	  </legend>

		<div class="row">
			<div class="col-sm-12">
			  <form id="save_form">

					@foreach ($main_loop as $item)

						<?php
							$aroaden_item_name = "aroaden.".$item['name'];
							$item_name = $item['name'];
							$item_type = $item['type'];
						?>

						@if ($item['type'] == 'text' || $item['type'] == 'email')

							<div class="form-group {{ $item['col'] }}">
							  <label class="control-label text-left mar10">{!! @trans($aroaden_item_name) !!}</label>
							  <input type="{!!  $item_type !!}" class="form-control" name="{{ $item_name }}" value="{!! $obj->$item_name !!}" 
								  @if ($item['maxlength'] != '') maxlength="{!! $item['maxlength'] !!}" @endif
								  @if ($item['pattern'] != '') pattern="{!! $item['pattern'] !!}" @endif				  
								  @if ($item['autofocus'] != '') {!! $item['autofocus'] !!} @endif
								  @if ($item['required'] != '') {!! $item['required'] !!} @endif
							  >
							</div>

						@elseif ($item['type'] == 'textarea')

							<div class="form-group {{ $item['col'] }}">
							  <label class="control-label text-left mar10">{!! @trans($aroaden_item_name) !!}</label>
							  <textarea class="form-control" name="{!! $item_name !!}" rows="{{ $item['rows'] }}">{!! $obj->$item_name !!}</textarea>
							</div>
							<br>

						@endif

					@endforeach
					
					@include('includes.submit_button')

				</form>
			</div> 
		</div>
	</fieldset>

	<script type="text/javascript">
		$(document).ready(function() {
		  $("#save_form").on('submit', function(evt) {
		    evt.preventDefault();
		    evt.stopPropagation();

		    var ajax_data = {  
		      url  : '{!! url($routes['company']."/$form_route") !!}',
		      data : $(this).serialize()
		    };

	      util.processAjaxReturnsJson(ajax_data).done(function(response) {
	        if (response.error)
	          return util.showPopup(response.msg, false);

	        var obj = {
	          url  : routes.company + '/ajaxIndex'
	        };

	        util.processAjaxReturnsHtml(obj);
	        return util.showPopup();

	      });

		  }); 
		});
	</script>