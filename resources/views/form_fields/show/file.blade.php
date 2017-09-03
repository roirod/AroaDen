<div class="row pad10">
  <form role="form" action="{!!url("/$main_route/upload")!!}" method="post" enctype="multipart/form-data">
      {!! csrf_field() !!}

       <input type="hidden" name="id" value="{!!$id!!}">
  
      <div class="input-group">
        <span class="input-group-btn pad4"> 
          <p>&nbsp;&nbsp; Archivos: &nbsp;&nbsp;</p> 
        </span> 
        <span class="input-group-btn"> 
          <input type="file" class="btn btn-default" name="files[]" multiple />
        </span> 
        &nbsp;&nbsp;&nbsp;
        <span class="pad10"> 
          <button type="submit" class="btn btn-info">&nbsp;<i class="fa fa-upload"></i>&nbsp;</button>
        </span>
      </div>
  </form>
</div>


<div class="row visfile mar10 pad20">
  <div class="col-lg-12">
  
    @foreach ($files as $file)
  
        @continue(basename($file) == '.*' || basename($file) == $profile_photo_name)

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
                        <a href="{!!url("$main_route/$id").'/'.basename($file).'/down'!!}"> 
                            <i class="fa fa-download" aria-hidden="true"></i> Descargar
                        </a>
                    </li>

                    <br>
                    <hr>
                  	      
                    <li>
                        <form action="{!!url("/$main_route/filerem")!!}" method="post"> 
                            {!! csrf_field() !!}

                            <input type="hidden" name="filerem" value="{!!basename($file)!!}" />
                            <input type="hidden" name="id" value="{!!$id!!}" />          

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