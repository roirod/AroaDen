@if( $request->session()->has('success_message') )

	<script type="text/javascript">
		$(document).ready(function() {
			return util.showPopup('{{ $request->session()->get('success_message') }}');
        });
	</script>

@elseif( $request->session()->has('error_message') )

	<script type="text/javascript">
		$(document).ready(function() {
			return util.showPopup('{{ $request->session()->get('error_message') }}', false);
        });
	</script>

@endif