
  @if(!$downloadPdf)

    <link href="{!! asset('assets/css/colorbox.css') !!}" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="{!! asset('assets/js/colorbox/jquery.colorbox-min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/colorbox/jquery.colorbox-es.js') !!}"></script>  

  @endif

  <div class="col-sm-12">
    <p class="fonsi14">
      {!! $company->company_name !!}
    </p>

    <p class="fonsi10">
      Telf: {!! $company->company_tel1 !!} &nbsp; &nbsp; Email: {!! $company->company_email !!}
    </p> 
  </div>

  <br>

  <div class="col-sm-12">
    <table class="table">
      <tr>
        <td>

          <p class="fonsi11">
            {!! @trans("aroaden.invoice") !!} nº: 
            <span class="pad2">
              @if($downloadPdf)

                00{{ $number }}

              @else
                
                00__

              @endif
            </span>

           &nbsp; &nbsp;

            {!! @trans("aroaden.serial") !!} : 
            <span class="pad2">
              {{ $serial }}
            </span>

            <br>

            {!! @trans("aroaden.type") !!}: 
            <span class="pad2">
              {{ @trans("aroaden.".$type) }}
            </span>

            <br>

            {!! @trans("aroaden.exp_date") !!} : 
            <span class="pad2">
              {{ $exp_date }}
            </span>

           &nbsp; &nbsp; 

            {!! @trans("aroaden.place") !!} : 
            <span class="pad2">
              {{ $place }}
            </span>
          </p>

        </td>
      </tr>
    </table>


    <table class="table">
      <tr>
        <td class="wid140">
          <p class="fonsi11">
            - {!! @trans("aroaden.company") !!}
          </p>

          <p>
            {!! @trans("aroaden.name") !!} : 
            <span>
              {!! $company->company_name !!}
            </span>
          </p>         

          <p>
            {!! @trans("aroaden.address") !!} : 
            <span>
              {!! $company->company_address !!}
            </span>
          </p>         

          <p>
            {!! @trans("aroaden.city") !!} : 
            <span>
              {!! $company->company_city !!}
            </span>
          </p>         
  
          <p>
            {!! @trans("aroaden.nif") !!} : 
            <span>
              {!! $company->company_nif !!}
            </span>
          </p>          
        </td>

        <td class="wid140">
          <p class="fonsi11">
            - {!! @trans("aroaden.patient") !!}
          </p>

          <p>
            {!! @trans("aroaden.name") !!} : 
            <span>
              {!! $object->name.' '.$object->surname !!}
            </span>
          </p>

          <p>
            {!! @trans("aroaden.address") !!} : 
            <span>
              {!! $object->address !!}
            </span>
          </p>

          <p>
            {!! @trans("aroaden.city") !!} : 
            <span>
              {!! $object->city !!}
            </span>
          </p>

          <p>
            {!! @trans("aroaden.dni") !!} : 
            <span>
              {!! $object->dni !!}
            </span>
          </p>
        </td>

      </tr>
    </table>

  </div>

  <div class="col-sm-12">

   @include('includes.tables.table_items')

   <br>

  </div> 

  <div class="col-sm-12">

    @php
      $no_tax_msg = true;

      foreach ($items as $item) {
        if ($item->tax != 0) {
          $no_tax_msg = false;
          break;
        }
      }
    @endphp

    @if($no_tax_msg)

      <p>
        {!! @trans("aroaden.no_tax_msg") !!}
      <p>

    @endif

    <p>
      {!! nl2br(e($notes)) !!}
    <p>

    </p>
      {!! nl2br(e($company->invoice_text)) !!}
    </p>

  </div>
