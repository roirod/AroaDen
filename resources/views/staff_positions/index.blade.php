@extends('layouts.main')

@section('content')

	@include('includes.staff_nav')

	@include('includes.messages')
	
	<div class="row"> 
	  <div class="col-sm-12"> 
	    <div class="input-group"> 
	      <span class="input-group-btn pad10">
	      	<p> {{ Lang::get('aroaden.staff_positions') }} </p> 
	      </span>
	      <div class="btn-toolbar pad4" role="toolbar"> 
	        <div class="btn-group">
	          <a href="{{ url("/$main_route/create") }}" role="button" class="btn btn-sm btn-primary">
	            <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
	          </a>
	        </div>
	</div> </div> </div> </div>

	<div class="row">
		<div class="col-sm-12"> 
			<p class="label label-success fonsi15">
      	<span class="badge" id="countcurrentId">{!! $count !!}</span>
			  {{ @trans('aroaden.positions') }}
			</p>
	  </div>
	</div> 

	<br>

	<div class="row">
	  <div class="col-sm-6">
		  <div class="panel panel-default">
				<table class="table table-striped table-bordered table-hover">
		       <tr class="fonsi15">
			        <td class="wid180">{{ @trans('aroaden.position') }}</td>    
			        <td class="wid70 textcent">{{ @trans('aroaden.edit') }}</td>
			        <td class="wid70 textcent">{{ Lang::get('aroaden.delete') }}</td>
			        <td class="wid70 textcent"></td>
		       </tr>
		    </table>

		    <div class="box300">
			  <table class="table table-striped table-bordered table-hover">

		        @foreach ($main_loop as $obj)

		         <tr>
		            <td class="wid180">{{ $obj->name }}</td>

		            <td class="wid70 textcent">
		              <a class="btn btn-sm btn-success" type="button" href="/{{ "$main_route/$obj->idstpo/edit" }}">
		                <i class="fa fa-edit"></i>
		              </a>
		            </td>

		            <td class="wid70 textcent">  
		              <div class="btn-group">
		                <form class="form" action="{!! url("/$main_route/$obj->idstpo") !!}" data-removeTr="true" data-count="true" method="POST">
		                  <input type="hidden" name="_method" value="DELETE">

		                  <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
		                    <i class="fa fa-times"></i> <span class="caret"></span>  
		                  </button>
		                  <ul class="dropdown-menu" role="menu"> 
		                    <li>
		                      @include('includes.delete_button')
		                    </li>
		                  </ul>     
		                </form>
		              </div> 
		            </td>
			        	<td class="wid70 textcent"></td>
		         </tr>
		          
		        @endforeach

						<tr class="fonsi15">
						  <td class="wid70 textcent"></td>
						  <td class="wid70 textcent"></td>
						  <td class="wid70 textcent"></td>
						</tr>
						<tr class="fonsi15">
						  <td class="wid70 textcent"></td>
						  <td class="wid70 textcent"></td>
						  <td class="wid70 textcent"></td>
						</tr>

		      </table>
		    </div>

				<table class="table table-striped table-bordered table-hover">
		       <tr class="fonsi15">
		        <td class="wid180">{{ @trans('aroaden.position') }}</td>    
		        <td class="wid70 textcent">{{ @trans('aroaden.edit') }}</td>
		        <td class="wid70 textcent">{{ Lang::get('aroaden.delete') }}</td>
		        <td class="wid70 textcent"></td>
		       </tr>
		    </table>
		    
		  </div>
	  </div>
	</div>

	<script type="text/javascript" src="{{ asset('assets/js/confirmDelete.js') }}"></script>

	<script type="text/javascript">
		
	  currentId = 'countcurrentId';

	</script>

@endsection
