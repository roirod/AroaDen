@extends('layouts.main')

@section('content')

	@include('includes.staff_nav')

	@include('includes.messages')
	
	<div class="row"> 
    <div class="col-sm-12"> 
			<div class="input-group"> 
				<span class="input-group-btn pad10">  
					<p> {!! @trans("aroaden.staff") !!} </p>
				</span>

				<div class="btn-toolbar pad4" role="toolbar"> 
					<div class="btn-group">
						<a href="{{ url("/$main_route/$id/edit") }}" role="button" class="btn btn-sm btn-success">
							<i class="fa fa-edit"></i> {!! @trans("aroaden.edit") !!}
						</a>
					</div>

					<div class="btn-group">
						@include('includes.delete_dropdown')
				 	</div>

				</div> 
			</div> 
		</div> 
	</div>

	@include('form_fields.show.upload_photo')

	<hr>

	<div class="row mar10"> 
	  <div class="col-sm-12"> 
	    <div class="row fonsi15">

	    	<div id="profile_photo">
	    	    @include('form_fields.show.profile_photo')
	    	</div>

				<div class="col-sm-10">

					@include('form_fields.show.name')

					@include('form_fields.show.position')

					@include('form_fields.show.city')

					@include('form_fields.show.address')

					@include('form_fields.show.dni')

					@include('form_fields.show.tel1')

					@include('form_fields.show.tel2')

					@include('form_fields.show.birth')

				</div>

				@include('form_fields.show.notes')

	 		</div> 
		</div> 
	</div>

	<hr>
	<br>

	<div class="row">
	  <div class="col-sm-12"> 
	 		<p> Trabajos realizados: </p> 
		</div> 
	</div>

	<div class="row">
		<div class="col-sm-12">
	  	<div class="panel panel-default">
				<table class="table table-striped table-bordered table-hover">
			       <tr class="fonsi15">
				   	 	<td class="wid180">{!! @trans("aroaden.patients") !!}</td>
				   	 	<td class="wid180">{!! @trans("aroaden.treatments") !!}</td>
				   	 	<td class="wid95 textcent">{!! @trans("aroaden.units") !!}</td>
				   	 	<td class="wid95">{!! @trans("aroaden.date") !!}</td>
				   	 	<td class="wid180"> </td>
			   	 	</tr>
			   </table>

			   <div class="box400">
					 <table class="table table-striped table-bordered table-hover">

						@foreach ($treatments as $treat)
				       		<tr class="fonsi13">
								<td class="wid180">
									<a href="{{ url("/$other_route/$treat->idpat") }}" class="pad4" target="_blank">
										{{ $treat->surname }}, {{ $treat->name }}
									</a>
								</td>
							   	<td class="wid180">{{ $treat->service_name }}</td>
							   	<td class="wid95 textcent">{{ $treat->units }}</td>
							   	<td class="wid95">{{ date('d-m-Y', strtotime($treat->day)) }}</td>
							   	<td class="wid180"></td>
					   		</tr>						
						@endforeach

					 </table>
				</div>

				<table class="table table-striped table-bordered table-hover">
			       <tr class="fonsi15">
				   	 	<td class="wid180">{!! @trans("aroaden.patients") !!}</td>
				   	 	<td class="wid180">{!! @trans("aroaden.treatments") !!}</td>
				   	 	<td class="wid95 textcent">{!! @trans("aroaden.units") !!}</td>
				   	 	<td class="wid95">{!! @trans("aroaden.date") !!}</td>
				   	 	<td class="wid180"> </td>
			   	 	</tr>
			   </table>

			</div> 
		</div>
	</div>
 
@endsection

@section('footer_script')

  <script type="text/javascript" src="{{ asset('assets/js/confirmDelete.js') }}"></script>
  
	<script type="text/javascript">

		redirectRoute = '{!! url("/$main_route") !!}';				

	</script>

@endsection
