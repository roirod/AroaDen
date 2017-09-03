<div class="row">
	<div class="col-sm-12">
		<div class="form-group col-lg-6">   <label class="control-label text-left mar10">Personal 1:</label> 
			<select name="per1" id="per1" class="form-control">
			    <option value="0" selected> </option>

			    @foreach($personal as $person)
					<option value="{{$person->idper}}">
						{{$person->surname}}, {{$person->name}} | {{$person->position}}
					</option>
			    @endforeach    
			</select>
		</div>

		<div class="form-group col-lg-6">   <label class="control-label text-left mar10">Personal 2:</label> 
			<select name="per2" id="per2" class="form-control">
			    <option value="0" selected> </option>

			    @foreach($personal as $person)
					<option value="{{$person->idper}}">
						{{$person->surname}}, {{$person->name}} | {{$person->position}}
					</option>
			    @endforeach    
			</select>
		</div>
	</div>
</div>