<div class="row"> 
 <div class="col-sm-12"> 
  <div class="input-group">

    <form role="form" action="{!! url("/$main_route/upload") !!}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}

       <input type="hidden" name="id" value="{!! $id !!}">
       <input type="hidden" name="profile_photo" value="1">

          <div class="btn-toolbar" role="toolbar">
             <div class="btn-group">
                <span class="input-group-btn pad10">  <p> Subir foto perfil </p> </span>
             </div>
             <div class="btn-group">
                <input type="file" class="btn btn-default btn-sm" name="files"/>
             </div>
            <div class="btn-group pad4"> 
              <button type="submit" class="btn btn-info btn-sm">&nbsp;<i class="fa fa-upload"></i>&nbsp;</button>
            </div>
        </div>
      
    </form>

    </div>
 </div> 
</div> 