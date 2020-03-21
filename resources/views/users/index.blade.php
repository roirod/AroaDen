@extends('layouts.main')

@section('content')

@include('includes.users_nav')

@include('includes.messages')
@include('includes.errors')


<div class="row">

	<div class="col-sm-7">
        <fieldset>
	          <legend>
	            {!! @trans('aroaden.users') !!}
	          </legend>

			  <div class="panel panel-default">
					<table class="table">
					  	 <tr class="fonsi14">
							<td class="wid140">{!! Lang::get('aroaden.user') !!}</td>
							<td class="wid50 textcent">{!! Lang::get('aroaden.edit') !!}</td>
							<td class="wid95">{!! Lang::get('aroaden.permissions') !!}</td>
							<td class="wid280">{!! Lang::get('aroaden.full_name') !!}</td>
						 </tr>
					</table>
			 	<div class="box300">
				 	 <table class="table table-striped table-bordered table-hover">
						
						@foreach ($main_loop as $obj)
							<tr> 
								<td class="wid140">{!! $obj->username !!}</td>

					            <td class="wid50 textcent">
					              <a class="btn btn-xs btn-success" type="button" href="/{{ "$main_route/$obj->uid/edit" }}">
					                <i class="fa fa-edit"></i>
					              </a>
					            </td>

								<td class="wid95">
									@if($obj->type == 'basic')

										{{ @trans('aroaden.basic') }}

									@else

										{{ @trans('aroaden.normal') }}

									@endif
								</td>
								<td class="wid280">{!! $obj->full_name !!}</td>
							</tr>	
						@endforeach
								
					</table>
				</div> 
			</div>
		</fieldset>
	</div>

	<div class="col-sm-5">
        <fieldset>
          <legend>
            {!! @trans('aroaden.create_user') !!}
          </legend>

			@include('includes.messages')
			@include('includes.errors')

			@include('form_fields.common')
		</fieldset>
	</div>
 
</div>
 
@endsection