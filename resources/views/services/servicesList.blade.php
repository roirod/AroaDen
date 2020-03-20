
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
      {{ @trans('aroaden.services') }} <span class="badge"> {!! $count !!} </span>
    </p>
  </div>

  <br>

  <div class="panel panel-default">
    <table class="table">
       <tr class="fonsi15 success">
        <td class="wid200">{{ @trans('aroaden.service') }}</td>
        <td class="wid95 textcent">{{ @trans('aroaden.tax') }}</td>
        <td class="wid110 textcent">{{ @trans('aroaden.price') }}</td>          
        <td class="wid95 textcent">{{ Lang::get('aroaden.edit') }}</td>
       </tr>
    </table>

    <div class="box300">
      <table class="table table-striped table-hover">

        @foreach ($main_loop as $obj)

         <tr>
            <td class="wid200">{{ $obj->name }}</td>
            <td class="wid95 textcent">{{ $obj->tax }} %</td>             
            <td class="wid110 textcent">{{ $obj->price }} €</td>

            <td class="wid95 textcent">
              <a class="btn btn-sm btn-success editService" type="button" href="/{{ "$services_route/$obj->idser/edit" }}">
                <i class="fa fa-edit"></i>
              </a>
            </td>   
         </tr>
          
        @endforeach

      </table>
    </div>
  </div>

@endif

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
      var url_href = _this.attr('href');

      util.checkPermissions('services.edit').done(function(response) {
        if (response.permission) {
          var obj = {      
            url  : url_href
          };

          return util.processAjaxReturnsHtml(obj);

        } else {

          return util.showPopup("{{ Lang::get('aroaden.deny_access') }}", false, 2500);

        }
      });
    }
  });
</script>