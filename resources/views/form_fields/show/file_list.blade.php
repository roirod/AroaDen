
    <?php $inc = 1; ?>
  
    @foreach ($files as $file)

      <?php      
        $idfiles = $file->idfiles;
        $info = json_decode($file->info);

        $originalName = $info->originalName;
        $fsFilename = $info->fsFilename;
        $fsFilenameNoExt = $info->fsFilenameNoExt;        
        $extension = $info->extension;
        $thumb_file = $thumb_dir.'/'.$fsFilenameNoExt.'.'.$default_img_type;
      ?>

      <div class="col-sm-2 pad10 pad_top_bot text-center">

        @if ( in_array($extension, $img_extensions) )

          <div class="img_container">
            <a class="img_popup<?php echo $inc; ?>" href="{!! $user_dir.'/'.$fsFilename !!}">
              <img class="img_file_view" src="{!! $thumb_file !!}">
            </a>
            <script>
              $(document).ready(function() {
                $('a.img_popup<?php echo $inc; ?>').colorbox({
                  maxWidth: '99%',
                  maxHeight: '99%'
                });
              });
            </script>
          </div>

          <?php $inc++; ?>

        @else

          <i class="fa fa-file-o fa-3x"></i>

        @endif
             
  	    <p class="filena wrap fonsi12">
          {!! $originalName !!} 
  	    </p>

  	    <button type="button" class="btn btn-info btn-xs dropdown-toggle" data-toggle="dropdown">
  	      <i class="fa fa-list"></i> &nbsp;
  	      <span class="caret"></span> 
  	    </button> 
  	    <ul class="dropdown-menu" role="menu">
          <li>
            <a class="fonbla" href="{!! url("$main_route/$id").'/'.$idfiles.'/download' !!}"> 
              <i class="fa fa-download" aria-hidden="true"></i> {{ @trans('aroaden.download') }}
            </a>
          </li>

          <hr>
        	      
          <li>
            <form action="{!! url("$main_route/deleteFile/$idfiles") !!}" method="POST">  
              {!! csrf_field() !!}

              <input type="hidden" name="_method" value="DELETE">
              <input type="hidden" name="id" value="{!! $id !!}" />          

      	      <button type="submit" class="btn btn-sm btn-danger mar10 onDelete"> 
                <i class="fa fa-trash" aria-hidden="true"></i> {{ @trans('aroaden.delete') }}
              </button>
            </form> 
  	      </li>
  	    </ul>
    	</div>
     	
    @endforeach

    <script type="text/javascript">
      $(document).ready(function() {
        $('button.onDelete').on('click', function(evt) {
          evt.preventDefault();
          evt.stopPropagation();

          var _this = $(this);

          return onDelete(_this);
        });

        function onDelete(_this) {
          util.checkPermissions('{{ $main_route }}.deleteFile').done(function(response) {
            if (!response.permission)
              return util.showPopup("{{ Lang::get('aroaden.deny_access') }}", false, 2500);
          });
        }       
      });
    </script>