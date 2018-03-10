
	@include('includes.messages')
	@include('includes.errors')

	{!! addText("Editar servicio") !!}

	<form id="form" class="serviceForm form" action="{{ url("/$main_route/$id") }}" method="POST">
		{!! csrf_field() !!}

		<input type="hidden" name="_method" value="PUT">

        @include('form_fields.edit_alternative')

    @include('form_fields.edit.closeform')

	<script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

    @include('services.jsInclude')