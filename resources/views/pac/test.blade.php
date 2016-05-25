@extends('layouts.main')

@include('includes.other')

@section('content')

@include('includes.pacnav')

@include('includes.messages')
@include('includes.errors')

   
<div class="row visfile mar10 pad20">
  <div class="col-lg-12">
  

      {{dd($dir)}} 



  </div>
</div>
	 

@endsection