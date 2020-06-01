@extends('layouts.main')

@section('content')

	@include('includes.messages')
	
	<div class="row pad10">
	  <div class="col-sm-11">
	    <h3 class="label label-default fonsi20">
        {{ @trans("aroaden.settings") }}
      </h3>
		</div> 
	</div>

	<div class="row pad20">
    <div class="col-sm-10">

      <div class="col-sm-2">
  			<div class="panel panel-primary">
  			  <div class="panel-heading fonsi16">
  	      	{{ @trans("aroaden.company") }} 
  			  </div>
  			  <div class="panel-body textcent">
            <a href="{{ url($routes["company"]) }}" class="btn btn-sm btn-primary">
              <i class="fa fa-bars"></i>
              {{ @trans("aroaden.view") }}
            </a>
  			  </div>
  			</div>
  		</div> 

      <div class="col-sm-2">
  			<div class="panel panel-primary">
          <div class="panel-heading fonsi16">
  	      	{{ @trans("aroaden.users") }} 
  			  </div>
  			  <div class="panel-body textcent">
            <a href="{{ url($routes["users"]) }}" class="btn btn-sm btn-primary">
              <i class="fa fa-bars"></i>
              {{ @trans("aroaden.view") }}
            </a>
  			  </div>
  			</div>
  		</div> 

    </div>
	</div>

@endsection