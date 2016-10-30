@if( $request->session()->has('sucmess') )

	<script type="text/javascript">

		$( document ).ready(function() {

			swal({
	            title: '{{ $request->session()->get('sucmess') }}',
	            type: 'success',
	            showConfirmButton: false,	            
	            timer: 1000
	        });

        });

	</script>

@elseif( $request->session()->has('errmess') )

	<script type="text/javascript">

		$( document ).ready(function() {

			swal({
	            title: '{{ $request->session()->get('errmess') }}',
	            type: 'error'
	        });

        });

	</script>

@endif