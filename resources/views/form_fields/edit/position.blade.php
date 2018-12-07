<div class="form-group col-sm-4">
	<label class="control-label text-left mar10">{{ @trans('aroaden.position') }}</label> 
	<select name="positions[]" class="form-control" size="4" multiple="multiple" required="required">

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
	</select>
</div>