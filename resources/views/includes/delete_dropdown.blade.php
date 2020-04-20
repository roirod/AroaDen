     
            <form class="form" action="{!! url("/$main_route/$id") !!}" data-redirect="true"> 
              <input type="hidden" name="_method" value="DELETE">

              <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-times"></i> {!! @trans("aroaden.delete") !!} <span class="caret"></span>  
              </button>
              <ul class="dropdown-menu" role="menu"> 
                <li>
                  @include('includes.delete_button')
                </li>
              </ul>
            </form>