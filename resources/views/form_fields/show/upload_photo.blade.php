<div class="row"> 
 <div class="col-sm-12"> 
  <div class="input-group">

    <form id="uploadProfilePhoto" enctype="multipart/form-data">
      {!! csrf_field() !!}

       <input type="hidden" name="id" value="{!! $id !!}">
       <input type="hidden" name="uploadProfilePhoto" value="1">

        <div class="btn-toolbar" role="toolbar">
           <div class="btn-group">
              <span class="input-group-btn pad10">  
                <p> {{ @trans('aroaden.upload_profile_photo') }} </p>
              </span>
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