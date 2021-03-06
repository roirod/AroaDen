
<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group">

      <form action="{!! "/$main_route/uploadFiles/$id" !!}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}

        <div class="btn-toolbar pad4" role="toolbar">
           <div class="btn-group">
              <span class="input-group-btn pad10">
                <p>{{ @trans('aroaden.files') }}</p>
              </span>
           </div>
           <div class="btn-group">
              <input type="file" class="btn btn-default btn-sm" name="files[]" multiple required />
           </div>
          <div class="btn-group"> 
            <button type="submit" class="btn btn-info btn-md">&nbsp;<i class="fa fa-upload"></i>&nbsp;</button>
          </div>
        </div>
      </form>

    </div>
  </div> 
</div> 

<div class="row visfile mar10 pad20">
  <div class="col-lg-12">
    <div id="fileList">

      @include('form_fields.show.file_list')

    </div>
  </div> 
</div>