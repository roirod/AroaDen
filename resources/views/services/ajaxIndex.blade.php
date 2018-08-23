
<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  <p> {{ Lang::get('aroaden.service') }} </p> </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{ url("/$services_route/create") }}" role="button" class="btn btn-sm btn-primary">
            <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
          </a>
        </div>
</div> </div> </div> </div>

<div class="row">
  <form class="form" id="searchService">
    {!! csrf_field() !!}   
    
    <div class="input-group">
      <span class="input-group-btn pad10"> <p> &nbsp; {{ Lang::get('aroaden.search_service') }}</p> </span>
      <div class="col-sm-4">
        <input type="search" name="string" id="string" class="form-control" placeholder="{{ Lang::get('aroaden.write_2_or_more') }}" autofocus required>
      </div>
      <div class="col-sm-3">
        <a href="{{ url("/$services_route") }}" role="button" class="btn btn-md btn-danger">
          <i class="fa fa-trash"></i> {{ Lang::get('aroaden.remove_text') }}
        </a>
      </div>
    </div>
  </form>
</div>

<div class="row">
  <div class="col-sm-12">
    <div id="item_list">

      @include('services.servicesList')

    </div>
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('a[href="{{ url("/$services_route/create") }}"]').on('click', function(evt) {
      evt.preventDefault();
      evt.stopPropagation();

      var _this = $(this);

      return onCreate(_this);
    });

    function onCreate(_this) {
      lastRoute = routes.services_route + '/ajaxIndex';

      var obj = {   
        url  : _this.attr('href')
      };

      return util.processAjaxReturnsHtml(obj);
    }

    $("#string").on('keyup change', function(event) {
      event.preventDefault();
      event.stopPropagation();

      var string_val = $(this).val();
      var string_val_length = string_val.length;

      if (string_val != '' && string_val_length > 1) {
        if ((event.which <= 90 && event.which >= 48) || event.which == 8 || event.which == 46 || event.which == 173) {
          var data = $("form#searchService").serialize();

          var obj = {
            data  : data,
            url  : '{!! $services_route !!}/search',
            id  : 'item_list'
          };

          util.processAjaxReturnsHtml(obj);
        }
      }
    });
  });
</script>