@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12">
    <form class="form" action="{{ url("/$main_route/$form_route") }}" method="post">
    	{!! csrf_field() !!}

    	<input type="hidden" name="id" value="{{ $idpat }}">
    	
  		<div class="input-group">
  		 	<div class="input-group-btn pad10"> 
  		 		<p> {{ Lang::get('aroaden.select_invoice_type') }} </p> 
  		 	</div>
			<div class="col-sm-2"> 
				<select name="type" class="form-control" required>
					@foreach($invoice_types as $type => $val)
						@if($type == $default_type)
							<option value="{{ $type }}" selected> {{ trim($val, '"') }} </option>
						@else
							<option value="{{ $type }}"> {{ trim($val, '"') }} </option>
						@endif
					@endforeach
				</select>
			</div>
			<div class="col-sm-3"> 
				<button type="submit" class="text-left btn btn-primary btn-md">{{ Lang::get('aroaden.create') }}
					<i class="fa fa-chevron-circle-right"></i>
				</button>
			</div>
    	</div>  
    </form>  
  </div> 
</div>

@endsection