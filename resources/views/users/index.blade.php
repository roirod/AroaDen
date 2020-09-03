@extends('layouts.main')

@section('content')

	@include('includes.users_nav')

	@include('includes.messages')
	
	<div class="row">

    @include('form_fields.show.form_errors')

		<div class="col-sm-3">
	    <fieldset>
	      <legend>
	        {!! @trans('aroaden.create_user') !!}
	      </legend>

        @include('users.common')

			</fieldset>
		</div>

		<div class="col-sm-9">
      <fieldset>
        <legend>
          {!! @trans('aroaden.users') !!}
        </legend>

				<div class="panel panel-default">
      		<table class="table table-bordered">
				    <thead>
		       		<tr class="fonsi14">
								<td class="wid110">{!! Lang::get('aroaden.user') !!}</td>
								<td class="wid50 textcent">{!! Lang::get('aroaden.edit') !!}</td>
								<td class="wid70 textcent">{!! Lang::get('aroaden.permissions') !!}</td>
								<td class="wid290">{!! Lang::get('aroaden.full_name') !!}</td>
							</tr>
				    </thead>
					</table>

				 	<div class="box300">
					 	 <table class="table table-striped table-bordered table-hover">
							
							@foreach ($main_loop as $obj)
								<tr> 
									<td class="wid110">{!! $obj->username !!}</td>

			            <td class="wid50 textcent">
			              <a class="btn btn-sm btn-success" href="/{{ "$main_route/$obj->uid/edit" }}">
			                <i class="fa fa-edit"></i>
			              </a>
						      </td>

									<td class="wid70 textcent">
										@if($obj->username == 'admin')

											admin

										@elseif($obj->type == 'basic')

											{{ @trans('aroaden.basic') }}

										@else

											{{ @trans('aroaden.normal') }}

										@endif
									</td>
									<td class="wid290">{!! $obj->full_name !!}</td>
								</tr>	
							@endforeach
									
						</table>
					</div>

      		<table class="table table-bordered">
			      <tfoot>
	       			<tr class="fonsi14">
								<td class="wid110">{!! Lang::get('aroaden.user') !!}</td>
								<td class="wid50 textcent">{!! Lang::get('aroaden.edit') !!}</td>
								<td class="wid70 textcent">{!! Lang::get('aroaden.permissions') !!}</td>
								<td class="wid290">{!! Lang::get('aroaden.full_name') !!}</td>
							</tr>
			      </tfoot>  
					</table>

				</div>
			</fieldset>
		</div>
	 
	</div>
 
@endsection