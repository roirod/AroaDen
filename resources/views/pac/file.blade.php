@extends('layouts.main')

@include('includes.other')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

<div class="row pad10">
  <form class="dropzone" id="dropzone" action="{!!url('/Pacientes/upload')!!}" method="post" enctype="multipart/form-data">
      {!! csrf_field() !!}

      <input type="hidden" name="idpac" value="{!!$idpac!!}">

      <input type="hidden" type="file" name="files" multiple />
        
  </form>
</div>


<div class="row visfile mar10 pad20">
  <div class="col-lg-12">
  
    @foreach ($files as $file)
  
        @continue(basename($file) == '.*' || basename($file) == '.fotoper.jpg')

          	<div class="col-sm-2 pad4 text-center">
          	  <i class="fa fa-file fa-2x text-center"></i> 
          	    <div class="filena wrap text-center">

                  {!!basename($file)!!} 

          	    </div>
          	    <button type="button" class="btn btn-info btn-md dropdown-toggle" data-toggle="dropdown">
          	      <i class="fa fa-list"></i> &nbsp;
          	      <span class="caret"></span> 
          	    </button> 
          	    <ul class="dropdown-menu" role="menu">
                   
                    <li>
                        <a href="{!!url("Pacientes/$idpac").'/'.basename($file).'/down'!!}"> 
                            <i class="fa fa-download" aria-hidden="true"></i> Descargar
                        </a>
                    </li>

                    <br>
                    <hr>
                  	      
                    <li>
                        <form action="{!!url('/Pacientes/filerem')!!}" method="post"> 
                            {!! csrf_field() !!}

                            <input type="hidden" name="filerem" value="{!!basename($file)!!}" />
                            <input type="hidden" name="idpac" value="{!!$idpac!!}" />          

              	    	      <button type="submit" class="btn btn-sm btn-danger"> 
                              <i class="fa fa-trash" aria-hidden="true"></i>  Eliminar
                            </button>
                        </form> 
            	      </li>
            	    
          	    </ul>
          	 </div>  
    	
    @endforeach

  </div>
</div>
	 
@endsection

@section('js')
    @parent

      <link href="{!! asset('assets/css/dropzone.css') !!}" rel="stylesheet" type="text/css" >

      <script type="text/javascript" src="{!! asset('assets/js/dropzone.js') !!}"></script>

@endsection