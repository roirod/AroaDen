@extends('layouts.budgets')

@section('content')

@include('budgets.common')

<?php
	if ( isset($mode) && $mode == 'print' ) {
		?>
			<script>
				window.print();
			</script>
		<?php
	}
?>

@endsection