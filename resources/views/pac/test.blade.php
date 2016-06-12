@extends('layouts.main')

@section('content')

@include('includes.messages')
@include('includes.errors')

   
<div class="row visfile mar10 pad20">
  <div class="col-lg-12">
  

      {{dd($dir)}} 



  </div>
</div>
	 

@endsection