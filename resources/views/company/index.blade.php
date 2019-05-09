@extends('layouts.main')

@section('content')

	@include('includes.company_nav')

	<div id="ajax_content">

		@include('company.ajaxIndex')

	</div>

	<script type="text/javascript">    
		$(document).ready(function() {
		  $('a.company_route').on('click', function(evt) {
		    evt.preventDefault();
		    evt.stopPropagation();

		    var obj = {      
		      url  : $(this).attr('href') + '/ajaxIndex'
		    };

		    return util.processAjaxReturnsHtml(obj);
		  }); 
		});
	</script>

@endsection

