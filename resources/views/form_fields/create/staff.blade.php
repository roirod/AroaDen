<div class="row">
	<div class="col-sm-12">
		<div class="form-group col-lg-8">
			<label class="control-label text-left mar10">{{ @trans('aroaden.staff') }}</label> 
			<select name="staff[]" id="staff" class="form-control" size="11" multiple="">
			    <option value="none" disabled>{{ Lang::get('aroaden.none') }}</option>

			    @foreach($staff as $sta)
					<option value="{{ $sta->idsta }}">{{ $sta->surname }}, {{ $sta->name }}({{ $sta->position }})</option>
			    @endforeach    
			</select>
		</div>
	</div>
</div>