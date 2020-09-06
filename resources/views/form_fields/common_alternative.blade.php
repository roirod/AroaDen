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