
@if ($count == 0)

  <p>
    <span class="text-danger">{{ @trans('aroaden.no_services_on_db') }}</span>
  </p>

@else

  <p>

  @if (isset($searched_text))
    <span class="label label-primary">{{ Lang::get('aroaden.searched_text') }} {!! $searched_text !!}</span>
  @endif
    <span class="label label-success"> {!! $count !!} {{ @trans('aroaden.services') }}</span>
  </p>

  <div class="panel panel-default">
    <table class="table">
       <tr class="fonsi15 success">
        <td class="wid290">{{ @trans('aroaden.service') }}</td>
        <td class="wid95 textcent">{{ @trans('aroaden.tax') }}</td>
        <td class="wid110 textcent">{{ @trans('aroaden.price') }}</td>          
        <td class="wid50"></td>
        <td class="wid50"></td>
        <td class="wid290"></td>
       </tr>
    </table>

    <div class="box300">
      <table class="table table-striped table-hover">

        @foreach ($main_loop as $obj)

         <tr>
            <td class="wid290">{{ $obj->name }}</td>
            <td class="wid95 textcent">{{ $obj->tax }} %</td>             
            <td class="wid110 textcent">{{ $obj->price }} €</td>

            <td class="wid50">
              <a class="btn btn-xs btn-success editService" type="button" href="/{{ "$services_route/$obj->idser/edit" }}">
                <i class="fa fa-edit"></i>
              </a>
            </td>
            
            <td class="wid50"> 
              <div class="btn-group"> 
                <form class="form" action="{!! url("/$services_route/$obj->idser") !!}" method="POST">    
                  {!! csrf_field() !!}

                  <input type="hidden" name="_method" value="DELETE">

                  <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-times"></i> <span class="caret"></span>  </button>
                  <ul class="dropdown-menu" role="menu"> 
                    <li>
                      @include('includes.delete_button')
                    </li>
                  </ul>     
                </form>
              </div>  
             </td>

            <td class="wid290"></td>
         </tr>
          
        @endforeach

      </table>
    </div>
  </div>

@endif

<script type="text/javascript" src="{{ asset('assets/js/confirmDelete.js') }}"></script>

<script type="text/javascript">
  $(document).ready(function() {
    $('a.editService').on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var _this = $(this);

      return onEdit(_this);
    });

    function onEdit(_this) {
      lastRoute = routes.services_route + '/ajaxIndex';

      var obj = {   
        url  : _this.attr('href')
      };

      return util.processAjaxReturnsHtml(obj);
    }
  });
</script>