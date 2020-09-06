@include('form_fields.fields.opendiv')

	@include('form_fields.fields.openform')

		<div>

			@include('form_fields.inputs')

		</div>

		<div class="row">
		  <div class="col-sm-12">
			@if ($form_fields['save'])

				@include('form_fields.save')

			@endif
		  </div>
		</div>

	@include('form_fields.fields.closeform')

@include('form_fields.fields.closediv')

@include('form_fields.form_js')
