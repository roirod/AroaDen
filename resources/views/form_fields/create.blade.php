@include('form_fields.create.opendiv')

	@include('form_fields.create.openform')

		<div>
			@if ($form_fields['surname'])

				@include('form_fields.create.surname')

			@endif

			@if ($form_fields['name'])

				@include('form_fields.create.name')

			@endif

			@if ($form_fields['position'])

				@include('form_fields.create.position')

			@endif

			@if ($form_fields['dni'])

				@include('form_fields.create.dni')

			@endif

			@if ($form_fields['tel1'])

				@include('form_fields.create.tel1')

			@endif

			@if ($form_fields['tel2'])

				@include('form_fields.create.tel2')

			@endif

			@if ($form_fields['tel3'])

				@include('form_fields.create.tel3')

			@endif

			@if ($form_fields['sex'])

				@include('form_fields.create.sex')

			@endif

			@if ($form_fields['address'])

				@include('form_fields.create.address')

			@endif

			@if ($form_fields['city'])

				@include('form_fields.create.city')

			@endif

			@if ($form_fields['birth'])

				@include('form_fields.create.birth')

			@endif

			@if ($form_fields['units'])

				@include('form_fields.create.units')

			@endif

			@if ($form_fields['price'])

				@include('form_fields.create.price')

			@endif

			@if ($form_fields['paid'])

				@include('form_fields.create.paid')

			@endif

			@if ($form_fields['tax'])

				@include('form_fields.create.tax')

			@endif 

			@if ($form_fields['hour'])

				@include('form_fields.create.hour')

			@endif 

			@if ($form_fields['day'])

				@include('form_fields.create.day')

			@endif 

			@if ($form_fields['per'])

				@include('form_fields.create.per')

			@endif

			@if ($form_fields['issue_date'])

				@include('form_fields.create.issue_date')

			@endif

			@if ($form_fields['no_tax_msg'])

				@include('form_fields.create.no_tax_msg')

			@endif

			@if ($form_fields['notes'])

				@include('form_fields.create.notes')

			@endif

		</div>

		<div class="row">
		  <div class="col-sm-12">
			@if ($form_fields['save'])

				@include('form_fields.save')

			@endif
		  </div>
		</div>

	@include('form_fields.create.closeform')

@include('form_fields.create.closediv')