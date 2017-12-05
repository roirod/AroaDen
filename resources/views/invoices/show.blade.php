@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12">
    <form role="form" class="form" action="{{ url("/$main_route/$form_route") }}" method="post">
    	{!! csrf_field() !!}

    	<input type="hidden" name="id" value="{{$idpac}}">
    	
  		<div class="input-group">
  		 	<div class="input-group-btn pad10"> 
  		 		<p> {{ Lang::get('aroaden.select_invoice_type') }} </p> 
  		 	</div>
			<div class="col-sm-3"> 
				<select name="type" class="form-control" required>
					@foreach($invoice_types as $key => $val)
						@if($key == $default_type)
							<option value="{{ $key }}" selected> {{ trim($val, '"') }} </option>
						@else
							<option value="{{ $key }}"> {{ trim($val, '"') }} </option>
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