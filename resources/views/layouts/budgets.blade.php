<!DOCTYPE html>
<html>
<head>
  @section('head')
    <meta charset="utf-8">
    <title>{!! $app_name_text !!}</title>
    <link href="{!! asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" >
    <link href="{!! asset('assets/css/start.css') !!}" rel="stylesheet" type="text/css" >
    <link href="{!! asset('assets/font-awe/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css" >
    <link href="{!! asset('assets/img/favicon.ico') !!}" rel="shortcut icon" > 
  @show
</head>

<body class="bgwi">
  <div class="jumbotron container pad20">
    <div class="row mar30">
    
      @yield('content')

    </div>
 </div> 
  
  @section('js')
	  <script type="text/javascript" src="{!! asset('assets/js/jquery.min.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
  @show
       
</body>
</html>