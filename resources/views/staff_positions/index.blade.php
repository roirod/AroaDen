@extends('layouts.main')

@section('content')

	@include('includes.staff_nav')

	@include('includes.messages')
	@include('includes.errors')

	<div class="row"> 
	  <div class="col-sm-12"> 
	    <div class="input-group"> 
	      <span class="input-group-btn pad10">  <p> {{ Lang::get('aroaden.staff_positions') }} </p> </span>
	      <div class="btn-toolbar pad4" role="toolbar"> 
	        <div class="btn-group">
	          <a href="{{ url("/$staff_positions_route/create") }}" role="button" class="btn btn-sm btn-primary">
	            <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
	          </a>
	        </div>
	</div> </div> </div> </div>

	<div class="row">
		<div class="col-sm-12"> 
			<p class="label label-success fonsi15">
			  {{ @trans('aroaden.positions') }} <span class="badge"> {!! $count !!} </span>
			</p>
	  	</div>
	</div> 

	<br>

	<div class="row">
	  <div class="col-sm-6">
		  <div class="panel panel-default">
		    <table class="table">
		       <tr class="fonsi15 success">
		        <td class="wid180">{{ @trans('aroaden.position') }}</td>    
		        <td class="wid110">{{ @trans('aroaden.edit') }}</td>      	        
		       </tr>
		    </table>

		    <div class="box300">
		      <table class="table table-striped table-hover">

		        @foreach ($main_loop as $obj)

		         <tr>
		            <td class="wid180">{{ $obj->name }}</td>

		            <td class="wid110">
		              <a class="btn btn-sm btn-success" type="button" href="/{{ "$main_route/$obj->idstpo/edit" }}">
		                <i class="fa fa-edit"></i>
		              </a>
		            </td>
		         </tr>
		          
		        @endforeach

		      </table>
		    </div>
		  </div>
	  </div>
	</div>

@endsection
