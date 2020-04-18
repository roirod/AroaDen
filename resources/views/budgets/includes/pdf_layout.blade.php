<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="{!! asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" >
  <link href="{!! asset('assets/css/main.css') !!}" rel="stylesheet" type="text/css" >
  <link href="{!! asset('assets/font-awe/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css" >
  <link href="{!! asset('assets/img/favicon.ico') !!}" rel="shortcut icon" >

  <style>



    html {
      margin: 0;
    }
    body {
      font-family: 'Helvetica' !important;
      margin: 11mm 11mm 11mm 11mm;
    }

  </style>

</head>

<body class="bgwi">
  <div class="jumbotron container">
    <div class="row">
    
      @yield('content')

    </div>
 </div> 

  <script type="text/javascript" src="{!! asset('assets/js/jquery.min.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
     
</body>
</html>