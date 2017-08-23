@extends('layouts.presmod')

@section('content')

@include('presup.common')

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