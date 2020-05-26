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

        <form class="form" action="{{ "/$main_route/invoicesFactory" }}" method="get">
        	<input type="hidden" name="idpat" value="{{ $idpat }}">
        	
          <label class="control-label text-left pad4">
            {{ @trans('aroaden.select_type') }}
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

          {!! csrf_field() !!}

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

    <div class="col-sm-8">
      <p>
        {!! @trans('aroaden.invoices') !!}
      </p>

      <div class="mar4"></div>

      <div class="panel panel-default">
        <table class="table table-striped table-bordered table-hover">
          <tr class="fonsi13">
            <td class="wid95 textcent">{!! @trans('aroaden.number') !!}</td>
            <td class="wid60 textcent">{!! @trans('aroaden.serial') !!}</td>            
            <td class="wid110 textcent">{!! @trans('aroaden.exp_date') !!}</td>
            <td class="wid110 textcent">{!! @trans('aroaden.type') !!}</td>
            <td class="wid50 textcent">{!! @trans('aroaden.edit') !!}</td>                        
            <td class="wid50 textcent">{!! @trans('aroaden.delete') !!}</td>            
            <td class="wid50 textcent">{!! @trans('aroaden.pdf') !!}</td>
          </tr>
        </table>

        <div class="box300">
          <table class="table table-striped table-bordered table-hover">

            @foreach ($items as $item)
                
              <tr> 
                <td class="wid95 textcent"> {!! $item->number !!} </td>
                <td class="wid60 textcent"> {!! $item->serial !!} </td>
                <td class="wid110 textcent"> {!! date('d-m-Y', strtotime($item->exp_date)) !!} </td>
                <td class="wid110 textcent"> {!! @trans("aroaden.".$item->type) !!} </td>

                <td class="wid50 textcent">
                  <a title="{!! @trans('aroaden.edit') !!}" href="{!! url("/$main_route/$item->number/edit") !!}" class="btn btn-success btn-sm">
                    <i class="fa fa-edit"></i>
                  </a>
                </td>

                <td class="wid50 textcent">  
                  <div class="btn-group">
                    <form class="form" action="{!! url("/$main_route/$item->number") !!}" data-removeTr="true" method="POST">
                      <input type="hidden" name="_method" value="DELETE">

                      <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-times"></i> <span class="caret"></span>  
                      </button>
                      <ul class="dropdown-menu" role="menu"> 
                        <li>
                          @include('includes.delete_button')
                        </li>
                      </ul>     
                    </form>
                  </div> 
                </td>

                <td class="wid50 textcent">
                  <a title="{!! @trans('aroaden.download_pdf') !!}" href="{!! url("/$main_route/downloadPdf/$item->number") !!}" class="btn btn-info btn-sm">
                    <i class="fa fa-download"></i>
                  </a>
                </td>

              </tr>
                    
            @endforeach

          </table> 
        </div> 
      </div>
    </div>

  </div>

  <script type="text/javascript" src="{{ asset('assets/js/confirmDelete.js') }}"></script>

@endsection