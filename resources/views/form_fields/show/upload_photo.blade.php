<div class="row pad4">
  <form role="form" action="{!! url("/$main_route/upload") !!}" method="post" enctype="multipart/form-data">
  	  {!! csrf_field() !!}

       <input type="hidden" name="id" value="{!!$id!!}">
       <input type="hidden" name="profile_photo" value="1">
  
  	  <div class="input-group">
  	    <span class="input-group-btn pad4"> 
  	      <p>&nbsp;&nbsp; Subir foto perfil: &nbsp;&nbsp;</p> 
  	    </span> 
  	    <span class="input-group-btn"> 
  	      <input type="file" class="btn btn-default" name="files" />
  	    </span> 
  	    &nbsp;&nbsp;&nbsp;
  	    <span class="pad10"> 
  	      <button type="submit" class="btn btn-info btn-md"> &nbsp;<i class="fa fa-upload"></i> &nbsp;</button>
  	    </span>
  	  </div>
  </form>
</div>