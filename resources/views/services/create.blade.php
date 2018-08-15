
	@include('includes.messages')
	@include('includes.errors')

	{{ addText("Añadir servicio") }}

	<form id="form" class="createServiceForm form" action="{!! $services_route !!}" method="post">
		{!! csrf_field() !!}

        @include('form_fields.create_alternative')

    @include('form_fields.edit.closeform')
    
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/forgetChanges.js') }}"></script>

	@include('includes.util')

    @include('services.jsInclude')