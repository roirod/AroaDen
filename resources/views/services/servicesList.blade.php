
@if ($count == 0)

  <p>
    <span class="text-danger fonsi15">{{ @trans('aroaden.no_results') }}</span>
  </p>

@else

  <div>
    @if (isset($searched_text))
      <p class="label label-primary fonsi15">
        {{ Lang::get('aroaden.searched_text') }} {!! $searched_text !!}
      </p>
      &nbsp;
    @endif

    <p class="label label-success fonsi15">
      <span class="badge" id="countcurrentId">{!! $count !!}</span>
      {{ @trans('aroaden.services') }} 
    </p>
  </div>

  <br>

  <div class="panel panel-default">
    <table class="table table-striped table-bordered table-hover">
      <tr class="fonsi15">
        <td class="wid200">{{ @trans('aroaden.service') }}</td>
        <td class="wid70 textcent">{{ @trans('aroaden.tax') }}</td>
        <td class="wid110 textcent">{{ @trans('aroaden.price') }}</td>          
        <td class="wid70 textcent">{{ Lang::get('aroaden.edit') }}</td>
        <td class="wid70 textcent">{{ Lang::get('aroaden.delete') }}</td>
        <td class="wid70 textcent"></td>        
      </tr>
    </table>

    <div class="box300">
      <table class="table table-striped table-bordered table-hover">

        @foreach ($main_loop as $obj)

          <tr>
            <td class="wid200">{{ $obj->name }}</td>
            <td class="wid70 textcent">{{ $obj->tax }} %</td>             
            <td class="wid110 textcent">{{ $obj->price }} €</td>

            <td class="wid70 textcent">
              <a class="btn btn-sm btn-success editService" type="button" href="/{{ "$services_route/$obj->idser/edit" }}">
                <i class="fa fa-edit"></i>
              </a>
            </td>

            <td class="wid70">  
              <div class="btn-group">
                <form class="form" action="{!! url("/$services_route/$obj->idser") !!}" data-removeTr="true" data-count="true" method="POST">
                  <input type="hidden" name="_method" value="DELETE">

                  <button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-times"></i> <span class="caret"></span>  
                  </button>
                  <ul class="dropdown-menu" role="menu"> 
                    <li>
                      @include('includes.delete_button')
                    </li>
                  </ul>     
                </form>
              </div> 
            </td>

            <td class="wid70 textcent"></td>
          </tr>
          
        @endforeach

          <tr>
            <td class="wid200"></td>
            <td class="wid200"></td>
          </tr>
          <tr>
            <td class="wid200"></td>
            <td class="wid200"></td>
          </tr>

      </table>
    </div>

    <table class="table table-striped table-bordered table-hover">
      <tr class="fonsi15">
        <td class="wid200">{{ @trans('aroaden.service') }}</td>
        <td class="wid70 textcent">{{ @trans('aroaden.tax') }}</td>
        <td class="wid110 textcent">{{ @trans('aroaden.price') }}</td>          
        <td class="wid70 textcent">{{ Lang::get('aroaden.edit') }}</td>
        <td class="wid70 textcent">{{ Lang::get('aroaden.delete') }}</td>
        <td class="wid70 textcent"></td>        
      </tr>
    </table>

  </div>

@endif

<script type="text/javascript" src="{{ asset('assets/js/confirmDelete.js') }}"></script>

<script type="text/javascript">
  currentId = 'countcurrentId';

  $(document).ready(function() {
    $('a.editService').on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var _this = $(this);

      return onEdit(_this);
    });

    function onEdit(_this) {
      lastRoute = routes.services_route + '/ajaxIndex';
      var url_href = _this.attr('href');

      var obj = {      
        url  : url_href
      };

      return util.processAjaxReturnsHtml(obj);
    }
  });
</script>