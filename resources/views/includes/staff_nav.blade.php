<div class="row">
  <div class="col-sm-12">
     <ul class="nav nav-pills bgtra">

  		@if (isset($idnav))
  			<li><a href="{{ url($routes['staff']."/$idnav") }}"> {{ Lang::get('aroaden.profile') }} </a></li>
  			<li><a href="{{ url($routes['staff']."/$idnav/file") }}"> {{ Lang::get('aroaden.files') }} </a></li>
  		@endif

     </ul>
     <hr>
  </div>
</div>