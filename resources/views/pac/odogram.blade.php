@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

<div class="row mar10">
  <div class="col-sm-12">
  	 <div class="input-group"> 
  	 	<span class="input-group-btn pad4"> Odontograma: &nbsp; </span>
  		<div class="btn-toolbar pad4" role="toolbar">
  			<div class="btn-group">
  				<a href="url("/Pacientes/$idpac/downodog")!!}" class="btn btn-sm btn-primary" role="button"> 
         		<i class="fa fa-download" aria-hidden="true"></i> Descargar
            </a>
         </div>
         <div class="btn-group">
         	<form role="form" class="form" action="{!!url("/Pacientes/resodog")!!}" method="post">
              {!! csrf_field() !!}

           		<input type="hidden" name="idpac" value="{!!$idpac!!}">
           		<input type="hidden" name="resodog" value="1">

           		<button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
           			Borrar Imagen <span class="caret"></span>
           		</button> 
           			<ul class="dropdown-menu" role="menu">
           				<li><button name="nueva" value="nueva" type="submit"> Borrar</button></li>
           			</ul>
         	</form>
       	</div>
  
  </div> </div> </div> </div>

<div class="row pad10">
  <form role="form" action="{!!url('/Pacientes/upodog')!!}" method="post" enctype="multipart/form-data">
  	  {!! csrf_field() !!}

       <input type="hidden" name="idpac" value="{!!$idpac!!}">
  
  	  <div class="input-group">
  	    <span class="input-group-btn pad4"> 
  	      <p>&nbsp;&nbsp;&nbsp; Subir: &nbsp;&nbsp;&nbsp;</p> 
  	    </span> 
  	    <span class="input-group-btn"> 
  	      <input type="file" class="btn btn-default" name="upodog"/>
  	    </span> 
  	    &nbsp;&nbsp;&nbsp;
  	    <span class="pad10"> 
  	      <button type="submit" class="btn btn-info">&nbsp;<i class="fa fa-upload"></i>&nbsp;</button>
  	    </span>
  	  </div>
  </form>
</div>
  
  <div class="col-sm-12 pad10">
    {!! Html::image($odogram,'a pic', array('class' => 'wPa')) !!}
  </div> 

@endsection