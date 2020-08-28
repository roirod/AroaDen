@if ($is_create_view)

	<form id="form" class="form" action="{!! url("/$main_route") !!}" method="post">
		{!! csrf_field() !!}

@else

	<form id="form" class="form" action="{{ url("/$main_route/$id") }}" method="POST">
		{!! csrf_field() !!}

		<input type="hidden" name="_method" value="PUT">

@endif








