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


@include('form_fields.show.upload_photo')


<hr>

<div class="row mar10"> 
  <div class="col-sm-12"> 
    <div class="row fonsi16">

		@include('form_fields.show.profile_photo')

		<div class="col-sm-10">

			@include('form_fields.show.name')

			@include('form_fields.show.idper')

			@include('form_fields.show.position')

			@include('form_fields.show.city')

			@include('form_fields.show.address')

			@include('form_fields.show.dni')

			@include('form_fields.show.tel1')

			@include('form_fields.show.tel2')

			@include('form_fields.show.birth')

		</div>

		@include('form_fields.show.notes')

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