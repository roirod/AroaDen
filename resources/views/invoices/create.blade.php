@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	@include('includes.errors')

	{!! addText("Crear factura") !!}

	<div class="row">
	 <div class="col-sm-12">
	 <div class="panel panel-default">
	  <table class="table"> 
		  <tr class="fonsi15 success">
			  <td class="wid140">{!! @trans("aroaden.service") !!}</td>
			  <td class="wid70 textcent">{!! @trans("aroaden.price") !!}</td>
			  <td class="wid70 textcent">{!! @trans("aroaden.units") !!}</td>
			  <td class="wid70">{!! @trans("aroaden.date") !!}</td>
			  <td class="wid140 textcent"></td>	  
			  <td class="wid230"></td>
		   </tr> 
	   </table> 
	   <div class="box260">
	   <table class="table table-striped">

	    @foreach($treatments as $treat)
	    	<tr>
	    		<td class="wid140">{!! $treat->service_name !!}</td> 
				<td class="wid70 textcent">{!! numformat($treat->price) !!} €</td>
				<td class="wid70 textcent">{!! $treat->units !!}</td>
				<td class="wid70">{!! date ('d-m-Y', strtotime ($treat->day) ) !!}</td>
				<td class="wid140 textcent" id="treat_{!! $treat->idtre !!}"> 	
					<button class="btn btn-sm btn-info add_to_invoice" title="{!! @trans("aroaden.add_to_invoice") !!}">
						<i class="fa fa-plus"></i>
					</button>
					<button class="btn btn-sm btn-danger remove_from_invoice" title="{!! @trans("aroaden.remove_from_invoice") !!}" style="display:none;">
						<i class="fa fa-close"></i>
					</button>
				</td>
				<td class="wid230"></td>
			</tr>

			<script type="text/javascript">
				var idtre_{!! $treat->idtre !!} = {
					price: {!! $treat->price !!},
					units: {!! $treat->units !!},
					tax: {!! $treat->tax !!},
					price: {!! $treat->price !!},
					day: "{!! $treat->day !!}"
				};

			</script>
			
		@endforeach

	    </table>

	</div> </div> </div> </div>		

	<hr> <br>	



@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/webshims.js') !!}"></script>


		<script type="text/javascript">

				console.log('javascript  javascript a');



			$('button.add_to_invoice').on('click', function(e){
				e.preventDefault();

				console.log('dasfasf asfsda a');



				onAdd(this);
			});

			function onAdd(this) {
				console.dir(this);
			}
		</script>



@endsection
