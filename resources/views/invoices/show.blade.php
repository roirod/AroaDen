@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')

  <div class="row">

    <div class="col-sm-12 pad10">
      @include('form_fields.show.name')
    </div>

    <div class="row">
      <div class="col-sm-5">
        <fieldset>

          <legend>
            {!! @trans('aroaden.create_invoice') !!}
          </legend>

          <form class="form" action="{{ url("/$main_route/$form_route") }}" method="post">
          	{!! csrf_field() !!}

          	<input type="hidden" name="id" value="{{ $idpat }}">
          	
            <label class="control-label text-left pad10">
              {{ @trans('aroaden.select_invoice_type') }}
            </label>

            <div class="col-sm-7 pad10">
      				<select name="type" class="form-control" required>
                @foreach ($invoice_types as $key => $val)

                  @if($val == $default_type)

                    <option value="{{ $val }}" selected>

                  @else

      							<option value="{{ $val }}">

                  @endif

                      {!! @trans("aroaden.".$val) !!}
                    </option>

      					@endforeach
      				</select>
            </div>

            <br>

            <div class="col-sm-12 pad10">
      				<button type="submit" class="text-left btn btn-primary btn-md">
                {{ Lang::get('aroaden.create') }}
      					<i class="fa fa-chevron-circle-right"></i>
      				</button>
            </div>

          </form>

        </fieldset>
      </div> 
    </div>

  </div>

@endsection