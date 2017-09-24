
<div class="row"> 
 <div class="col-sm-12"> 
  <div class="input-group">

    <form role="form" action="{!!url("/$main_route/upload")!!}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}

         <input type="hidden" name="id" value="{!!$id!!}">

          <div class="btn-toolbar pad4" role="toolbar">
             <div class="btn-group">
                <span class="input-group-btn pad10">  <p> Archivos: </p> </span>
             </div>
             <div class="btn-group">
                <input type="file" class="btn btn-default btn-sm" name="files[]" multiple />
             </div>
            <div class="btn-group pad4"> 
              <button type="submit" class="btn btn-info btn-sm">&nbsp;<i class="fa fa-upload"></i>&nbsp;</button>
            </div>
        </div>
      
    </form>

    </div>
 </div> 
</div> 


<div class="row visfile mar10 pad20">
  <div class="col-lg-12">

    <?php $inc = 1; ?>
  
    @foreach ($files as $file)
  
        @continue(basename($file) == '.*' || basename($file) == $profile_photo_name)

        <?php
          $partes_ruta = pathinfo($file);

          if ( isset($partes_ruta['extension']) ) {

            $extension = $partes_ruta['extension'];
            $thumb_filename = $partes_ruta['filename'];
            $thumb_filename = $thumb_dir.'/'.$thumb_filename.'.jpg';

          } else {

            $extension = '';

          }
        ?>

        <div class="col-sm-2 pad10 pad_top_bot fonsi12 text-center">

        @if ($extension == 'jpg' || $extension == 'png' || $extension == 'jpeg' || $extension == 'gif')

            <div class="img_container">
              <a class="img_popup<?php echo $inc; ?>" href="{!! $user_dir.'/'.basename($file) !!}">
                <img class="img_file_view" src="{!! $thumb_filename !!}">
              </a>
                <script>
                  $(document).ready(function() {
                    jQuery('a.img_popup<?php echo $inc; ?>').colorbox({
                      maxWidth: '99%',
                      maxHeight: '99%'
                    });
                  });
                </script>
            </div>

            <?php $inc++; ?>

        @else

          	  <i class="fa fa-file-o fa-3x text-center"></i>

        @endif
             
          	    <div class="filena wrap text-center">

                  {!!basename($file)!!} 

          	    </div>
          	    <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
          	      <i class="fa fa-list"></i> &nbsp;
          	      <span class="caret"></span> 
          	    </button> 
          	    <ul class="dropdown-menu" role="menu">
                   
                    <li>
                        <a href="{!! url("$main_route/$id").'/'.basename($file).'/down' !!}"> 
                            <i class="fa fa-download" aria-hidden="true"></i> Descargar
                        </a>
                    </li>

                    <br>
                    <hr>
                  	      
                    <li>
                        <form action="{!! url("/$main_route/filerem") !!}" method="post"> 
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