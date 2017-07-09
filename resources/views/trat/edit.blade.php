@extends('layouts.main')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')


{{ addtexto("Editar Tratamiento") }}

<div class="row">
 <div class="col-sm-12 mar10">

    <p class="pad4">
        {{ $tratampa->name }}
        <br>
        Precio: {{ $tratampa->price }} €.
        <br>
        Cantidad: {{ $tratampa->units }}.
        <br>
        Total: {{ numformat($tratampa->units * $tratampa->price) }} €.      
        <br>
        Pagado: {{ $tratampa->paid }} €.
        <br>
        Fecha: {{ date('d-m-Y', strtotime ($tratampa->date) ) }}.        
    </p>    

    <form role="form" id="form" class="form" action="{{url("/Trapac/$idtra")}}" method="POST">
        {!! csrf_field() !!}

        <input type="hidden" name="_method" value="PUT">

        <input type="hidden" name="idpac" value="{{$idpac}}">

         <div class="form-group col-sm-2">
            <label class="control-label text-left mar10">Pagado:</label>            
            <input type="text" name="paid" value="{{$tratampa->paid}}" pattern="[0-9]{1,10}" class="form-control" autofocus required> 
         </div>
   
         <div class="form-group col-sm-4">   <label class="control-label text-left mar10">Fecha:</label>            
             <input type="date" name="date" value="{!!$tratampa->date!!}" class="form-control" required> 
        </div>
        
        <div class="col-sm-12">

            <div class="form-group col-lg-6">   <label class="control-label text-left mar10">Personal 1:</label> 
            
               <select name="per1" class="form-control">

                    @if($tratampa->per1 == 0)
                        <option value="0" selected> </option>
                    @else
                        <option value="0"> </option>
                    @endif  
            
                    @foreach($personal as $person)

                        @if($person->idper == $tratampa->per1)
                            <option value="{{$person->idper}}" selected>{{$person->surname}}, {{$person->name}} - id: {{$person->idper}} - {{$person->position}} </option>
                        @else
                            <option value="{{$person->idper}}">{{$person->surname}}, {{$person->name}} - id: {{$person->idper}} - {{$person->position}} </option>
                        @endif

                    @endforeach    
               
               </select>
            
            </div>

            <div class="form-group col-lg-6">   <label class="control-label text-left mar10">Personal 2:</label> 
               <select name="per2" class="form-control">

                    @if($tratampa->per2 == 0)
                        <option value="0" selected> </option>
                    @else
                        <option value="0"> </option>
                    @endif               

                     @foreach($personal as $person)

                        @if($person->idper == $tratampa->per2)
                            <option value="{{$person->idper}}" selected>{{$person->surname}}, {{$person->name}} - id: {{$person->idper}} - {{$person->position}} </option>
                        @else
                            <option value="{{$person->idper}}">{{$person->surname}}, {{$person->name}} - id: {{$person->idper}} - {{$person->position}} </option>
                        @endif
                        
                     @endforeach     
               
               </select>
            </div>

        </div>    

      @include('includes.submit_button')

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
@endsection