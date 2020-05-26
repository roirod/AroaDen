
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

   @include('includes.tables.table_items')

   <br>

</div> </div> 

<div class="row">
  <div class="col-sm-12">

   <p>
    {!! nl2br(e($text)) !!}
   </p>

   <p>
    {!! nl2br(e($company->budget_text)) !!}
   </p>

</div> </div> 

