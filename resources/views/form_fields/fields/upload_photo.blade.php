 
  <div class="row"> 
   <div class="col-sm-12"> 
    <div class="input-group">

      <form id="uploadProfilePhoto" enctype="multipart/form-data">
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
            <div class="btn-group"> 
              <button type="submit" class="btn btn-info btn-md" title="{{ @trans('aroaden.upload_profile_photo') }}">
                &nbsp;<i class="fa fa-upload"></i>&nbsp;
              </button>
            </div>
        </div>
      </form>

    </div>
   </div> 
  </div>

  <script>
    $(document).ready(function(){
      $("#uploadProfilePhoto").on('submit', function(event){
        event.stopPropagation();
        event.preventDefault();

        var file_val = $('input[name="files"]').prop("files");     

        if (file_val.length == 0)
          return util.showPopup('{!! @trans("aroaden.no_files_for_upload") !!}', false, 4000);

        var formData = new FormData(this);
    
        $('input[type="file"]').val('');

        var obj = {
          data: formData,          
          url: '{!! url("/$main_route/uploadProfilePhoto/$id") !!}',
          uploadFiles: true
        };

        util.processAjaxReturnsJson(obj).done(function(response) {
          if (response.error)
            return util.showPopup(response.msg, false);

          $("#profile_photo div img").remove();
          $("#profile_photo div").append('<img src="' + response.profile_photo + '" class="max150 pad4" />').fadeIn(4000);
        });
      });
    });
  </script>
