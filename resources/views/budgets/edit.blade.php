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
          {!! @trans('aroaden.edit_budget') !!}
        </legend>

      	<div class="row">

          <div class="col-sm-6">

            <div class="row">

              <div class="col-sm-7">
                <div class="btn-toolbar pad4" role="toolbar">
                  <div class="btn-group">
                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
                      Eliminar todo
                      <span class="caret"></span> 
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <form class="form" action="{!! url("/$main_route/delBudget") !!}" method="POST">  
                        {!! csrf_field() !!}

                        <input type="hidden" name="uniqid" value="{!! $uniqid !!}"> 
                        <input type="hidden" name="idpat" value="{!! $idpat !!}"> 

                        <li><button type="submit"> <i class="fa fa-times"></i> Eliminar </button></li>
                      </form>
                    </ul>
                  </div>

                  @include('budgets.includes.saveButton')

                </div>
              </div>

              <div class="col-sm-5">
                <div class="btn-toolbar pad4" role="toolbar">

                  <div class="btn-group pull-right">
                    <a href="{!! url("/$main_route/downloadPdf/$uniqid") !!}" class="btn btn-info btn-sm">
                      {!! @trans('aroaden.download_pdf') !!}
                    </a>
                  </div>

                </div>
              </div>

            </div>

            <div class="mar10"></div>

            <div class="panel panel-default">
              <table class="table table-striped table-bordered table-hover">
                <tr class="fonsi14">
                  <td class="wid180">Tratamiento</td>
                  <td class="wid70 textcent">Cantidad</td>
                  <td class="wid70 textcent">Precio</td>          
                  <td class="wid50">Borrar</td>
                  <td class="wid50"></td>
                </tr>
              </table>

      	   		<div class="box300">
                <table class="table table-striped table-bordered table-hover">

      		   			<tbody id="budgets_list">   	  	

      				   		@foreach ($budgets as $bud)

        							<tr class="fonsi12" id="budgetId_{!! $bud->idser !!}">
        							  <td class="wid180">{!! $bud->name !!}</td>
        							  <td class="wid70 textcent">{!! $bud->units !!} </td>

                        @php
                          $price = calcTotal($bud->price, $bud->tax);                          
                        @endphp

                        <td class="wid70 textcent">{!! $price !!} €</td>

        							  <td class="wid50">
        							    <div class="btn-group"> 
        							    	<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
        							    	  <i class="fa fa-times"></i> 
        							    	  <span class="caret"></span>
        							    	</button>
        							    	<ul class="dropdown-menu" role="menu">
        							  			<li>
        							  				<button type="button" class="delBudgetLine"> <i class="fa fa-times"></i> Borrar</button>
        							  			</li>
        							  		</ul>  
        							  	</div>	
        							   </td>
        							  <td class="wid50"></td>
        							</tr>

                      <script type="text/javascript">
                        if (typeof budgetArr == 'undefined')
                          var budgetArr = [];

                        var obj = {
                          'idpat' : '{!! $bud->idpat !!}',
                          'uniqid' : '{!! $bud->uniqid !!}',
                          'idser' : '{!! $bud->idser !!}',
                          'units' : '{!! $bud->units !!}',
                          'price' : '{!! $bud->price !!}',
                          'tax' : '{!! $bud->tax !!}',
                          'created_at' : '{!! $bud->created_at !!}'
                        };

                        budgetArr.push(obj);
                      </script>

      						  @endforeach

                    <script type="text/javascript">

                      onUpdate = true;
                      budgetArray = budgetArr.slice(0);

                    </script>

      						</tbody>

                  <tr class="fonsi15">
                    <td class="wid140"></td>
                    <td class="wid140"></td>
                  </tr>
                  <tr class="fonsi15">
                    <td class="wid140"></td>
                    <td class="wid140"></td>
                  </tr>

      					</table> 

              </div>
            </div> 
          </div>

          <div class="col-sm-6">
    				<div class="form-group"> 
  				    <label class="control-label text-left mar10">Texto:</label>
  				    <textarea class="form-control" name="budgettext" rows="16">{!! $budgetstext->text !!}</textarea> 
    				</div>
          </div>

        </div>

      </fieldset>
    </div>
  </div>    

@endsection


