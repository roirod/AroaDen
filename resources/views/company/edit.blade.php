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

	    var obj = {  
	      url  : '{!! url($routes['company']."/$form_route") !!}',
	      data : $(this).serialize(),
	      method  : 'POST',
	      popup: true          
	    };

	    return util.processAjaxReturnsHtml(obj);
	  }); 
	});
</script>