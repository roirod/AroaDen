<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="{!! asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" >
  <link href="{!! asset('assets/css/main.css') !!}" rel="stylesheet" type="text/css" >

  <style>

    html {
      font-family: 'Helvetica' !important;
      margin: 16mm 16mm 16mm 16mm !important;
    }

    body {
      font-family: 'Helvetica' !important;
      font-size: 10px !important;      
    }

    p {
      font-family: 'Helvetica' !important;
      font-size: 10px !important;
    }

    table td {
      font-family: 'Helvetica' !important;
      font-size: 10px !important;
    }

  </style>

</head>

<body class="bgwi">

  @yield('content')


  <script type="text/javascript" src="{!! asset('assets/js/jquery.min.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
     
</body>
</html>