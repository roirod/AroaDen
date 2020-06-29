<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <link href="{!! asset('assets/css/bootstrap.min.css') !!}" rel="stylesheet" type="text/css" >
  <link href="{!! asset('assets/css/main.css') !!}" rel="stylesheet" type="text/css" >

  <style>

  @if($downloadPdf)

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

  @endif

  </style>

  <script type="text/javascript" src="{!! asset('assets/js/jquery.min.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>

</head>

<body class="bgwi">

  @if($downloadPdf)

  <div class="row">

  @else

  <div class="row pad30">

  @endif


    @yield('content')


  </div> 

</body>
</html>