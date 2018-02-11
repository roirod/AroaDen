    @include('includes.messages')
    @include('includes.errors')

    <meta name="_token" content="{!! csrf_token() !!}"/>

    <div class="row"> 
      <div class="col-sm-12"> 
        <div class="input-group"> 
          <span class="input-group-btn pad10">  <p> {{ Lang::get('aroaden.service') }} </p> </span>
          <div class="btn-toolbar pad4" role="toolbar"> 
            <div class="btn-group">
              <a href="{{ url("/$main_route/create") }}" role="button" class="btn btn-sm btn-primary">
                <i class="fa fa-plus"></i> {{ Lang::get('aroaden.new') }}
              </a>
            </div>  
    </div> </div> </div> </div>

    <div class="row">
      <form class="form" id="string_form">
        {!! csrf_field() !!}   
        
        <div class="input-group">
          <span class="input-group-btn pad10"> <p> &nbsp; {{ Lang::get('aroaden.search_service') }}</p> </span>
          <div class="col-sm-4">
            <input type="search" name="string" id="string" class="form-control" placeholder="{{ Lang::get('aroaden.write_2_or_more') }}" autofocus required>
          </div>
          <div class="col-sm-3">
            <a href="{{ url("/$main_route") }}" role="button" class="btn btn-md btn-danger">
              <i class="fa fa-trash"></i> {{ Lang::get('aroaden.remove_text') }}
            </a>
          </div>
        </div>
      </form>
    </div>

    <div class="row">
      <div class="col-sm-12">
        <div id="item_list">

        @if ($count == 0)

          <p>
            <span class="text-danger">{{ @trans('aroaden.no_services_on_db') }}</span>
          </p>

        @else

          <p>
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
                    <a class="btn btn-xs btn-success editService" type="button" href="/{{ "$main_route/$obj->idser/edit" }}">
                      <i class="fa fa-edit"></i>
                    </a>
                  </td>
                  
                  <td class="wid50"> 
                    <div class="btn-group"> 
                      <form class="form" action="{!! url("/$main_route/$obj->idser") !!}" method="POST">    
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
          </div> </div>

          @endif

        </div>
      </div>
    </div>

    <script id="templateHandlebars" type="text/x-handlebars-template">
      @include('services.hbsPartial')
    </script>

    @include('services.jsInclude')