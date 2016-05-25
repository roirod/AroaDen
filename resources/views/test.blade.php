<!DOCTYPE html>
<html lang="es">
<head>
  @section('head')
    <meta charset="utf-8">
    <title>AroaDen</title>
    <link href="{{ URL::asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" >
    <link href="{{ URL::asset('assets/css/Start.css') }}" rel="stylesheet" type="text/css" >
  @show
</head>

<body> <br>

<div class="jumbotron container bgwi pad20">
 <div class="row">
 
 
 @section('content')
 
	<div class="container">
	    <div class="row">
	        <div class="col-md-10 col-md-offset-1 fonsi18">
	        
	        		<p class="lead"> session vars </p>
	        
	        		@foreach ($request->session()->all() as $key => $value)
	        		
	        			@if (is_array($key) )
	        				
	        				@foreach ($key as $key => $value)
	        				
		        				<div class="col-md-10 col-md-offset-1">
		        						{{ $key }} -- {{ $value }}
		        						<br>
		        				</div>
		        				
	        				@endforeach
	        				
	        			@else
		        			
		        			<div class="col-md-10 col-md-offset-1">
			        			{{ $key }} -- {{ $value }}
			        			<br>
			        		</div>
	        		       			
	        			@endif
	        				
					@endforeach
		
	        </div>
	    </div>
	</div>
	
	
	
<hr>
<br><br>
<hr>
<br><br>
{{ dd($request) }}
<hr>
<br><br>
<hr>
<br><br>	
	
	
@endsection
  
  
 </div> </div>
  
  @section('js')
	  <script type="text/javascript" src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
	  <script type="text/javascript" src="{{ URL::asset('assets/js/bootstrap.min.js') }}"></script>
  @show 
     
</body>
</html>