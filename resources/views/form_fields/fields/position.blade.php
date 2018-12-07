<div class="form-group col-sm-4">
	<label class="control-label text-left mar10">
		{{ @trans('aroaden.position') }} 
		<span class="text-info fonsi11"> {{ @trans('aroaden.press_crtl_to_select') }}</span>
	</label> 
	<select name="positions[]" class="form-control" size="6" multiple="multiple" required="required">

	@if ($is_create_view)

		<?php $inc = 1; ?>

	    @foreach($staffPositions as $pos)
	    	@if($inc === 1)

				<option value="{{ $pos->idstpo }}" selected="selected">{{ $pos->name }}</option>

	    	@else

				<option value="{{ $pos->idstpo }}">{{ $pos->name }}</option>

	    	@endif

			<?php $inc++; ?>

	    @endforeach

	@else

        <?php
        	$staffPositionsEntries_array = array_column($staffPositionsEntries, 'idstpo');
        ?>

	    @foreach($staffPositions as $pos)

	    	@if(in_array($pos->idstpo, $staffPositionsEntries_array))

				<option value="{{ $pos->idstpo }}" selected="selected">{{ $pos->name }}</option>

	    	@else

				<option value="{{ $pos->idstpo }}">{{ $pos->name }}</option>

	    	@endif

	    @endforeach

	@endif
 
	</select>
</div>