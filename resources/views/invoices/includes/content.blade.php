
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

    <table class="table table-striped table-bordered">
      <tr>
        <td class="wid140">{!! @trans("aroaden.treatment") !!}</td>
        <td class="wid60 textcent">{!! @trans("aroaden.date") !!}</td>
        <td class="wid60 textcent">{!! @trans("aroaden.units") !!}</td>
        <td class="wid70 textcent">{!! @trans("aroaden.price_no_tax") !!}</td>
        <td class="wid50 textcent">{!! @trans("aroaden.tax") !!}</td>
        <td class="wid70 textcent">{!! @trans("aroaden.amount") !!}</td>
      </tr>

      @foreach ($treatments as $treat)
          
        <tr> 
          <td class="wid140"> {!! $treat->name !!} </td>
          <td class="wid60 textcent"> {!! date ('d-m-Y', strtotime ($treat->day) ) !!}</td>
          <td class="wid60 textcent"> {!! $treat->units !!} </td>
          <td class="wid70 textcent"> {!! numformat($treat->price) !!} € </td>
          <td class="wid50 textcent"> {!! $treat->tax !!} % </td>

          @php
            $price = $treat->price + (($treat->price * $treat->tax) / 100);
            $total = $treat->units * $price;

            if (empty($total_amount))
              $total_amount = 0;

            $total_amount = $total_amount + $total;
          @endphp

          <td class="wid70 textcent"> {!! numformat($total) !!} € </td>
        </tr>
              
      @endforeach

      <tr>
        <td class="wid140">&nbsp;</td>
        <td class="wid60">&nbsp;</td>
        <td class="wid60">&nbsp;</td>        
        <td class="wid60">&nbsp;</td>
        <td class="wid60">&nbsp;</td>       
        <td class="wid70">&nbsp;</td>
      </tr>

      <tr>
        <td class="wid140"></td>
        <td class="wid60">&nbsp;</td>
        <td class="wid60">&nbsp;</td>
        <td class="wid60">&nbsp;</td>       
        <td class="wid60 textder">{!! @trans("aroaden.total_amount") !!}</td>
        <td class="wid70 textcent">{{ numformat($total_amount) }} € </td> 
      </tr>

    </table>

   <br>

  </div> 

  <div class="col-sm-12">

    @php
      $no_tax_msg = true;

      foreach ($treatments as $treat) {
        if ($treat->tax != 0) {
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
