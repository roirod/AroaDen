@extends('layouts.main')

@section('content')

@include('includes.users_nav')

@include('includes.messages')

 <div class="row"> 
  	<div class="col-sm-10">
        <fieldset>
	          <legend>
	            {!! @trans('aroaden.del_user') !!}
	          </legend>

		 	<form class="form" id="form" action="{!! url("/$main_route/$form_route") !!}" method="POST">
		  		{!! csrf_field() !!}

				<div class="input-group"> 
					<span class="input-group-btn pad4"> <p> &nbsp; {!! Lang::get('aroaden.user') !!}:</p> </span>
		 			<div class="col-sm-6">
		 				<select name="uid" class="form-control">
	 
							@foreach($main_loop as $user)
								@continue($user->username == 'admin')
				   
				  				<option value="{!! $user->uid !!}">{!! $user->username !!}({!! $user->full_name !!})</option> 
							@endforeach
	 
	 					</select>
	 				</div>
	 			</div>

			   	<div class="pad10">
					<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-times"></i> {!! @trans("aroaden.delete") !!} <span class="caret"></span>  
					</button>
					<ul class="dropdown-menu" role="menu"> 
						<li>
							@include('includes.delete_button')
						</li>
					</ul>
	 			</div>						
	 		</form>

		</fieldset>
 	</div>
 </div>
 
@endsection