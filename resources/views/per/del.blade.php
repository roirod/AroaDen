@extends('layouts.main')

@section('content')

@include('includes.pernav')

@include('includes.messages')
@include('includes.errors')

 <div class="row"> 
  	<div class="col-sm-12 mar10">
  	
   	<p class="pad10">
   		Eliminar Personal:
   	</p>

	 	<form role="form" class="form" id="form" role="form" action="{!!url("/Personal/$idper")!!}" method="POST">	
	  		{!! csrf_field() !!}

			<input type="hidden" name="_method" value="DELETE">

			<div class="col-sm-12">
				<span class="lead pad4"> 

					<p> &nbsp;{!!$personal->ape!!}, {!!$personal->nom!!}</p>

				</span>
 			</div>

 			@include('includes.delbuto')
 			
 		</form>
 	</div>
 </div>
 
 @endsection