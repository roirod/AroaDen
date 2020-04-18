
<div class="row">
  <div class="col-sm-12">
    <p class="fonsi18">
      {!! $company->company_name !!}
    </p> 

    <p class="fonsi14">

      {!! $company->company_address !!}
      <br>

      {!! $company->company_city !!}
      <br> 

      <i class="fa fa-phone-square" aria-hidden="true"></i>
      {!! $company->company_tel1 !!}
      <br> 

      <i class="fa fa-envelope-square" aria-hidden="true"></i>
      {!! $company->company_email !!}
    
      <br><br>

      Presupuesto: {!! DatTime($created_at) !!} para {!! $patient->name.' '.$patient->surname.'('.$patient->dni.')' !!}
    </p>
    <br>
  </div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default fonsi14">
      <table class="table table-striped table-bordered table-hover">
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
    </div> 

   <br> <br>

</div> </div> 

<div class="row">
  <div class="col-sm-12">

   <p>
    {!! nl2br(e($text)) !!}

    <br> <br>

    {!! nl2br(e($company->budget_text)) !!}
   </p>

</div> </div> 

