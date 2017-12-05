
<div class="row">
  <div class="col-sm-12">
    <form role="form" class="form" action="{{ url("/$main_route/$form_route") }}" method="post">
      {!! csrf_field() !!}   

      <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">

        <div class="btn-group pad10" role="group">
          <p> Selecciona el número de registros a mostrar: </p>
        </div>

        <div class="btn-group mr-2" role="group">
          <select name="num_mostrado" class="form-control">
            <option value="100">100</option> 
            <option value="200">200</option>
            <option value="500">500</option>
            <option value="1000">1.000</option> 
            <option value="2000">2.000</option>
            <option value="5000">5.000</option>
            <option value="10000">10.000</option>
            <option value="todos">Todos</option>
          </select> 
        </div>
        <div class="btn-group" role="group">
          <button type="submit" class="text-left btn btn-primary btn-md">
            <i class="fa fa-chevron-circle-right"></i>
          </button>
        </div>

      </div>        
</form>  </div>  </div>

<p>
  <span class="label label-success"> {!! $num_mostrado !!} pacientes, ordenados por resto descendiente. </span>
</p>


 <div class="row">
  <div class="col-sm-12">
   <div class="panel panel-default">
    <table class="table">
      <tr class="fonsi15 success">
        <td class="wid50"></td>
        <td class="wid290">Paciente</td>
        <td class="wid110 textcent">Total</td>
        <td class="wid110 textcent">Pagado</td> 
        <td class="wid110 text-danger textcent">Resto</td>
      </tr>
    </table>
   <div class="box400">
   	  <table class="table table-striped">

	   	  	@foreach($main_loop as $pago)
		   	  	<tr>
		   	  	  	<td class="wid50">
						         <a href="{!! url("/$other_route/$pago->idpac") !!}" target="_blank" class="btn btn-default" role="button">
							         <i class="fa fa-hand-pointer-o"></i>
						         </a>
                </td> 

                <td class="wid290">
                  <a href="{!! url("/$other_route/$pago->idpac") !!}" class="pad4" target="_blank">
                    {!!$pago->surname!!}, {!!$pago->name!!}
                  </a>
                </td>

                <td class="text-info textcent wid110">{!! numformat($pago->total) !!} € </td>
                <td class="text-muted textcent wid110">{!! numformat($pago->paid) !!} € </td>
                <td class="text-danger textcent wid110">{!! numformat($pago->rest) !!} €</td>
		 	 	    </tr>
		 	    @endforeach	

	   </table>

</div> </div> </div> </div>