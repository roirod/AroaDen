@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')

  @include('budgets.includes.commonJs')

  <script type="text/javascript">

    data.updatedA = <?php echo json_encode($updatedA); ?>;  

  </script>

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

        		   		@foreach ($items as $item)

                    @php
                      $price = calcTotal($item->price, $item->tax);                          
                    @endphp

          					<tr>			 	
          					  <td class="wid180">{!! $item->name !!}</td>
          					  <td class="wid50 textcent">
          					  	 	<div class="form-group">
          					  			<input type="number" min="1" step="1" value="1" class="form-control" name="units" required>
          					  		</div>
          					  </td>
          					  <td class="wid50">
          						  <button class="btn btn-sm btn-info addLine" data-idser="{!! $item->idser !!}" data-name="{!! $item->name !!}" data-price="{!! $price !!}">
          						  		<i class="fa fa-plus"></i>
          						  </button>
          					  </td>
          					  <td class="wid70 textcent">{{ $price }} {{ $_SESSION["Alocale"]["currency_symbol"] }}</td>
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
        	   			<tbody id="items_list">
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