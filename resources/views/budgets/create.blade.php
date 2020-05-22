@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')

  @include('budgets.includes.commonJs')

    <div class="col-sm-12 pad10">
      @include('form_fields.show.name')
    </div>

    <div class="row">
      <div class="col-sm-12">
        <fieldset>
          <legend>
            {!! @trans('aroaden.create_budget') !!}
          </legend>

          <div class="row">
           	<div class="col-sm-6">

              <div class="row">
                <div class="col-sm-12">
                  @include('budgets.includes.saveButton')
                </div> 
              </div>

              <div class="mar10"></div>

             	<div class="panel panel-default">

                <table class="table table-striped table-bordered table-hover">
          		    <tr class="fonsi14">
          				  <td class="wid180">Tratamiento</td>
          				  <td class="wid50 textcent">Cantidad</td>
          				  <td class="wid50"></td>
          				  <td class="wid70 textcent">Precio</td>
          		    </tr>
              	</table>

             		<div class="box400">
                  <table class="table table-striped table-bordered table-hover">

          		   		@foreach ($main_loop as $service)

                      @php
                        $price = calcTotal($service->price, $service->tax);                          
                      @endphp

            					<tr>			 	
            					  <td class="wid180">{!! $service->name !!}</td>
            					  <td class="wid50 textcent">
            					  	 	<div class="form-group">
            					  			<input type="number" min="1" step="1" value="1" class="form-control" name="units" required>
            					  		</div>
            					  </td>
            					  <td class="wid50">
            						  	<button type="button" class="btn btn-sm btn-info addBudgetLine" data-idpat="{!! $idpat !!}" data-uniqid="{!! $uniqid !!}" data-idser="{!! $service->idser !!}" data-name="{!! $service->name !!}" data-price="{!! $service->price !!}" data-tax="{!! $service->tax !!}" data-created_at="{!! $created_at !!}">
            						  		<i class="fa fa-plus"></i>
            						  	</button>
            					  </td>
            					  <td class="wid70 textcent">{{ $price }} €</td>
            					</tr>	

          				  @endforeach
            	  
          				</table> 

                </div> 
              </div> 
            </div>


           	<div class="col-sm-6">
              <p class="fonsi16">
                Añadidos
              </p>

             	<div class="panel panel-default">
                <table class="table table-striped table-bordered table-hover">
          		    <tr class="fonsi14">
            				<td class="wid180">Tratamiento</td>
            				<td class="wid70 textcent">Cantidad</td>
            				<td class="wid70 textcent">Precio</td>				  
            				<td class="wid50"></td>
                    <td class="wid50"></td>
          		    </tr>
              	</table>

             		<div class="box400">
                  <table class="table table-striped table-bordered table-hover">
          	   			<tbody id="budgets_list">
          	   			</tbody>
          	   		</table>
          	   	</div>
              </div> 
            </div>

          </div>

        </fieldset>
      </div>
    </div>          

@endsection