<div class="row">
	<div class="col-sm-12">
		<div class="form-group col-lg-6"> 
			<label class="control-label text-left mar10">Personal 1:</label> 
			<select name="per1" id="per1" class="form-control">

		        @if($object->per1 == 0)
		            <option value="0" selected> </option>
		        @else
		            <option value="0"> </option>
		        @endif    

			    @foreach($personal as $person)

			    	@if($object->per1 == $person->idper)

						<option value="{{$person->idper}}" selected>
							{{$person->surname}}, {{$person->name}} | {{$person->position}}
						</option>

			    	@else

						<option value="{{$person->idper}}">
							{{$person->surname}}, {{$person->name}} | {{$person->position}}
						</option>

			    	@endif

			    @endforeach    
			</select>
		</div>

		<div class="form-group col-lg-6"> 
			<label class="control-label text-left mar10">Personal 2:</label> 
			<select name="per2" id="per2" class="form-control">

		        @if($object->per2 == 0)
		            <option value="0" selected> </option>
		        @else
		            <option value="0"> </option>
		        @endif   

			    @foreach($personal as $person)

			    	@if($object->per2 == $person->idper)

						<option value="{{$person->idper}}" selected>
							{{$person->surname}}, {{$person->name}} | {{$person->position}}
						</option>

			    	@else

						<option value="{{$person->idper}}">
							{{$person->surname}}, {{$person->name}} | {{$person->position}}
						</option>

			    	@endif

			    @endforeach    
			</select>
		</div>
	</div>
</div>