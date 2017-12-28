		<div>
			@if ($form_fields['surname'])

				@include('form_fields.edit.surname')

			@endif

			@if ($form_fields['name'])

				@include('form_fields.edit.name')

			@endif

			@if ($form_fields['position'])

				@include('form_fields.edit.position')

			@endif

			@if ($form_fields['dni'])

				@include('form_fields.edit.dni')

			@endif

			@if ($form_fields['tel1'])

				@include('form_fields.edit.tel1')

			@endif

			@if ($form_fields['tel2'])

				@include('form_fields.edit.tel2')

			@endif

			@if ($form_fields['tel3'])

				@include('form_fields.edit.tel3')

			@endif

			@if ($form_fields['sex'])

				@include('form_fields.edit.sex')

			@endif

			@if ($form_fields['address'])

				@include('form_fields.edit.address')

			@endif

			@if ($form_fields['city'])

				@include('form_fields.edit.city')

			@endif

			@if ($form_fields['birth'])

				@include('form_fields.edit.birth')

			@endif

			@if ($form_fields['units'])

				@include('form_fields.edit.units')

			@endif

			@if ($form_fields['price'])

				@include('form_fields.edit.price')

			@endif

			@if ($form_fields['paid'])

				@include('form_fields.edit.paid')

			@endif

			@if ($form_fields['tax'])

				@include('form_fields.edit.tax')

			@endif 

			@if ($form_fields['hour'])

				@include('form_fields.edit.hour')

			@endif 

			@if ($form_fields['day'])

				@include('form_fields.edit.day')

			@endif 

			@if ($form_fields['staff'])

				@include('form_fields.edit.staff')

			@endif 

			@if ($form_fields['notes'])

				@include('form_fields.edit.notes')

			@endif
		</div>

		<div class="row">
		  <div class="col-sm-12">
			@if ($form_fields['save'])

				@include('form_fields.save')

			@endif
		  </div>
		</div>