
    @if (isset($idnav))

      <div class="row">
        <div class="col-sm-12">
          <ul class="nav nav-pills bgtra">
          	<li><a href="{{ url($routes['staff']."/$idnav") }}"> {{ Lang::get('aroaden.profile') }} </a></li>
          	<li><a href="{{ url($routes['staff']."/$idnav/file") }}"> {{ Lang::get('aroaden.files') }} </a></li>
          </ul>
          <hr>
        </div>
      </div>

    @endif