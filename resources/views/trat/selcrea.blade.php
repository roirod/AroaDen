@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


{{ addtexto("Añadir Tratamientos") }}


<div class="row">
 <div class="col-sm-12 mar10">

    <p class="pad4">
        {{ $servicio->nomser }} | precio: {{ $servicio->precio }} €
    </p>    

    <form role="form" id="form" class="form" action="{{url('/Trapac')}}" method="post">
        {!! csrf_field() !!}

        <input type="hidden" name="idpac" value="{{$idpac}}">
        <input type="hidden" name="idser" value="{{$servicio->idser}}">
        <input type="hidden" name="precio" value="{{$servicio->precio}}">
        <input type="hidden" name="iva" value="{{$servicio->iva}}">

         <div class="form-group col-sm-2">
            <label class="control-label text-left mar10">Cantidad:</label>          
            <input type="number" min="1" value="1" step="1" name="canti" id="uno" onchange="multi(this.value)" class="form-control" autofocus required>
         </div>
        
        <input type="hidden" value="{{$servicio->precio}}" id="dos">

         <div class="form-group col-sm-2">
            <label class="control-label text-left mar10">Pagado:</label>            
            <input type="text" name="pagado" id="Tot" value="{{$servicio->precio}}" pattern="[0-9]{1,10}" class="form-control" required> 
         </div>
   
         <div class="form-group col-sm-3">   <label class="control-label text-left mar10">Fecha:</label>            
             <input type="date" name="fecha" class="form-control" required> 
        </div>
        <br>

        <div class="form-group col-lg-6">   <label class="control-label text-left mar10">Personal 1:</label> 
        
           <select name="per1" class="form-control">
                 
                 <option value="0" selected> </option>

                 @foreach($personal as $person)
                    <option value="{{$person->idper}}">{{$person->ape}}, {{$person->nom}} - id: {{$person->idper}} - {{$person->cargo}}
                    </option>
                 @endforeach    
           
           </select>
        
        </div>

        <div class="form-group col-lg-6">   <label class="control-label text-left mar10">Personal 2:</label> 
           <select name="per2" class="form-control">
                 <option value="0" selected> </option>

                 @foreach($personal as $person)
                    <option value="{{$person->idper}}">{{$person->ape}}, {{$person->nom}} - id: {{$person->idper}} - {{$person->cargo}}
                    </option>
                @endforeach    
           
           </select>
        </div>

        @include('includes.subuto')

    </form>

 </div>
</div>

@endsection

@section('js')
    @parent   
	  <script type="text/javascript" src="{{ asset('assets/js/modernizr.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/minified/polyfiller.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/main.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/areyousure.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/guarda.js') }}"></script>
	  <script type="text/javascript" src="{{ asset('assets/js/calcula.js') }}"></script>
@endsection