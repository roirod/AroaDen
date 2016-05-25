@if( $request->session()->has('sucmess') )

  <div class="row">	<div class="col-sm-7">
    <div class="alert alert-success">    
	    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	      <p class="lead pad4"> {{ $request->session()->get('sucmess') }} </p>
	 </div> 
  </div> </div>

@elseif( $request->session()->has('errmess') )

  <div class="row">	<div class="col-sm-7">
    <div class="alert alert-danger">    
	    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	      <p class="lead pad10"> {{ $request->session()->get('errmess') }} </p>  
	 </div>
  </div> </div>

@endif