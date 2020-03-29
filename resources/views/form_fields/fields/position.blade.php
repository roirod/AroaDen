
<div class="form-group col-sm-4">
	<label class="control-label text-left mar10">
		{{ @trans('aroaden.positions') }} 
	</label>

	<select name="positions[]" id="positions" multiple="multiple" required="required">

		<option data-placeholder="true"></option>

		@if ($is_create_view)

	    @foreach($staffPositions as $pos)

				<option value="{{ $pos->idstpo }}">{{ $pos->name }}</option>

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