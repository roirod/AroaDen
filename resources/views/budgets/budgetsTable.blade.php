
	@foreach ($budgets as $bud)

        <tr>
            <td class="wid140">{!! $bud->name !!}</td>
            <td class="wid95 textcent">{!! $bud->units !!}</td>
            <td class="wid95 textcent">{!! $bud->price !!} €</td>
            <td class="wid50">
              <form id="del_budgets_form">
                <input type="hidden" name="idbud" value="{!! $bud->idbud !!}">
                <input type="hidden" name="uniqid" value="{!! $bud->uniqid !!}">

                <div class="btn-group">
                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-times"></i>  <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu">
                      <li> <button type="submit"> <i class="fa fa-times"></i> Borrar</button></li> 
                    </ul> 
                </div>
              </form>   
            </td>
            <td class="wid230"> </td>                            
        </tr> 

	@endforeach