@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')
  @include('includes.errors')

  @include('budgets.commonJs')

  <div class="row">
    <div class="col-sm-12">

    	<div class="col-sm-12 pad10">
    	    @include('form_fields.show.name')
    	</div>

      @include('budgets.saveButton')

    </div> 
  </div>

  <div class="row">
   	<div class="col-sm-7">
     	<div class="panel panel-default">

      	<table class="table">
  		    <tr class="fonsi14 success">
  				  <td class="wid140">Tratamiento</td>
  				  <td class="wid50 textcent">Cantidad</td>
  				  <td class="wid50"></td>
  				  <td class="wid70 textcent">Precio</td>
  		    </tr>
      	</table>

     		<div class="box230">
  	   		<table class="table table-striped">      	  	

  		   		@foreach ($main_loop as $service)

    					<tr>			 	
    					  <td class="wid140">{!! $service->name !!}</td>
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
    					  <td class="wid70 textcent">{!! $service->price !!} €</td>
    					</tr>	

  				  @endforeach
    	  
  				</table> 

        </div> 
      </div> 
    </div>

    {!! addText("Añadidos") !!}

   	<div class="col-sm-7">
     	<div class="panel panel-default">
      	<table class="table">
  		    <tr class="fonsi14 success">
    				<td class="wid140">Tratamiento</td>
    				<td class="wid95 textcent">Cantidad</td>
    				<td class="wid70 textcent">Precio</td>				  
    				<td class="wid50"></td>
            <td class="wid95"></td>
  		    </tr>
      	</table>

     		<div class="box400">
  	   		<table class="table table-striped">
  	   			<tbody id="budgets_list">
  	   			</tbody>
  	   		</table>
  	   	</div>
      </div> 
    </div>
  </div>

@endsection