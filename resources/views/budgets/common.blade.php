
<div class="row">
	<div class="col-sm-12">
		<p class="fonsi18">
  			{!! $company->company_name !!}
  		</p> 

  		<p class="fonsi14">
  			NIF: {!! $company->company_nif !!}
  			<br> 
  			Telf.: {!! $company->company_tel1 !!}
  			<br> 
  			{!! $company->company_address !!}
  			<br> 
  			{!! $company->company_city !!}
  			<br><br>

			Presupuesto: {!! DatTime($created_at) !!}
  		</p>
  		<br>
  	</div>
</div>

<div class="row">
	<div class="col-sm-12 fonsi15">
	  <div class="panel panel-default">
	  		<table class="table table-striped">
			  	 <tr>
					<td class="wid230">Tratamiento</td>
					<td class="wid70 textcent">IVA</td>
					<td class="wid70 textcent">Cantidad</td>
					<td class="wid70 textcent">Precio</td>			
				 </tr>

				@foreach ($budgets as $bud)
						
					<tr> 
						<td class="wid230"> {!! $bud->name !!} </td>
						<td class="wid70 textcent"> {!! $bud->tax !!} % </td>
						<td class="wid70 textcent"> {!! $bud->units !!} </td>
						<td class="wid70 textcent"> {!! numformat($bud->price) !!} € </td>
					</tr>
								
				@endforeach

				<tr>
					<td class="wid230">&nbsp;</td>
					<td class="wid70">&nbsp;</td>
					<td class="wid70">&nbsp;</td>
					<td class="wid70">&nbsp;</td>
				</tr>
				<tr>
					<td class="wid230"></td>
					<td class="wid70">&nbsp;</td>
					<td class="wid70 textder">Total IVA</td>
					<td class="wid70 textcent"> {!! numformat($taxtotal) !!} € </td> 
				</tr>
				<tr>
					<td class="wid230"></td>
					<td class="wid70">&nbsp;</td>
					<td class="wid70 textder">Total sin IVA</td>
					<td class="wid70 textcent"> {!! numformat($notaxtotal) !!} € </td> 
				</tr>
				<tr>
					<td class="wid230"></td>
					<td class="wid70">&nbsp;</td>
					<td class="wid70 textder">Total</td>
					<td class="wid70 textcent"> {!! numformat($total) !!} € </td> 
				</tr>
			</table>
		</div> 

   <br> <br>

   <p class="fonsi14">
   	{!! nl2br(e($text)) !!}
   </p>

   <p class="fonsi14">
   	{!! nl2br(e($company->budget_text)) !!}
   </p>

</div> </div>	

