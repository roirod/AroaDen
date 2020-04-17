			@foreach( $treatments_sum as $sum )

			 	<div class="row mar10">
			 	    <div class="col-sm-3">
			 	      <table class="table table-bordered fonsi15">
			 	     	<tr class="text-info pad10">
				 	     	 <td class="wid180"> <i class="fa fa-minus"></i> &nbsp; {!! @trans("aroaden.treatments_sum") !!}</td>
				 	     	 <td class="wid95 textder"> {!! numformat($sum->total_sum) !!} €</td>
			 	     	</tr> 
			 		    <tr class="text-success pad10">
			 		    	<td class="wid180"> <i class="fa fa-minus"></i> &nbsp; {!! @trans("aroaden.paid") !!}</td>
			 		    	<td class="wid95 textder"> {!! numformat($sum->total_paid) !!} € </td>
			 		    </tr>
			 		    <tr class="text-danger danger pad10">
			 		    	<td class="wid180"> <i class="fa fa-minus"></i> &nbsp; {!! @trans("aroaden.rest") !!}</td>
			 		    	<td class="wid95 textder"> {!! numformat($sum->rest) !!} € </td>
			 		    </tr>
			 		  </table>

			 		</div>
			 	</div>

			@endforeach	 