	<div class="col-sm-3 pad4">
		<i class="fa fa-circle-o"></i> {{ @trans('aroaden.sex') }}: 

		@if( $object->sex == 'male' )

			{{ @trans('aroaden.male') }}

		@else

			{{ @trans('aroaden.female') }}

		@endif	  

	</div>




 