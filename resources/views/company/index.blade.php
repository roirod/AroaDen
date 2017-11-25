@extends('layouts.main')

@section('content')

@include('includes.company_nav')

@include('includes.messages')
@include('includes.errors')

<br> 

<div class="row"> 
  <div class="col-sm-12"> 
    <div class="input-group"> 
      <span class="input-group-btn pad10">  <p> Datos empresa: </p> </span>
      <div class="btn-toolbar pad4" role="toolbar"> 
        <div class="btn-group">
          <a href="{{ url("/$main_route/$form_route") }}" role="button" class="btn btn-sm btn-success">
            <i class="fa fa-edit"></i> Editar
          </a>
        </div>  
</div> </div> </div> </div>


<div class="row">
  <div class="col-sm-12 fonsi15">

    @foreach ($main_loop as $item)

      <?php
        $aroaden_item_name = "aroaden.".$item['name'];
        $item_name = $item['name'];
      ?>

      @if ($item['type'] == 'textarea')

         <br>
         <div class="{{ $item['col'] }} pad10">
            <i class="fa fa-minus-square"></i> {!! @trans($aroaden_item_name) !!}: <br>
            <div class="box200">{!! nl2br(e($obj->$item_name)) !!}</div>

            <hr> <br>
         </div>

      @else

          <div class="{{ $item['col'] }} pad10">
            <i class="fa fa-minus-square"></i> {!! @trans($aroaden_item_name) !!}: &nbsp; 
            <span class="text-muted"> {!! $obj->$item_name !!} </span>  
          </div> 

      @endif

    @endforeach

 </div>
</div> 

@endsection





