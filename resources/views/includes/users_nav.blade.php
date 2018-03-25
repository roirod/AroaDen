<div class="row">
  <div class="col-sm-12">
     <ul class="nav nav-pills bgtra">
         <li><a href="{{ url("/$other_route") }}">{{ Lang::get('aroaden.settings') }}</a></li>
         <li><a href="{{ url("/$main_route") }}">{{ Lang::get('aroaden.see') }}</a></li>
         <li><a href="{{ url("/$main_route/$user_edit") }}">{{ Lang::get('aroaden.edit') }}</a></li>
         <li><a href="{{ url("/$main_route/$user_delete") }}">{{ Lang::get('aroaden.delete') }}</a></li>
     </ul>
     <hr>
  </div>
</div>