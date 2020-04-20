
<div class="row">
  <div class="col-sm-12">

    <p class="fonsi14">
      {!! $company->company_name !!}
    </p>

    <br>

    <table class="table">
      <tr>
        <td class="wid180">
          {!! $company->company_address !!}
          <br>

          {!! $company->company_city !!}
          <br> 

          Telf: {!! $company->company_tel1 !!}
          <br> 

          Email: {!! $company->company_email !!}
        </td>

        <td class="wid180">
          Presupuesto: {!! DatTime($created_at) !!}
          <br>
          {!! $patient->name.' '.$patient->surname !!}
          <br>
          {!! $patient->dni !!}
        </td>

      </tr>
    </table>

  </div>
</div>

<div class="row">
  <div class="col-sm-12">

    <table class="table table-striped table-bordered">
      <tr>
        <td class="wid180">Tratamiento</td>
        <td class="wid70 textcent">IVA</td>
        <td class="wid70 textcent">Cantidad</td>
        <td class="wid70 textcent">Precio</td>      
      </tr>

      @foreach ($budgets as $bud)
          
        <tr> 
          <td class="wid180"> {!! $bud->name !!} </td>
          <td class="wid70 textcent"> {!! $bud->tax !!} % </td>
          <td class="wid70 textcent"> {!! $bud->units !!} </td>
          <td class="wid70 textcent"> {!! numformat($bud->price) !!} € </td>
        </tr>
              
      @endforeach

      <tr>
        <td class="wid180">&nbsp;</td>
        <td class="wid70">&nbsp;</td>
        <td class="wid70">&nbsp;</td>
        <td class="wid70">&nbsp;</td>
      </tr>
      <tr>
        <td class="wid180"></td>
        <td class="wid70">&nbsp;</td>
        <td class="wid70 textder">Total IVA</td>
        <td class="wid70 textcent"> {!! numformat($taxtotal) !!} € </td> 
      </tr>
      <tr>
        <td class="wid180"></td>
        <td class="wid70">&nbsp;</td>
        <td class="wid70 textder">Total sin IVA</td>
        <td class="wid70 textcent"> {!! numformat($notaxtotal) !!} € </td> 
      </tr>
      <tr>
        <td class="wid180"></td>
        <td class="wid70">&nbsp;</td>
        <td class="wid70 textder">Total</td>
        <td class="wid70 textcent"> {!! numformat($total) !!} € </td> 
      </tr>
    </table>

   <br>

</div> </div> 

<div class="row">
  <div class="col-sm-12">

   <p>
    {!! nl2br(e($text)) !!}

    <br>

    {!! nl2br(e($company->budget_text)) !!}
   </p>

</div> </div> 

