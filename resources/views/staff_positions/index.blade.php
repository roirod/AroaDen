@extends('layouts.main')

@section('content')

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
		  <p>
		    <span class="label label-success"> {!! $count !!} {{ @trans('aroaden.positions') }}</span>
		  </p>

		  <div class="panel panel-default">
		    <table class="table">
		       <tr class="fonsi15 success">
		        <td class="wid290">{{ @trans('aroaden.positions') }}</td>      
		        <td class="wid50"></td>
		        <td class="wid290"></td>
		        <td class="wid290"></td>		        
		       </tr>
		    </table>

		    <div class="box300">
		      <table class="table table-striped table-hover">

		        @foreach ($main_loop as $obj)

		         <tr>
		            <td class="wid290">{{ $obj->name }}</td>

		            <td class="wid50">
		              <a class="btn btn-xs btn-success editService" type="button" href="/{{ "$main_route/$obj->idstpo/edit" }}">
		                <i class="fa fa-edit"></i>
		              </a>
		            </td>
		            
		            <td class="wid290"></td>
		            <td class="wid290"></td>
		         </tr>
		          
		        @endforeach

		      </table>
		    </div>
		  </div>
	  </div>
	</div>

@endsection
