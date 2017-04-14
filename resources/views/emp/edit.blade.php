@extends('layouts.main')

@section('content')

@include('includes.empnav')

@include('includes.messages')
@include('includes.errors')


{!! addtexto("Editar Datos") !!}

<div class="row">
 <div class="col-sm-12">
  
  	<form class="form" id="form" role="form" action="{!!url("/Empresa/saveData")!!}" method="post">
		{!! csrf_field() !!}
		
		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Nombre:</label>
		<input type="text" class="form-control" name="empre_nom" value="{!!$empre->empre_nom!!}" maxlength="111" autofocus required >  </div>
				
		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Poblaci&#xF3;n:</label>
		<input type="text" class="form-control" name="empre_pobla" value="{!!$empre->empre_pobla!!}" maxlength="111" >  </div>  
				  
		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Direcci&#xF3;n:</label>
		<input type="text" class="form-control" name="empre_direc" value="{!!$empre->empre_direc!!}" maxlength="111" >  	</div>
				
		<div class="form-group col-sm-2">  <label class="control-label text-left mar10">NIF:</label>
		<input type="text" class="form-control" name="empre_nif" value="{!!$empre->empre_nif!!}" maxlength="22">  </div>
					
		<div class="form-group col-sm-2">    <label class="control-label text-left mar10">Tel&#xE9;fono:</label>
		<input type="text" class="form-control" name="empre_tel1" value="{!!$empre->empre_tel1!!}" pattern="[0-9 -]{0,11}"> 	</div>
				
		<div class="form-group col-sm-2">     <label class="control-label text-left mar10">Tel&#xE9;fono:</label>
		<input type="text" class="form-control" name="empre_tel2" value="{!!$empre->empre_tel2!!}" pattern="[0-9 -]{0,11}"> 	</div>
				
		<div class="form-group col-sm-2">    <label class="control-label text-left mar10">Tel&#xE9;fono:</label>
		<input type="text" class="form-control" name="empre_tel3" value="{!!$empre->empre_tel3!!}" pattern="[0-9 -]{0,11}"> 	</div>
						
		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Notas:</label>
		<textarea class="form-control" name="empre_notas" rows="4"> {!!$empre->empre_notas!!} </textarea> 	</div> 	<br>

		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Texto de facturas: </label>
		<textarea class="form-control" name="factutex" rows="4"> {!!$empre->factutex!!} </textarea> 	</div> 	<br>

		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Texto de presupuestos: </label>
		<textarea class="form-control" name="presutex" rows="4"> {!!$empre->presutex!!} </textarea> 	</div> 	<br>
		
	@include('includes.subuto')

	</form>

</div> </div>

@endsection
	 
@section('js')
    @parent
    
	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/main.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/guarda.js') !!}"></script>
	 	  
@endsection