		<div>
			@if ($form_fields['surname'])

				@include('form_fields.fields.surname')

			@endif

			@if ($form_fields['name'])

				@include('form_fields.fields.name')

			@endif

			@if ($form_fields['dni'])

				@include('form_fields.fields.dni')

			@endif

			@if ($form_fields['tel1'])

				@include('form_fields.fields.tel1')

			@endif

			@if ($form_fields['tel2'])

				@include('form_fields.fields.tel2')

			@endif

			@if ($form_fields['tel3'])

				@include('form_fields.fields.tel3')

			@endif

			@if ($form_fields['sex'])

				@include('form_fields.fields.sex')

			@endif

			@if ($form_fields['address'])

				@include('form_fields.fields.address')

			@endif

			@if ($form_fields['city'])

				@include('form_fields.fields.city')

			@endif

			@if ($form_fields['birth'])

				@include('form_fields.fields.birth')

			@endif

			@if ($form_fields['position'])

				@include('form_fields.fields.position')

			@endif

			@if ($form_fields['tax'])

				@include('form_fields.fields.tax')

			@endif 

			@if ($form_fields['units'])

				@include('form_fields.fields.units')

			@endif

			@if ($form_fields['price'])

				@include('form_fields.fields.price')

			@endif

			@if ($form_fields['pricetax'])

				@include('form_fields.fields.pricetax')

			@endif			

			@if ($form_fields['paid'])

				@include('form_fields.fields.paid')

			@endif

			@if ($form_fields['hour'])

				@include('form_fields.fields.hour')

			@endif 

			@if ($form_fields['day'])

				@include('form_fields.fields.day')

			@endif 

			@if ($form_fields['staff'])

				@include('form_fields.fields.staff')

			@endif

			@if ($form_fields['issue_date'])

				@include('form_fields.fields.issue_date')

			@endif

			@if ($form_fields['no_tax_msg'])

				@include('form_fields.fields.no_tax_msg')

			@endif

			@if ($form_fields['notes'])

				@include('form_fields.fields.notes')

			@endif

		</div>

		<div class="row">
		  <div class="col-sm-12">
			@if ($form_fields['save'])

				@include('form_fields.save')

			@endif
		  </div>
		</div>