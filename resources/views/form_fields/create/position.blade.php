<div class="form-group col-sm-4">
	<label class="control-label text-left mar10">{{ @trans('aroaden.position') }}</label> 
	<select name="positions[]" class="form-control" size="4" multiple="multiple" required="required">

		<?php $inc = 1; ?>

	    @foreach($staffPositions as $pos)
	    	@if($inc === 1)

				<option value="{{ $pos->idstpo }}" selected="selected">{{ $pos->name }}</option>

	    	@else

				<option value="{{ $pos->idstpo }}">{{ $pos->name }}</option>

	    	@endif

			<?php $inc++; ?>

	    @endforeach    
	</select>
</div>