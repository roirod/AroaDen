<div class="row">
  <div class="col-sm-12">
     <ul class="nav nav-pills bgtra">
		<li><a href="{{url("Pacientes/$idnav")}}"> {{ Lang::get('aroaden.profile') }} </a></li>
		<li><a href="{{url("Pacientes/$idnav/ficha")}}"> {{ Lang::get('aroaden.record') }} </a></li>
		<li><a href="{{url("Pacientes/$idnav/file")}}"> {{ Lang::get('aroaden.files') }} </a></li>
		<li><a href="{{url("Pacientes/$idnav/odogram")}}"> {{ Lang::get('aroaden.odontogram') }} </a></li>
		<li><a href="{{url("Presup/$idnav")}}"> {{ Lang::get('aroaden.budgets') }} </a></li>
		<li><a href="{{url("Factu/$idnav")}}"> {{ Lang::get('aroaden.invoices') }} </a></li>
     </ul>
  </div>
</div>

<hr>