@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')

  <div class="row">
    <div class="col-sm-12 pad10">
      @include('form_fields.show.name')
    </div>
  </div>

  <div class="row">

    <div class="col-sm-3">
      <fieldset>

        <legend>
          {!! @trans('aroaden.create_invoice') !!}
        </legend>

        <form class="form" action="{{ url("/$main_route/$form_route") }}" method="post">
        	{!! csrf_field() !!}

        	<input type="hidden" name="id" value="{{ $idpat }}">
        	
          <label class="control-label text-left pad4">
            {{ @trans('aroaden.select_invoice_type') }}
          </label>

          <div class="col-sm-10 pad4">
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

          <div class="col-sm-12 pad4">
            <div class="mar10"></div>

    				<button type="submit" class="text-left btn btn-primary btn-md">
              {{ Lang::get('aroaden.create') }}
    					<i class="fa fa-chevron-circle-right"></i>
    				</button>
          </div>

        </form>

      </fieldset>
    </div>

    <div class="col-sm-9">
      <p>
        {!! @trans('aroaden.invoices') !!}
      </p>

      <div class="mar4"></div>

      <div class="panel panel-default">

        <table class="table table-striped table-bordered table-hover">
          <tr class="fonsi14">
            <td class="wid180">Tratamiento</td>
            <td class="wid50 textcent">Cantidad</td>
            <td class="wid50"></td>
            <td class="wid70 textcent">Precio</td>
          </tr>
        </table>

        <div class="box400">
          <table class="table table-striped table-bordered table-hover">


        
          </table> 
        </div> 
      </div>
    </div>

  </div>

@endsection