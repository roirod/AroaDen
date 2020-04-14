@extends('layouts.main')

@section('content')

	@include('includes.patients_nav')

	@include('includes.messages')
	
	<div class="col-sm-12 pad10">
	    @include('form_fields.show.name')
	</div>

	<div class="row">
	  <div class="col-sm-12">
	    <fieldset>
	      <legend>
	        {!! @trans('aroaden.edit_record') !!}
	      </legend>

		  	<form class="form" id="form" action="{!! url("/$main_route/$id/$form_route") !!}" method="POST">
				{!! csrf_field() !!}
				<input type="hidden" name="_method" value="PUT">

				<div class="form-group col-sm-12">
				    <label class="control-label text-left mar10">{!! @trans('aroaden.medical_record') !!}</label>
				    <textarea class="form-control" name="medical_record" rows="4">{!! $record->medical_record !!}</textarea>
				</div>

				<div class="form-group col-sm-12">
				    <label class="control-label text-left mar10">{!! @trans('aroaden.diseases') !!}</label>
				    <textarea class="form-control" name="diseases" rows="4">{!! $record->diseases !!}</textarea>
				</div>

				<div class="form-group col-sm-12">
				    <label class="control-label text-left mar10">{!! @trans('aroaden.medicines') !!}</label>
				    <textarea class="form-control" name="medicines" rows="4">{!! $record->medicines !!}</textarea>
				</div>

				<div class="form-group col-sm-12">
				    <label class="control-label text-left mar10">{!! @trans('aroaden.allergies') !!}</label>
				    <textarea class="form-control" name="allergies" rows="4">{!! $record->allergies !!}</textarea>
				</div>

				<div class="form-group col-sm-12">
				    <label class="control-label text-left mar10">{!! @trans('aroaden.notes') !!}</label>
				    <textarea class="form-control" name="notes" rows="4">{!! $record->notes !!}</textarea>
				</div>
				
				@include('includes.submit_button')

		 	</form>

        </fieldset>
      </div>
    </div>

@endsection
	 
@section('js')
    @parent
    
	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/webshims.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/forgetChanges.js') !!}"></script>
	 	  
@endsection