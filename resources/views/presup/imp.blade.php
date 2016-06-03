@extends('layouts.presmod')

@include('includes.other')

@section('content')

@include('includes.messages')
@include('includes.errors')

<div class="row">
	<div class="col-sm-12">
		<div class="fonsi26">
  			{!! $empre->nom !!}
  		</div> 
  		<p class="lead">
  			Telf.: {!! $empre->tel1 !!}
  			<br> 
  			{!! $empre->direc !!}
  			<br> 
  			{!! $empre->pobla !!}
  			<br><br>
  			Presupuesto: {!! date('d-m-Y') !!}
  		</p>
  		<br>

  	</div>
</div>


<div class="row">
	<div class="col-sm-12 lead">
	  <div class="panel panel-default">
	  		<table class="table table-striped">
			  	 <tr>
					<td class="wid230">Tratamiento</td>
					<td class="wid95 textcent">Cantidad</td>
					<td class="wid95 textcent">Precio</td>			
				 </tr>

				@foreach ($presup as $pres)
						
					<tr> 
						<td class="wid230"> {!! $pres->nomser !!} </td>
						<td class="wid95 textcent"> {!! $pres->canti !!} </td>
						<td class="wid95 textcent"> {!! numformat($pres->precio) !!} € </td>
					</tr>
								
				@endforeach

				<tr>
					<td class="wid230">&nbsp;</td>
					<td class="wid95">&nbsp;</td>
					<td class="wid95">&nbsp;</td>
				</tr>

				@foreach ($sumtot as $sum)

					<tr>
						<td class="wid230"></td>
						<td class="wid95 textder">TOTAL:</td>
						<td class="wid95 textcent"> {!! numformat($sum->sumtot) !!} € </td> 
					</tr>

				@endforeach

			</table>

		</div> 

   <br><br>

   <p class="lead">
   	{!! nl2br(e($empre->presutex)) !!}
   </p>

</div> </div>	


<?php
	if ( isset($presmod) && $presmod == 'imp' ) {
		?>
			<script>
				window.print();
			</script>
		<?php
	}
?>

@endsection