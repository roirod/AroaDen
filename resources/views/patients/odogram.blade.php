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
  				<a href="{!! url("/$main_route/$id/downodog") !!}" class="btn btn-sm btn-primary" role="button"> 
         		<i class="fa fa-download" aria-hidden="true"></i> Descargar
            </a>
         </div>
         <div class="btn-group">
         	<form role="form" class="form" action="{!! url("/$main_route/resodog") !!}" method="post">
              {!! csrf_field() !!}

           		<input type="hidden" name="id" value="{!! $id !!}">

           		<button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
           			Borrar Imagen <span class="caret"></span>
           		</button> 
           			<ul class="dropdown-menu" role="menu">
           				<li><button name="nueva" value="nueva" type="submit"> Borrar</button></li>
           			</ul>
         	</form>
       	</div>
  
  </div> </div> </div> </div>
    
  <div class="row"> 
   <div class="col-sm-12"> 
    <div class="input-group">

      <form id="upodog" enctype="multipart/form-data">
          {!! csrf_field() !!}

           <input type="hidden" name="id" value="{!!$id!!}">

            <div class="btn-toolbar pad4" role="toolbar">
               <div class="btn-group">
                  <span class="input-group-btn pad10">  <p> Subir </p> </span>
               </div>
               <div class="btn-group">
                  <input type="file" class="btn btn-default btn-sm" name="upodog"/>
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
      $("#upodog").on('submit', function(event){
        event.stopPropagation();
        event.preventDefault();

        var formData = new FormData(this);

        $('input[type="file"]').val('');
    
        var obj = {
          data  : formData,          
          url  : '{!! "/$main_route/upodog" !!}'
        };

        util.processAjaxReturnsJson(obj).done(function(response) {
          if (response.error)
            return util.showPopup(response.msg, false);

          $("#upodog_img img").remove();
          $("#upodog_img").append('<img src="' + response.odogram + '" class="wPa" />').fadeIn(4000);
        });
      });
    });
  </script>

  <div id="upodog_img" class="col-sm-12 pad10">
    {!! Html::image($odogram,'', array('class' => 'wPa')) !!}
  </div>

@endsection