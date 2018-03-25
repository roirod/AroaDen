<div class="row">
	<div class="col-sm-12">
		<div class="form-group col-lg-8"> 
			<label class="control-label text-left mar10">{{ @trans('aroaden.staff') }}</label> 
			<select name="staff[]" id="staff" class="form-control" size="11" multiple="">
		        <option value="none" disabled>{{ Lang::get('aroaden.none') }}</option>

		        <?php
		        	$idsta_array = array_column($staff_works, 'idsta');
		        ?>

			    @foreach($staff as $sta)

			    	@if(in_array($sta->idsta, $idsta_array))

						<option value="{{ $sta->idsta }}" selected>
							{{ $sta->surname }}, {{ $sta->name }}({{ $sta->position }})
						</option>

			    	@else

						<option value="{{ $sta->idsta }}">
							{{ $sta->surname }}, {{ $sta->name }}({{ $sta->position }})
						</option>

			    	@endif

			    @endforeach    
			</select>
		</div>

	</div>
</div>