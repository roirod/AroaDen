@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')

	<div class="row"> 
	  <div class="col-sm-12">

			<div class="input-group"> 
		    <span class="input-group-btn pad10">
          <p> {!! @trans("aroaden.patient") !!} </p>
        </span>

		    <div class="btn-toolbar pad4" role="toolbar">
					<div class="btn-group">
					  <a href="{!! url("/$main_route/$id/edit") !!}" role="button" class="btn btn-sm btn-success">
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

					@include('form_fields.show.city')

					@include('form_fields.show.address')

					@include('form_fields.show.dni')

					@include('form_fields.show.sex')

					@include('form_fields.show.tel1')

					@include('form_fields.show.tel2')

					@include('form_fields.show.tel3')

					@include('form_fields.show.birth')

					@include('form_fields.show.age')
					
				</div>

				@include('form_fields.show.notes')

	 		</div> 
	 	</div>
	</div>

	<hr> 
	<br>

	<div class="row">
	  <div class="col-sm-12"> 
	  	<div class="input-group">
	   		<span class="input-group-btn pad10"> <p> {!! @trans("aroaden.appointments") !!} </p> </span>
	   		<div class="btn-toolbar pad4" role="toolbar"> 
	    		<div class="btn-group">
			       <a href="{!! url($routes['appointments']."/$id/create") !!}" role="button" class="btn btn-sm btn-primary">
			          <i class="fa fa-plus"></i> {!! @trans("aroaden.new") !!}
			       </a>
					</div> 
				</div> 
			</div>  
		</div> 
	</div>

  <div class="row"> 
  	<div class="col-sm-12">
   		<div class="panel panel-default">

			 	<table class="table table-striped table-bordered table-hover">
			     <tr class="fonsi14">
					  <td class="wid95">{!! @trans("aroaden.hour") !!}</td>
					  <td class="wid95">{!! @trans("aroaden.day") !!}</td>
					  <td class="wid50">{!! @trans("aroaden.edit") !!}</td>
					  <td class="wid50">{!! @trans("aroaden.delete") !!}</td>
					  <td class="wid450">{!! @trans("aroaden.notes") !!}</td>
			     </tr>
		    </table>

   			<div class="box260">
				 	<table class="table table-striped table-bordered table-hover">

				    @foreach($appointments as $appo)

							<tr class="fonsi13">
					 			<td class="wid95">{!! mb_substr($appo->hour, 0, -3) !!}</td>
					 			<td class="wid95">{!!date('d-m-Y', strtotime($appo->day) )!!}</td>
					 			<td class="wid50">	
									<a href="{!! url($routes['appointments']."/$appo->idapp/edit") !!}" class="btn btn-sm btn-success" role="button" title="{!! @trans("aroaden.edit") !!}">
										<i class="fa fa-edit"></i>
									</a>
								</td>
								<td class="wid50"> 	
									<div class="btn-group">
									 	<form class="form" action="{!! url($routes['appointments']."/$appo->idapp") !!}" data-removeTr="true" method="POST">
											<input type="hidden" name="_method" value="DELETE">

											<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
											<i class="fa fa-times"></i> <span class="caret"></span>  </button>
											<ul class="dropdown-menu" role="menu"> 
												<li>
													@include('includes.delete_button')
												</li>
											</ul>			
								 		</form>

									</div> 
								</td>
								<td class="wid450">{!! $appo->notes !!}</td>
							</tr>

				    @endforeach
	    
					  <tr class="fonsi14">
						  <td class="wid180"></td>
						  <td class="wid180"></td>
					  </tr> 
					  <tr class="fonsi14">
						  <td class="wid180"></td>
						  <td class="wid180"></td>
					  </tr>

	 				</table>

	 			</div> 
			</div> 
		</div> 
	</div>		
				
	<hr> 
	<br>

	<div class="row">
	  <div class="col-sm-12"> 
		  <div class="input-group">
		  	<span class="input-group-btn pad10">  <p> {!! @trans("aroaden.treatments") !!} </p> </span>

		   	<div class="btn-toolbar pad4" role="toolbar"> 
		   		<div class="btn-group">
			       <a href="{!! url($routes['treatments']."/$id/create") !!}" role="button" class="btn btn-sm btn-primary">
			          <i class="fa fa-plus"></i> {!! @trans("aroaden.new") !!}
			       </a>
					</div> 
				</div> 
			</div> 
		</div> 
	</div>

	<div class="row">
	 	<div class="col-sm-12">
	 		<div class="panel panel-default">

				<table class="table table-striped table-bordered table-hover">				  	
				  <tr class="fonsi14">
					  <td class="wid180">{!! @trans("aroaden.service") !!}</td>
					  <td class="wid50 textcent">{!! @trans("aroaden.tax") !!}</td>			  
					  <td class="wid50 textcent">{!! @trans("aroaden.price") !!}</td>
					  <td class="wid50 textcent">{!! @trans("aroaden.units") !!}</td>
					  <td class="wid50 textcent">{!! @trans("aroaden.total") !!}</td>
					  <td class="wid50 textcent">{!! @trans("aroaden.paid") !!}</td>
					  <td class="wid70 textcent">{!! @trans("aroaden.date") !!}</td>
					  <td class="wid50 textcent">{!! @trans("aroaden.edit") !!}</td>
					  <td class="wid50 textcent">{!! @trans("aroaden.delete") !!}</td>
					  <td class="wid95">{!! @trans("aroaden.staff") !!}</td>
				  </tr> 
			  </table>

   			<div class="box260">
				  <table class="table table-striped table-bordered table-hover">				  	

				    @foreach($treatments["treatments"] as $treat)

				  		<tr class="fonsi13">
				    		<td class="wid180">{!! $treat->service_name !!}</td> 
								<td class="wid50 textcent">{!! $treat->tax !!} %</td>
								<td class="wid50 textcent">{!! numformat($treat->price) !!} €</td>
								<td class="wid50 textcent">{!! $treat->units !!}</td>
								<td class="wid50 textcent">{!! numformat($treat->units * $treat->price) !!} €</td>
								<td class="wid50 textcent">{!! numformat($treat->paid) !!} €</td>
								<td class="wid70">{!! date ('d-m-Y', strtotime ($treat->day) ) !!}</td>

								<td class="wid50 textcent">
									<a href="{!! url($routes['treatments']."/$treat->idtre/edit") !!}" class="btn btn-sm btn-success" role="button" title="{!! @trans("aroaden.edit") !!}">
										<i class="fa fa-edit"></i>
									</a>
								</td>

								<td class="wid50 textcent"> 	
									<div class="btn-group">
									 	<form class="form" action="{!! url($routes['treatments']."/$treat->idtre") !!}" data-removeTr="true" data-htmlContent="true" method="POST">	
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

								<td class="wid95">		
								
									@foreach ($treatments["staff_works"] as $staff_work)

										@if ($treat->idtre == $staff_work->idtre)

											<a href="{!! url($routes['staff']."/$staff_work->idsta") !!}" data-toggle="tooltip" title="{!! $staff_work->surname.', '.$staff_work->name !!}" target="_blank" class="btn btn-sm btn-default mar4" role="button">
												<i class="fa fa-hand-pointer-o"></i>
											</a>

										@endif
										
									@endforeach

								</td>
							</tr>

						@endforeach

					  <tr class="fonsi14">
						  <td class="wid180"></td>
						  <td class="wid180"></td>
					  </tr> 
					  <tr class="fonsi14">
						  <td class="wid180"></td>
						  <td class="wid180"></td>
					  </tr> 

	    		</table>
				</div>

			</div> 
		</div> 
	</div>		

	<hr> <br>			

	<div class="row">
	  <div class="col-sm-12">
			{!! addText(@trans("aroaden.payments")) !!}

			<div id="paymentsTable">
				@include('patients.paymentsTable')
			</div>
	  </div>
	</div>
 
@endsection

@section('footer_script')

	<script type="text/javascript" src="{{ asset('assets/js/confirmDelete.js') }}"></script>

	<script type="text/javascript">
		defaulId = 'paymentsTable';
		redirectRoute = '{!! url("/$main_route") !!}';				

	  $(document).ready(function(){
			$('[data-toggle="tooltip"]').tooltip();
	  });
	</script>
	
@endsection
