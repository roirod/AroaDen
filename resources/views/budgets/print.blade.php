
@extends('includes.layout')

@section('content')

  @include('budgets.includes.common')

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