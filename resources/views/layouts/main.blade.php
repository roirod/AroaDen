<!DOCTYPE html>
<html>
<head>
  @section('head')

    <meta charset="UTF-8"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page_title }}</title>

    <link href="{!! asset('assets/img/favicon.ico') !!}" rel="shortcut icon">
    <link href="{!! asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('assets/css/main.css') !!}" rel="stylesheet" type="text/css">
    <link href="{!! asset('assets/font-awe/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/css/sweetalert2.min.css') !!}">
    <link rel="stylesheet" type="text/css" href="{!! asset('assets/css/animate.min.css') !!}">

  @show
  
  @section('js')

    <script type="text/javascript" src="{!! asset('assets/js/jquery.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('assets/js/sweetalert2.min.js') !!}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/axios.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/vue.js') }}"></script>

    @include('includes.util')

    @if ($load_js["datatables"])
      @include('includes.js.datatables')
    @endif

    @if ($load_js["datetimepicker"])
      @include('includes.js.datetimepicker')
    @endif

    <script type="text/javascript">
      $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
      });

      $(document).ready(function(){
        $('[data-toggle="menuTooltip"]').tooltip({ 
          container: 'body',
          placement: 'right',
          html: 'true'
        });
      });
    </script>

  @show

</head>

<body> 

  <div class="mar4"></div>

  <div class="jumbotron container bgwi pad10 mainContainer">
    <div class="row">  

      <div class="col-sm-1 widCol1 textcent">
        <div class="affix">
        
          <div class="mar20"></div>

          <nav class="navbar navbar-default" role="navigation">   
            <ul class="nav nav-pills nav-stacked"> 
              <li data-toggle="menuTooltip" title="<b>{!! @trans("aroaden.accounting") !!}</b>">
                <a href="{!! url($routes['accounting'])!!}"><i class="fa fa-pie-chart fa-menusize"></i></a>
              </li>
              <li data-toggle="menuTooltip" title="<b>{!! @trans("aroaden.services") !!}</b>">
                <a href="{!! url($routes['services'])!!}"><i class="fa fa-tasks fa-menusize"></i></a>
              </li>
              <li data-toggle="menuTooltip" title="<b>{!! @trans("aroaden.appointments") !!}</b>">
                <a href="{!! url($routes['appointments'])!!}"><i class="fa fa-calendar fa-menusize"></i></a>
              </li>
              <li data-toggle="menuTooltip" title="<b>{!! @trans("aroaden.patients") !!}</b>">
                <a href="{!! url($routes['patients'])!!}"><i class="fa fa-users fa-menusize"></i></a>
              </li>
              <li data-toggle="menuTooltip" title="<b>{!! @trans("aroaden.staff") !!}</b>">
                <a href="{!! url($routes['staff'])!!}"><i class="fa fa-user-md fa-menusize"></i></a>
              </li>
              <li data-toggle="menuTooltip" title="<b>{!! @trans("aroaden.staff_positions") !!}</b>">
                <a href="{!! url($routes['staff_positions'])!!}"><i class="fa fa-stethoscope fa-menusize"></i></a>
              </li>
            </ul>
          </nav>

        </div>  
      </div>

      <div class="col-sm-11 minHeight widCol2">
        <div class="row">
          <div class="col-sm-10 pad4">
            &nbsp; &nbsp;
            <span class="label label-primary fonsi13"> 
              <i class="fa fa-user fa-1x"></i> {!! Auth::user()->username !!} 
            </span>
          </div>
   	
          <div class="col-sm-2 text-right">
            <a class="btn btn-default btn-sm" href="{!! url($routes['settings']) !!}" title="{!! @trans("aroaden.settings") !!}">
              <i class="fa fa-cogs"></i>
            </a>
            <a href="https://www.youtube.com/channel/UCegtqSZJWwyppeSVovo6RxQ" target="_blank" class="btn btn-sm btn-info" title="{!! @trans("aroaden.manuals") !!}">
              <i class="fa fa-question"></i>
            </a>
            <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown" title="{!! @trans("aroaden.logout") !!}">
              <span class="caret"></span> &nbsp; <i class="fa fa-close"></i>
            </button> 
            <ul class="dropdown-menu" role="menu">
               <form action="{!! url("/logout") !!}" method="post">
                {!! csrf_field() !!}
                <li class="text-right">
                   <input type="submit" class="btn btn-default btn-lg" value="{!! @trans("aroaden.logout") !!}">
                </li>
              </form>
            </ul>
          </div>
        </div>

        <hr>
        
        <div id="main_content">
        
          @yield('content')

        </div>

      </div>
    </div> 
  </div>
    
  @yield('footer_script')
       
</body>
</html>