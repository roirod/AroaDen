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
		  <p>
		    <span class="label label-success"> {!! $count !!} {{ @trans('aroaden.positions') }}</span>
		  </p>

		  <div class="panel panel-default">
		    <table class="table">
		       <tr class="fonsi15 success">
		        <td class="wid180">{{ @trans('aroaden.position') }}</td>    
		        <td class="wid110">{{ @trans('aroaden.edit') }}</td>      
		        <td class="wid290"></td>
		        <td class="wid290"></td>		        
		       </tr>
		    </table>

		    <div class="box300">
		      <table class="table table-striped table-hover">

		        @foreach ($main_loop as $obj)

		         <tr>
		            <td class="wid180">{{ $obj->name }}</td>

		            <td class="wid110">
		              <a class="btn btn-xs btn-success onEdit" type="button" href="/{{ "$main_route/$obj->idstpo/edit" }}">
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

	<script type="text/javascript">
	  $(document).ready(function() {
	    $('a.onEdit').on('click', function(evt) {
	      evt.preventDefault();
	      evt.stopPropagation();

	      var _this = $(this);

	      return onEdit(_this);
	    });

	    function onEdit(_this) {
	      util.checkPermissions('staff_positions.edit').done(function(response) {
	        if (!response.permission)
	          return util.showPopup("{{ Lang::get('aroaden.deny_access') }}", false, 2500);
	      });
	    }	    
	  });
	</script>

@endsection
