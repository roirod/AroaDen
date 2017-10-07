@extends('layouts.main')

@section('content')

@include('includes.empnav')

@include('includes.messages')
@include('includes.errors')


{!! addtexto("Editar Datos") !!}

<div class="row">
 <div class="col-sm-12">
  
  	<form class="form" id="form" role="form" action="{!!url("/$main_route/saveData")!!}" method="post">
		{!! csrf_field() !!}
		
		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Nombre:</label>
		<input type="text" class="form-control" name="company_name" value="{!!$empre->company_name!!}" maxlength="111" autofocus required >  </div>
				
		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Poblaci&#xF3;n:</label>
		<input type="text" class="form-control" name="company_city" value="{!!$empre->company_city!!}" maxlength="111" >  </div>  
				  
		<div class="form-group col-sm-4">  <label class="control-label text-left mar10">Direcci&#xF3;n:</label>
		<input type="text" class="form-control" name="company_address" value="{!!$empre->company_address!!}" maxlength="111" >  	</div>
				
		<div class="form-group col-sm-2">  <label class="control-label text-left mar10">NIF/CIF:</label>
		<input type="text" class="form-control" name="company_nif" value="{!!$empre->company_nif!!}" maxlength="22">  </div>
					
		<div class="form-group col-sm-2">    <label class="control-label text-left mar10">Tel&#xE9;fono:</label>
		<input type="text" class="form-control" name="company_tel1" value="{!!$empre->company_tel1!!}" pattern="[0-9 -]{0,11}"> 	</div>
				
		<div class="form-group col-sm-2">     <label class="control-label text-left mar10">Tel&#xE9;fono:</label>
		<input type="text" class="form-control" name="company_tel2" value="{!!$empre->company_tel2!!}" pattern="[0-9 -]{0,11}"> 	</div>
				
		<div class="form-group col-sm-2">    <label class="control-label text-left mar10">Tel&#xE9;fono:</label>
		<input type="text" class="form-control" name="company_tel3" value="{!!$empre->company_tel3!!}" pattern="[0-9 -]{0,11}"> 	</div>
						
		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Notas:</label>
		<textarea class="form-control" name="company_notes" rows="4"> {!!$empre->company_notes!!} </textarea> 	</div> 	<br>

		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Texto de facturas: </label>
		<textarea class="form-control" name="invoice_text" rows="4"> {!!$empre->invoice_text!!} </textarea> 	</div> 	<br>

		<div class="form-group col-sm-12">      <label class="control-label text-left mar10">Texto de presupuestos: </label>
		<textarea class="form-control" name="budget_text" rows="4"> {!!$empre->budget_text!!} </textarea> 	</div> 	<br>
		
		@include('includes.submit_button')

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