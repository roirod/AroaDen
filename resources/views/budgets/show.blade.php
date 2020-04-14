@extends('layouts.main')

@section('content')

  @include('includes.patients_nav')

  @include('includes.messages')
  
  <div class="row">
    <div class="col-sm-12">

    <div class="col-sm-12 pad10">
        @include('form_fields.show.name')
    </div>

    	<div class="input-group">
     		<span class="input-group-btn pad10"> <p> Presupuesto </p> </span>
    		<div class="btn-toolbar pad4" role="toolbar"> 
      		<div class="btn-group">
          		<a href="{!! url("/$main_route/$idpat/create") !!}" role="button" class="btn btn-sm btn-primary">
              	<i class="fa fa-plus"></i> Nuevo
           		</a>
         		</div>
         	</div>
       </div>
    </div> </div>


    <div class="row">
    <div class="col-sm-8">
     <div class="panel panel-default">
      <table class="table table-striped table-bordered table-hover">
        <tr class="fonsi15">
       	  <td class="wid110">Fecha</td>
    		  <td class="wid180">Tratamiento</td>
    		  <td class="wid95 textcent">Cantidad</td>
    		  <td class="wid95 textcent">Precio</td>
    		  <td class="wid95"></td>
        </tr>
      </table>

     	<div class="box300">
        <table class="table table-striped table-bordered table-hover">

    	    @foreach($budgets as $bud)

    	    	@if( (isset($created_at) && $created_at != $bud->created_at) || !isset($created_at) )

    	    		<tr class="danger">
    		     	  <td class="wid110"></td>
    					  <td class="wid180"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid95"></td>
       				</tr>
    	    		<tr class="info">
    		     	  <td class="wid110"></td>
    					  <td class="wid180"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid95"></td>
    	    		</tr>

    					<tr>
    			 		  <td class="wid110"> {!! DatTime($bud->created_at) !!} </td>
    					  <td class="wid180"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid95"></td>
    					</tr>

              <tr>
                <td class="wid110">
                  <a href="{!! url($routes['budgets']."/$bud->uniqid/edit") !!}" role="button" class="btn btn-sm btn-success">
                     <i class="fa fa-edit"></i> {!! @trans("aroaden.edit") !!}
                  </a>
                </td>
                <td class="wid180"></td>
                <td class="wid95 textcent"></td>
                <td class="wid95 textcent"></td>
                <td class="wid95"></td>
              </tr>

       			@endif

    			<tr>
    	 			<td class="wid110"></td>
    	 			<td class="wid180">{!! $bud->name !!}</td>
    	 			<td class="wid95 textcent">{!! $bud->units !!}</td>
    	 			<td class="wid95 textcent">{!! $bud->price !!} €</td>
    	 			<td class="wid95"></td>
    			</tr>

    	    <?php $created_at = $bud->created_at; ?>
    			
    	  @endforeach
        
     	</table>
    </div>

    <table class="table table-striped table-bordered table-hover">
      <tr class="fonsi15">
        <td class="wid110">Fecha</td>
        <td class="wid180">Tratamiento</td>
        <td class="wid95 textcent">Cantidad</td>
        <td class="wid95 textcent">Precio</td>
        <td class="wid95"></td>
      </tr>
    </table>

  </div> </div> </div>		

@endsection