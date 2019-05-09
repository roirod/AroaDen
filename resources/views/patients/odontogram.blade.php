@extends('layouts.main')

@section('content')

@include('includes.patients_nav')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12">

    <div class="col-sm-12 pad10">
      @include('form_fields.show.name')
    </div>

  	 <div class="input-group pad4"> 
  	 	
  		<div class="btn-toolbar pad4" role="toolbar">
        <div class="btn-group">
          <p class="pad4"> Odontograma &nbsp; </p>
        </div>
  			<div class="btn-group">
  				<a href="{!! url("/$main_route/$id/downloadOdontogram") !!}" class="btn btn-sm btn-primary" role="button"> 
         		<i class="fa fa-download" aria-hidden="true"></i> Descargar
          </a>
         </div>
         <div class="btn-group">
         	<form class="form" action="{!! url("/$main_route/resetOdontogram/$id") !!}" method="post">
              {!! csrf_field() !!}
           		<input type="hidden" name="_method" value="PUT">

           		<button type="button" class="btn btn-danger btn-sm dropdown-toggle onReset" data-toggle="dropdown">
           			Borrar Imagen <span class="caret"></span>
           		</button> 
           			<ul class="dropdown-menu" role="menu">
           				<li><button class="btn btn-danger btn-sm" type="submit"> Borrar</button></li>
           			</ul>
         	</form>
       	</div>
  
  </div> </div> </div> </div>
    
  <div class="row"> 
   <div class="col-sm-12"> 
    <div class="input-group">

      <form id="uploadOdontogram" enctype="multipart/form-data">
          {!! csrf_field() !!}

          <div class="btn-toolbar pad4" role="toolbar">
             <div class="btn-group">
                <span class="input-group-btn pad10">  <p> Subir </p> </span>
             </div>
             <div class="btn-group">
                <input type="file" class="btn btn-default btn-sm" name="file"/>
             </div>
            <div class="btn-group pad4"> 
              <button type="submit" class="btn btn-info btn-sm">&nbsp;<i class="fa fa-upload"></i>&nbsp;</button>
            </div>
        </div>
      </form>

      </div>
   </div> 
  </div> 

  <script>
    $(document).ready(function(){
      $("#uploadOdontogram").on('submit', function(event){
        event.stopPropagation();
        event.preventDefault();

        var formData = new FormData(this);
        $('input[type="file"]').val('');

        util.checkPermissions('patients.uploadOdontogram').done(function(response) {
          if (response.permission) {

            var obj = {
              data: formData,          
              url: '{!! "/$main_route/uploadOdontogram/$id" !!}',
              uploadFiles: true
            };       

            util.processAjaxReturnsJson(obj).done(function(response) {
              if (response.error)
                return util.showPopup(response.msg, false);

              $("#upodog_img img").remove();
              $("#upodog_img").append('<img src="' + response.odontogram + '" class="wPa" />').fadeIn(4000);
            });

          } else {

            return util.showPopup("{{ Lang::get('aroaden.deny_access') }}", false, 2500);

          }
        });
      });
    });
  </script>

  <div id="upodog_img" class="col-sm-12 pad10">
    {!! Html::image($odontogram,'', array('class' => 'wPa')) !!}
  </div>

@endsection