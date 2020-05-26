<div class="row">
  <div class="col-sm-12">
     <ul class="nav nav-pills bgtra">
      <li>
        <a href="{{ url($routes['pays']) }}"> 
          <i class="fa fa-euro fa-1x"></i>
          {{ Lang::get('aroaden.payments') }}
        </a>
      </li>

      <li>
        <a href="{{ url($routes['invoices']) }}"> 
          <i class="fa fa-euro fa-1x"></i>
          {{ Lang::get('aroaden.invoices') }}
        </a>
      </li>
     </ul>
     <hr>
  </div>
</div>