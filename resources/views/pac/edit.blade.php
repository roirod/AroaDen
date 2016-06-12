@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


{!! addtexto("Editar Paciente") !!}


<div class="row">
 <div class="col-sm-12">
  	<form class="form" id="form" role="form" action="{!!url("/Pacientes/$idpac")!!}" method="POST">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="PUT">

		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Apellidos:</label>
		<input type="text" class="form-control" name="apepac" value="{!!$pacientes->apepac!!}" maxlength="111" required>  </div>
				
		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Nombre:</label>
		<input type="text" class="form-control" name="nompac" value="{!!$pacientes->nompac!!}" maxlength="111" required >  </div>
				
		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Poblaci&#xF3;n:</label>
		<input type="text" class="form-control" name="pobla" value="{!!$pacientes->pobla!!}" maxlength="111">  </div>  
				  
		<div class="form-group col-sm-3">  <label class="control-label text-left mar10">Direcci&#xF3;n:</label>
		<input type="text" class="form-control" name="direc" value="{!!$pacientes->direc!!}" maxlength="111">  	</div>
				
		<div class="form-group col-sm-2">  <label class="control-label text-left mar10">DNI:</label>
		<input type="text" class="form-control" name="dni" value="{!!$pacientes->dni!!}" maxlength="9" required>  </div>
			
		<div class="form-group col-sm-2">  <label class="control-label text-left mar10">Sexo:</label>
		 <select name="sexo" class="form-control" required>    
			@if( $pacientes->sexo == 'hombre' )
				<option value="hombre" selected> hombre </option>
				<option value="mujer"> mujer </option> 
			@else
				<option value="hombre"> hombre </option>
				<option value="mujer" selected> mujer </option> 
			@endif	        
		</select> </div>
				
		<div class="form-group col-sm-2">    <label class="control-label text-left mar10">Tel&#xE9;fono1:</label>
		<input type="text" class="form-control" name="tel1" value="{!!$pacientes->tel1!!}" maxlength="11"> 	</div>
				
		<div class="form-group col-sm-2">     <label class="control-label text-left mar10">Tel&#xE9;fono2:</label>
		<input type="text" class="form-control" name="tel2" value="{!!$pacientes->tel2!!}" maxlength="11"> 	</div>
				
		<div class="form-group col-sm-2">    <label class="control-label text-left mar10">Tel&#xE9;fono3:</label>
		<input type="text" class="form-control" name="tel3" value="{!!$pacientes->tel3!!}" maxlength="11"> 	</div>
				
		<div class="form-group col-sm-4">      <label class="control-label text-left mar10">F. nacimiento:</label>
			@if( isset($pacientes->fenac) ) 
				<input type="date" name="fenac" value="{!!$pacientes->fenac!!}">
			@else
				<input type="date" name="fenac">
			@endif
		</div>
				
		<div class="form-group col-sm-11">      <label class="control-label text-left mar10">Notas:</label>
		<textarea class="form-control" name="notas" rows="4"> {!!$pacientes->notas!!} </textarea> 	</div> 

@include('includes.subuto')

 </form>  </div> </div>

@endsection
	 
@section('js')
    @parent
    
	  <script type="text/javascript" src="{!! asset('assets/js/modernizr.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/minified/polyfiller.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/main.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/areyousure.js') !!}"></script>
	  <script type="text/javascript" src="{!! asset('assets/js/guarda.js') !!}"></script>
	 	  
@endsection