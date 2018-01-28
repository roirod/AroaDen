<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">

  	<title>AroaDen</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/css/login.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/font-awe/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ asset('assets/img/favicon.ico') }}" rel="shortcut icon" >
</head>

<body> <br>

<div class="container pad20">
 <div class="row">
 
    @yield('content')

</div> </div>

  <script type="text/javascript" src="{{ asset('assets/js/jquery.min.js') }}"></script>
  <script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

</body>
</html>
