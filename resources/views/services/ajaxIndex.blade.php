
  <div class="row"> 
    <div class="col-sm-12"> 
      <div class="input-group"> 
        <span class="input-group-btn pad10">  
          <p> {{ Lang::get('aroaden.service') }} </p> 
        </span>
        <div class="btn-toolbar pad4" role="toolbar"> 
          <div class="btn-group">
            <a href="{{ url($routes['services']."/create") }}" role="button" class="btn btn-sm btn-primary">
              <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
            </a>
          </div>
        </div> 
      </div> 
    </div> 
  </div>

  <div class="row"> 
    <div class="col-sm-12">

      <form class="form" id="searchService">     
        <div class="input-group">
          <span class="input-group-btn pad10"> 
            <p> {{ Lang::get('aroaden.search') }}</p> 
          </span>
          <div class="col-sm-3">
            <input type="search" name="string" id="string" class="form-control" placeholder="{{ Lang::get('aroaden.write_2_or_more') }}" autofocus>
          </div>
          <div class="col-sm-3">
            <a href="{{ url($routes['services']) }}" role="button" class="btn btn-md btn-danger">
              <i class="fa fa-trash"></i> {{ Lang::get('aroaden.remove_text') }}
            </a>
          </div>
        </div>
      </form>

    </div>
  </div>

  <div class="row">
    <div class="col-sm-7">
      <div id="item_list">

        @include('services.servicesList')

      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(document).ready(function() {
      $('a[href="{{ url($routes['services']."/create") }}"]').on('click', function(evt) {
        evt.preventDefault();
        evt.stopPropagation();

        var _this = $(this);

        return onCreate(_this);
      });

      function onCreate(_this) {
        lastRoute = routes.services + '/ajaxIndex';
        var url_href = _this.attr('href');

        var obj = {      
          url  : url_href
        };

        return util.processAjaxReturnsHtml(obj);
      }

      $("#string").on('keyup change', function(event) {
        event.preventDefault();
        event.stopPropagation();

        var string_val = $(this).val().trim();
        var string_val_length = string_val.length;

        if (string_val != '' && string_val_length > 1) {
          if ((event.which <= 90 && event.which >= 48) || event.which == 8 || event.which == 46 || event.which == 173) {
            var data = $("form#searchService").serialize();

            var obj = {
              data  : data,
              url  : '{!! $routes['services'] !!}/search',
              id  : 'item_list'
            };

            util.processAjaxReturnsHtml(obj);
          }
        }
      });
    });
  </script>