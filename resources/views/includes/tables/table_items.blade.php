
    <table class="table table-striped table-bordered">
      <tr>
        <td class="wid140">{!! @trans("aroaden.treatment") !!}</td>

        @if (isset($has_date))
        <td class="wid60 textcent">{!! @trans("aroaden.date") !!}</td>
        @endif

        <td class="wid60 textcent">{!! @trans("aroaden.units") !!}</td>
        <td class="wid70 textcent">{!! @trans("aroaden.price_no_tax") !!}</td>
        <td class="wid50 textcent">{!! @trans("aroaden.tax") !!}</td>
        <td class="wid70 textcent">{!! @trans("aroaden.amount") !!}</td>
      </tr>

      @foreach ($items as $item)
          
        <tr> 
          <td class="wid140"> {!! $item->name !!} </td>

          @if (isset($has_date))
          <td class="wid60 textcent"> {!! date ('d-m-Y', strtotime ($item->day) ) !!}</td>
          @endif

          <td class="wid60 textcent"> {!! $item->units !!} </td>
          <td class="wid70 textcent"> {!! numformat($item->price) !!} {{ $_SESSION["Alocale"]["currency_symbol"] }} </td>
          <td class="wid50 textcent"> {!! $item->tax !!} % </td>

          @php
            $price = calcTotal($item->price, $item->tax, false);
            $total = $item->units * $price;

            if (empty($total_amount))
              $total_amount = 0;

            $total_amount = $total_amount + $total;
          @endphp

          <td class="wid70 textcent"> {!! numformat($total) !!} {{ $_SESSION["Alocale"]["currency_symbol"] }} </td>
        </tr>
              
      @endforeach

      <tr>
        <td class="wid140">&nbsp;</td>

        @if (isset($has_date))
        <td class="wid60">&nbsp;</td>
        @endif

        <td class="wid60">&nbsp;</td>        
        <td class="wid60">&nbsp;</td>
        <td class="wid60">&nbsp;</td>       
        <td class="wid70">&nbsp;</td>
      </tr>

      <tr>
        <td class="wid140"></td>

        @if (isset($has_date))
        <td class="wid60">&nbsp;</td>
        @endif

        <td class="wid60">&nbsp;</td>
        <td class="wid60">&nbsp;</td>       
        <td class="wid60 textder">{!! @trans("aroaden.total_amount") !!}</td>
        <td class="wid70 textcent">{{ numformat($total_amount) }} {{ $_SESSION["Alocale"]["currency_symbol"] }} </td> 
      </tr>

    </table>
