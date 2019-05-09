<div class="row">
  <div class="col-sm-12">
     <ul class="nav nav-pills bgtra">
		<li><a href="{{ url("$staff_route") }}">{{ Lang::get('aroaden.staff') }}</a></li>
		<li><a href="{{ url("$staff_positions_route") }}">{{ Lang::get('aroaden.positions') }}</a></li>

		@if (isset($idnav))
			<li><a href="{{ url("$staff_route/$idnav") }}"> {{ Lang::get('aroaden.profile') }} </a></li>
			<li><a href="{{ url("$staff_route/$idnav/file") }}"> {{ Lang::get('aroaden.files') }} </a></li>
		@endif

     </ul>
     <hr>
  </div>
</div>