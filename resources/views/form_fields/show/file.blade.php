
<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group">

      <form action="{!! "/$main_route/upload" !!}" method="post" enctype="multipart/form-data">
        {!! csrf_field() !!}

        <input type="hidden" name="id" value="{!! $id !!}">

        <div class="btn-toolbar pad4" role="toolbar">
           <div class="btn-group">
              <span class="input-group-btn pad10">
                <p>{{ @trans('aroaden.files') }}</p>
              </span>
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
    <div id="fileList">

      @include('form_fields.show.file_list')

    </div>
  </div> 
</div>