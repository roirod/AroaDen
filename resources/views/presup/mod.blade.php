@extends('layouts.presmod')

@section('content')

@include('includes.messages')
@include('includes.errors')

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

			Presupuesto: {!! DatTime($code) !!}
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

				@foreach ($tottax as $totiv)

					<tr>
						<td class="wid230"></td>
						<td class="wid70">&nbsp;</td>
						<td class="wid70 textder">Total iva:</td>
						<td class="wid70 textcent"> {!! numformat($totiv->tot) !!} € </td> 
					</tr>

				@endforeach				

				@foreach ($sintax as $sini)

					<tr>
						<td class="wid230"></td>
						<td class="wid70">&nbsp;</td>
						<td class="wid70 textder">Total sin tax:</td>
						<td class="wid70 textcent"> {!! numformat($sini->tot) !!} € </td> 
					</tr>

				@endforeach

				@foreach ($sumtot as $sum)

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
   	{!! nl2br(e($texto)) !!}
   </p>

   <p class="fonsi18">
   	{!! nl2br(e($empre->budget_text)) !!}
   </p>

</div> </div>	

@endsection