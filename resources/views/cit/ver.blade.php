@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

<div class="row">
  <div class="col-sm-12">
  	<form role="form" class="form" action="{{ url('/Citas/ver') }}" method="post">
  		{!! csrf_field() !!}

  		<div class="input-group">
  		 	<span class="input-group-btn pad4"> <p> &nbsp;<i class="fa fa-clock-o"></i> Citas: </p> </span>
  		  <div class="col-sm-2"> 
  		    <span class="input-group-btn"> 
  		      <select name="selec" class="form-control">
  		        <option value="hoy" selected>hoy</option> 
  		        <option value="1semana">+1 semana</option>
  		        <option value="1mes">+1 mes</option>
  		        <option value="3mes">+3 meses</option> 
  		        <option value="1ano">+1 año</option>
  		        <option value="menos1mes">-1 mes</option>
  		        <option value="menos3mes">-3 meses</option>
  		        <option value="menos1ano">-1 año</option>
  		        <option value="menos5ano">-5 años</option>
  		        <option value="menos20ano">-20 años</option>
  		        <option value="todas">todas</option>
  		      </select> 
  		    </span>
  		  </div>
  		  <div class="col-sm-2">
  		    <span> 
  		      <button class="btn btn-default" name="veord" type="submit"> ver <i class="fa fa-arrow-circle-right"></i></button>
  		    </span>
  		  </div>
</div>  </form>  </div>  </div>


<div class="row">
  <div class="col-sm-12">
    <form role="form" class="form" action="{{ url('/Citas/ver') }}" method="post">
      {!! csrf_field() !!}

      <div class="input-group pad10"> 
        <span class="input-group-btn pad4">  <p> Fecha de: </p> </span>
        <div class="col-sm-4"> 
          <input name="fechde" type="date" autofocus required>
        </div>
        <div class="col-sm-1">
          <span class="input-group-btn pad4">  <p> hasta: </p> </span> 
        </div>
        <div class="col-sm-4 input-group">
          <input name="fechha" type="date" required>
        <input type="hidden" name="selec" value="rango">
        <button class="btn btn-default" name="veo" type="submit"> ver <i class="fa fa-arrow-circle-right"></i></button>
        </div>
      </div>
    
    </form>
</div> </div>


<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-default"> 
			<table class="table">
				 <tr class="fonsi16 success">
					   <td class="wid50"></td>
					   <td class="wid290">Paciente</td>
					   <td class="wid110">Hora</td>
					   <td class="wid110">Día</td>
					   <td class="wid230">Notas</td>
				 </tr>
			 </table>
 
 			<div class="box400">

 				<table class="table table-hover">
 
 	
					@foreach ($citas as $cita)
					 	<tr>
								<td class="wid50">
									<a href="{{url("/Pacientes/$cita->idpac")}}" target="_blank" class="btn btn-default" role="button">
										<i class="fa fa-hand-pointer-o"></i>
									</a> 
								</td>

							 	<td class="wid290"> 
									<a href="{{url("/Pacientes/$cita->idpac")}}" class="pad4" target="_blank">
											{{$cita->apepac}}, {{$cita->nompac}} 
									</a>
							 	</td>

							 	<td class="wid110"> {{$cita->horacit}} </td>
							 	<td class="wid110"> {{date('d-m-Y', strtotime($cita->diacit) )}} </td>
							 	<td class="wid230"> {{$cita->notas}} </td>
					 	</tr>
					@endforeach

</table>
</div> </div> </div> </div>

@endsection
	 
@section('js')
    @parent

	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	  
@endsection