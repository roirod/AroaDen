@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')


<div class="row"> 
	<div class="col-sm-12"> 
		<div class="input-group pad4"> <span class="input-group-btn pad4"> <p> Personal:</p> </span>
			<div class="col-sm-3">
				<span class="input-group-btn">
					<a href="/Personal/create" role="button" class="btn btn-sm btn-primary">
						<i class="fa fa-plus"></i> Nuevo
					</a> 
				</span>
			</div>
		</div> 
	</div>
</div>
	
<div class="row">
	<form role="form" class="form" action="{{url('/Personal/ver')}}" method="post">
		{!! csrf_field() !!}	 
		
		<div class="input-group">
			<span class="input-group-btn pad4"> <p> &nbsp; Buscar apellido:</p> </span>
			<div class="col-sm-4">
				<input type="search" name="busca" class="form-control" placeholder="buscar..." required>
			</div>
			<div class="col-sm-1">
				<button class="btn btn-default" type="submit"> 
					<i class="fa fa-arrow-circle-right"></i>
				</button>
			</div>
		</div>
	</form>
</div>

	
<div class="row">
  <div class="col-sm-12">
	<div class="panel panel-default">
	  <table class="table">
	 	<tr class="fonsi16 success">
			<td class="wid50">&nbsp;</td>
			<td class="wid290">Nombre</td> 
			<td class="wid110">Cargo</td>
			<td class="wid110 textcent">Tel&#xE9;fono</td>
		</tr>
	  </table>
 	 <div class="box400">
 	  <table class="table table-hover">
	
		@foreach ($personal as $persona)	
			<tr> 
				<td class="wid50"> 
					<a class="btn btn-default" href="{{url("/Personal/$persona->idper")}}" target="_blank" role="button">
						<i class="fa fa-hand-pointer-o"></i>
					</a>
				</td>

				<td class="wid290">
					<a href="{{url("/Personal/$persona->idper")}}" class="pad4" target="_blank">
						{{$persona->ape}}, {{$persona->nom}}
					</a>
				</td> 

				<td class="wid110">{{$persona->cargo}}</td> 
				<td class="wid110 textcent">{{$persona->tel1}}</td>
			</tr>		
		@endforeach
	
		<table class="table table-hover">
			<tr>
		 		<div class="textcent">
			 		<hr>
			 		{{$personal->links()}}
				</div>
			</tr> 
		</table>
	
	</table>

</div> </div> </div> </div>

@endsection