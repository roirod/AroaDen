<div class="row">
  <div class="col-sm-12">
    <ul class="nav nav-pills bgtra">
      <li>
        <a href="{{ url($routes['settings']) }}">
          {{ Lang::get('aroaden.settings') }}
        </a>
      </li>

      <li>
        <a class="company_route" href="{{ url($routes['company']) }}">
          {{ @trans("aroaden.data") }}
        </a>
      </li>
    </ul>
    <hr>
  </div>
</div>