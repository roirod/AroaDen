
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