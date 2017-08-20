@extends('layouts.main')

@section('content')

@include('includes.pernav')

@include('includes.messages')
@include('includes.errors')

<div class="row"> 
  <div class="col-sm-12"> 
		<div class="input-group"> 
			<span class="input-group-btn pad10">  <p> Personal: </p> </span>
			<div class="btn-toolbar pad4" role="toolbar"> 
				<div class="btn-group">
					<a href="{{url("/$main_route/$id/edit")}}" role="button" class="btn btn-sm btn-success">
						<i class="fa fa-edit"></i> Editar
					</a>
				</div>	
			<div class="btn-group">
			 	<form role="form" class="form" id="form" role="form" action="{!!url("/$main_route/$id")!!}" method="POST">	
			  		{!! csrf_field() !!}

					<input type="hidden" name="_method" value="DELETE">

					<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
					<i class="fa fa-times"></i> Eliminar <span class="caret"></span>  </button>
					<ul class="dropdown-menu" role="menu"> 
						<li>
							@include('includes.delete_button')
						</li>
					</ul>			
		 			
		 		</form>

</div> </div> </div> </div> </div>

<div class="row pad10">
  <form role="form" action="{!!url("/$main_route/upload")!!}" method="post" enctype="multipart/form-data">
  	  {!! csrf_field() !!}

       <input type="hidden" name="id" value="{!!$id!!}">
       <input type="hidden" name="profile_photo" value="1">
  
  	  <div class="input-group">
  	    <span class="input-group-btn pad4"> 
  	      <p>&nbsp;&nbsp; Subir foto perfil: &nbsp;&nbsp;</p> 
  	    </span> 
  	    <span class="input-group-btn"> 
  	      <input type="file" class="btn btn-default" name="files" />
  	    </span> 
  	    &nbsp;&nbsp;&nbsp;
  	    <span class="pad10"> 
  	      <button type="submit" class="btn btn-info">&nbsp;<i class="fa fa-upload"></i>&nbsp;</button>
  	    </span>
  	  </div>
  </form>
</div>

<hr>

<div class="row mar10"> 
  <div class="col-sm-12"> 
    <div class="row fonsi16">

		<div class="col-sm-2 pad4 max150">
			<img src="{!! $profile_photo !!}" class="max150 pad4">
		</div>

		<div class="col-sm-10">
			<div class="col-sm-6 pad4"> 
			<i class="fa fa-minus-square"></i> Paciente: &nbsp; {{$personal->surname}},&nbsp;{{$personal->name}} </div>

			<div class="col-sm-2 pad4"> 
			<i class="fa fa-minus-square"></i> id: &nbsp; {{$personal->idper}} </div>

			<div class="col-sm-3 pad4">
			<i class="fa fa-minus-square"></i> Cargo: &nbsp;{{$personal->position}} </div>

			<div class="col-sm-5 pad4"> 
			<i class="fa fa-minus-square"></i> Poblaci&#xF3;n: &nbsp; {{$personal->city}} </div> 

			<div class="col-sm-6 pad4">
			<i class="fa fa-minus-square"></i> Direcci&#xF3;n: &nbsp; {{$personal->address}} </div> 

			 <div class="col-sm-3 pad4">
			<i class="fa fa-minus-square"></i> DNI: &nbsp; {{ $personal->dni}} </div> 

			<div class="col-sm-3 pad4">
			<i class="fa fa-minus-square"></i> Tel&#xE9;fono1: &nbsp;{{$personal->tel1}} </div>

			 <div class="col-sm-3 pad4">
			<i class="fa fa-minus-square"></i> Tel&#xE9;fono2: &nbsp; {{ $personal->tel2}} </div>

			 <div class="col-sm-3 pad4"> 
			<i class="fa fa-minus-square"></i> F. nacimiento: &nbsp; {{date ('d-m-Y', strtotime ($personal->birth) )}} </div>
		</div>

		 <div class="col-sm-12 pad4"> 
			<i class="fa fa-minus-square"></i> Notas: <br>
			<div class="box200"> {!! nl2br(e($personal->notes)) !!} </div>
		 </div>

 </div> </div> </div>

<hr>
<br>

<div class="row">
  <div class="col-sm-12"> 
 	<p> Trabajos realizados: </p> 
</div> </div>

<div class="row">
 <div class="col-sm-12">
  <div class="panel panel-default">
   <table class="table">
   	 <tr class="fonsi16 success">
	   	 	<td class="wid180">Paciente</td>
	   	 	<td class="wid180">Tratamiento</td>
	   	 	<td class="wid95 textcent">Cantidad</td>
	   	 	<td class="wid95">Fecha</td>
	   	 	<td class="wid95"> </td>
   	 </tr>
   </table>
   <div class="box500">
     <table class="table table-striped">

				@foreach ($trabajos as $traba)
					<tr>

						<td class="wid180">
							<a href="{{url("/$other_route/$traba->idpac")}}" class="pad4" target="_blank">
								{{$traba->surname}}, {{$traba->name}}
							</a>
						</td>

					   	<td class="wid180"> {{$traba->servicio_name}} </td>
					   	<td class="wid95 textcent"> {{$traba->units}} </td>
					   	<td class="wid95"> {{date('d-m-Y',strtotime ($traba->day))}} </td>
					   	<td class="wid95"> </td>
			   		</tr>						
				@endforeach
	

		 </table>
		</div>

 </div> </div> </div>
 
@endsection