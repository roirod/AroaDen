<div class="row">
  <div class="col-sm-12">
     <ul class="nav nav-pills bgtra">
		<li><a href="{{ url("$patients_route/$idnav") }}"> {{ Lang::get('aroaden.profile') }} </a></li>
		<li><a href="{{ url("$patients_route/$idnav/record") }}"> {{ Lang::get('aroaden.record') }} </a></li>
		<li><a href="{{ url("$patients_route/$idnav/file") }}"> {{ Lang::get('aroaden.files') }} </a></li>
		<li><a href="{{ url("$patients_route/$idnav/odontogram") }}"> {{ Lang::get('aroaden.odontogram') }} </a></li>
		<li><a href="{{ url("$budgets_route/$idnav") }}"> {{ Lang::get('aroaden.budgets') }} </a></li>
		<li><a href="{{ url("$invoices_route/$idnav") }}"> {{ Lang::get('aroaden.invoices') }} </a></li>
     </ul>
  </div>
</div>

<hr>