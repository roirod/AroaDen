

<script id="templateHandlebars" type="text/x-handlebars-template">

  @{{#if error}}

    <p>
      <span class="text-danger">{{ @trans('aroaden.no_services_on_db') }}</span>
    </p>

  @{{else}}

    <p>
      <span class="label label-success"> @{{ count }} {{ @trans('aroaden.services') }}</span>
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

        @{{#each main_loop}}

         <tr>
            <td class="wid290">@{{ name }}</td>
            <td class="wid95 textcent">@{{ tax }} %</td>             
            <td class="wid110 textcent">@{{ price }} €</td>

            <td class="wid50">
              <a class="btn btn-xs btn-success" type="button" href="/@{{ ../main_route }}/@{{ idser }}/edit") }}">
                <i class="fa fa-edit"></i>
              </a>
            </td>
            
            <td class="wid50"> 
              <div class="btn-group"> 
                <form class="form" id="form" action="/@{{ ../main_route }}/@{{ idser }}" method="POST">    
                  {!! csrf_field() !!}

                <input type="hidden" name="_method" value="DELETE">

                <button type="button" class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">
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

            <td class="wid290"></td>
         </tr>
          
        @{{/each}}

      </table>
    </div> </div>

  @{{/if}}

</script>