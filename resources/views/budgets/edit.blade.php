@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')
  @include('includes.errors')

  @include('budgets.commonJs')

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

        	  <div class="col-sm-3">
        		 	<form class="form" action="{!! url("/$main_route/delBudget") !!}" method="POST">	
        		 		{!! csrf_field() !!}

        				<input type="hidden" name="uniqid" value="{!! $uniqid !!}">	
        				<input type="hidden" name="idpat" value="{!! $idpat !!}">	

        				<div class="input-group"> 
        					<span class="input-group-btn pad10">  
        						<p> Eliminar todo </p> 
        					</span>
        					<div class="btn-toolbar pad4" role="toolbar">
        						<div class="btn-group">
        							<button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
        								<i class="fa fa-times"></i> Eliminar 
        								<span class="caret"></span> 
        							</button>
        							<ul class="dropdown-menu" role="menu">
        								<li><button type="submit"> <i class="fa fa-times"></i> Eliminar </button></li>
        							</ul>
        						</div>
        					</div> 
        				</div> 
        			</form>
        	 	</div> 

        		<div class="col-sm-4">
        	 	  @include('budgets.saveButton')
        		</div> 

	       </div>

      	<div class="row">

          <div class="col-sm-7">
            <div class="panel panel-default">
              <table class="table table-striped table-bordered table-hover">
                <tr class="fonsi15">
                  <td class="wid140">Tratamiento</td>
                  <td class="wid95 textcent">Cantidad</td>
                  <td class="wid70 textcent">Precio</td>          
                  <td class="wid50"></td>
                  <td class="wid95"></td>
                </tr>
              </table>

      	   		<div class="box230">
                <table class="table table-striped table-bordered table-hover">

      		   			<tbody id="budgets_list">   	  	

      				   		@foreach ($budgets as $bud)

        							<tr class="fonsi13" id="budgetId_{!! $bud->idser !!}">
        							  <td class="wid140">{!! $bud->name !!}</td>
        							  <td class="wid95 textcent">{!! $bud->units !!} </td>
        							  <td class="wid70 textcent">{!! numformat($bud->price) !!} €</td>
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
        							  <td class="wid95"></td>
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
      					</table> 

              </div>
            </div> 
          </div>

        </div>

      	<div class="row">
      		<form class="form mode" action="{!! url("/$main_route/mode") !!}" method="POST">	
      		 	{!! csrf_field() !!}

      			<div class="col-sm-7">
      				<div class="form-group"> 
      				    <label class="control-label text-left mar10">Texto:</label>
      				    <textarea class="form-control" name="text" rows="4">{!! $budgetstext->text !!}</textarea> 
      				</div>
      			</div>

      			<div class="col-sm-7 text-right">
      				<input type="hidden" name="uniqid" value="{!! $uniqid !!}">	
      				<input type="hidden" name="idpat" value="{!! $idpat !!}">

      				<button type="submit" formtarget="_blank" name="mode" value="print" class="btn btn-default btn-md">Imprimir</button>
      				<button type="submit" formtarget="_blank" name="mode" value="create" class="btn btn-success btn-md">Ver</button>
      				<button type="submit" name="mode" class="btn btn-primary btn-md save_text">Guardar texto</button>
      			</div>
      		</form>
      	</div>

      </fieldset>
    </div>
  </div>    

@endsection
	 
@section('footer_script')

  <script type="text/javascript">
		$(document).ready(function() {
			$('.save_text').click(function (evt) {
        evt.preventDefault();
        evt.stopPropagation();

				Module.saveText();
			});

			var Module = (function( window, undefined ){
				function saveText() {
			    var data = $("form.mode").serialize();
			    data += '&mode=save_text';

          var ajax_data = {
            url  : '/{{ $main_route }}/mode',
            data : data
          };

          util.processAjaxReturnsJson(ajax_data).done(function(response) {
            return util.showPopup(response.msg);
          });
				}

        return {
          saveText: function() {
            saveText();
          }
        }

	    })(window);
  	});

  </script>

@endsection

