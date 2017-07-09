@if( $request->session()->has('success_message') )

	<script type="text/javascript">

		$( document ).ready(function() {

			swal({
	            title: '{{ $request->session()->get('success_message') }}',
	            type: 'success',
	            showConfirmButton: false,	            
	            timer: 1000
	        });

        });

	</script>

@elseif( $request->session()->has('error_message') )

	<script type="text/javascript">

		$( document ).ready(function() {

			swal({
	            text: '{{ $request->session()->get('error_message') }}',
	            type: 'warning'
	        });

        });

	</script>

@endif