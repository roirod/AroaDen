@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')
  
  <div class="col-sm-12 pad10">
    @include('form_fields.show.name')
  </div>



  <div class="row">
    <div class="col-sm-12">
      <div class="mar10"></div>

      <fieldset>
        <legend>
          <div>
            {!! @trans('aroaden.record') !!}
            <span class="mar4">
              <a href="{!! url("/$main_route/$id/$form_route") !!}" role="button" class="btn btn-sm btn-success">
                 <i class="fa fa-edit"></i> {!! @trans('aroaden.edit') !!}
              </a>
            </span>
          </div>
        </legend>

        <br>

        <div class="row">
          <div class="col-sm-12 fonsi15">

             <div class="col-sm-12">
              <i class="fa fa-minus-square"></i> {!! @trans('aroaden.medical_record') !!}
              <br>
              <div class="box200">{!! nl2br(e($record->medical_record)) !!}</div>
             </div>
            
            <div class="col-sm-12">
              <br> <br>
              <i class="fa fa-minus-square"></i> {!! @trans('aroaden.diseases') !!}
              <br> 
              <div class="box200">{!! nl2br(e($record->diseases)) !!}</div>
            </div>

            <div class="col-sm-12">
              <br> <br>
              <i class="fa fa-minus-square"></i> {!! @trans('aroaden.medicines') !!}
              <br> 
              <div class="box200">{!! nl2br(e($record->medicines)) !!}</div>
            </div>

            <div class="col-sm-12">
              <br> <br>
              <i class="fa fa-minus-square"></i> {!! @trans('aroaden.allergies') !!}
              <br> 
              <div class="box200">{!! nl2br(e($record->allergies)) !!}</div>
            </div>

         </div>
        </div>

      </fieldset>
    </div>
  </div>

@endsection