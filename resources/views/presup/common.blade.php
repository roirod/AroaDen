
<div class="row">
	<div class="col-sm-12">
		<div class="fonsi26">
  			{!! $empre->company_name !!}
  		</div> 

  		<p class="fonsi16">
  			NIF: {!! $empre->company_nif !!}
  			<br> 
  			Telf.: {!! $empre->company_tel1 !!}
  			<br> 
  			{!! $empre->company_address !!}
  			<br> 
  			{!! $empre->company_city !!}
  			<br><br>

			Presupuesto: {!! DatTime($created_at) !!}
  		</p>
  		<br>
  	</div>
</div>


<div class="row">
	<div class="col-sm-12 fonsi16">
	  <div class="panel panel-default">
	  		<table class="table table-striped">
			  	 <tr>
					<td class="wid230">Tratamiento</td>
					<td class="wid70 textcent">IVA</td>
					<td class="wid70 textcent">Cantidad</td>
					<td class="wid70 textcent">Precio</td>			
				 </tr>

				@foreach ($presup as $pres)
						
					<tr> 
						<td class="wid230"> {!! $pres->name !!} </td>
						<td class="wid70 textcent"> {!! $pres->tax !!} % </td>
						<td class="wid70 textcent"> {!! $pres->units !!} </td>
						<td class="wid70 textcent"> {!! numformat($pres->price) !!} € </td>
					</tr>
								
				@endforeach

				<tr>
					<td class="wid230">&nbsp;</td>
					<td class="wid70">&nbsp;</td>
					<td class="wid70">&nbsp;</td>
					<td class="wid70">&nbsp;</td>
				</tr>

				@foreach ($taxtotal as $totiv)

					<tr>
						<td class="wid230"></td>
						<td class="wid70">&nbsp;</td>
						<td class="wid70 textder">Total IVA:</td>
						<td class="wid70 textcent"> {!! numformat($totiv->tot) !!} € </td> 
					</tr>

				@endforeach				

				@foreach ($notaxtotal as $sini)

					<tr>
						<td class="wid230"></td>
						<td class="wid70">&nbsp;</td>
						<td class="wid70 textder">Total sin IVA:</td>
						<td class="wid70 textcent"> {!! numformat($sini->tot) !!} € </td> 
					</tr>

				@endforeach

				@foreach ($total as $sum)

					<tr>
						<td class="wid230"></td>
						<td class="wid70">&nbsp;</td>
						<td class="wid70 textder">Total:</td>
						<td class="wid70 textcent"> {!! numformat($sum->tot) !!} € </td> 
					</tr>

				@endforeach

			</table>

		</div> 

   <br> <br>

   <p class="fonsi18">
   	{!! nl2br(e($text)) !!}
   </p>

   <p class="fonsi18">
   	{!! nl2br(e($empre->budget_text)) !!}
   </p>

</div> </div>	

