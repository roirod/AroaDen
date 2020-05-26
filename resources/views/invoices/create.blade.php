@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')

  @include('invoices.includes.commonJs')
	
  <div class="row">
    <div class="col-sm-12 pad10">
      @include('form_fields.show.name')
    </div>
  </div>

  <div class="row">
    <div class="col-sm-12">
      <fieldset>
        <legend>
          {!! @trans('aroaden.create_invoice') !!}
        </legend>

			  <div class="row">
			    <div class="col-sm-11">

						@include('invoices.includes.type')

						@include('invoices.includes.serial')

						@include('invoices.includes.place')

						@include('invoices.includes.exp_date')

			    </div>

          @include('invoices.includes.data_section')

			  </div>

			  <br>

				<div class="row fonsi12">

					<div class="col-sm-6">
            <p class="fonsi14">
              {!! @trans("aroaden.treatments_paid") !!}             
            </p>

						<div class="panel panel-default">
							<table class="table table-striped table-bordered table-hover">				  	
							  <tr class="">
								  <td class="wid120">{!! @trans("aroaden.treatment") !!}</td>
                  <td class="wid60 textcent">{!! @trans("aroaden.date") !!}</td>                  
								  <td class="wid40 textcent">{!! @trans("aroaden.units") !!}</td>	 
								  <td class="wid40 textcent">{!! @trans("aroaden.add") !!}</td>
							  </tr>
						  </table>

							<div class="box260">
								<table class="table table-striped table-bordered table-hover">

								  @foreach($items as $item)
								  	<tr>
								  		<td class="wid120">{!! $item->service_name !!}</td>
                      <td class="wid60 textcent">{!! date ('d-m-Y', strtotime ($item->day) ) !!}</td>                      
											<td class="wid40 textcent">{!! $item->units !!}</td>				
											<td class="wid40 textcent">
        						  	<button type="button" class="btn btn-sm btn-info addLine" data-idtre="{!! $item->idtre !!}" data-name="{!! $item->service_name !!}" data-units="{!! $item->units !!}" data-day="{!! date('d-m-Y', strtotime($item->day)) !!}">
        						  		<i class="fa fa-plus"></i>
        						  	</button>
											</td>
										</tr>		
									@endforeach

				    		</table>
				    	</div>
				    </div>
				  </div>

         	<div class="col-sm-6">
            <p class="fonsi14">
              {!! @trans("aroaden.treatments_added") !!}    
            </p>

           	<div class="panel panel-default">
              <table class="table table-striped table-bordered table-hover">
        		    <tr class="">
								  <td class="wid120">{!! @trans("aroaden.treatment") !!}</td> 
                  <td class="wid60 textcent">{!! @trans("aroaden.date") !!}</td>
								  <td class="wid40 textcent">{!! @trans("aroaden.units") !!}</td> 
								  <td class="wid40 textcent">{!! @trans("aroaden.remove") !!}</td>
        		    </tr>
            	</table>

           		<div class="box260">
                <table class="table table-striped table-bordered table-hover">
        	   			<tbody id="items_list">
        	   			</tbody>
        	   		</table>
        	   	</div>
            </div> 
          </div>

					@include('invoices.includes.notes')

				</div>

				@include('invoices.includes.buttons')

      </fieldset>
	  </div>
	</div>		

	@include('invoices.includes.scripts')

@endsection

