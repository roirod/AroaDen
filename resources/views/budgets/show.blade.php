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
     		<span class="input-group-btn pad10"> 
          <p> Presupuesto </p> 
        </span>
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
    <div class="col-sm-9">
     <div class="panel panel-default">
      <table class="table table-striped table-bordered table-hover">
        <tr class="fonsi14">
          <td class="wid110">{{ @trans('aroaden.date') }}</td>
          <td class="wid180">{{ @trans('aroaden.treatment') }}</td>
          <td class="wid95 textcent">{{ @trans('aroaden.units') }}</td>             
          <td class="wid70 textcent">{{ @trans('aroaden.tax') }}</td>
          <td class="wid95 textcent">{{ @trans('aroaden.price') }}</td>
        </tr>
      </table>

     	<div class="box300">
        <table class="table table-striped table-bordered table-hover">

    	    @foreach($items as $item)

    	    	@if( (isset($created_at) && $created_at != $item->created_at) || !isset($created_at) )

    	    		<tr class="danger">
    		     	  <td class="wid110"></td>
    					  <td class="wid180"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid70 textcent"></td>
    					  <td class="wid95"></td>
       				</tr>
    	    		<tr class="info">
    		     	  <td class="wid110"></td>
    					  <td class="wid180"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid70 textcent"></td>
    					  <td class="wid95"></td>
    	    		</tr>

    					<tr>
    			 		  <td class="wid110"> {!! DatTime($item->created_at) !!} </td>
    					  <td class="wid180"></td>
    					  <td class="wid95 textcent"></td>
    					  <td class="wid70 textcent"></td>
    					  <td class="wid95"></td>
    					</tr>

              <tr>
                <td class="wid110">
                  <div class="btn-toolbar pad4" role="toolbar">

                    <div class="btn-group">
                      <a href="{!! url($routes['budgets']."/$item->uniqid/edit") !!}" title="{!! @trans("aroaden.edit") !!}" class="btn btn-sm btn-success">
                         <i class="fa fa-edit"></i> 
                      </a>
                    </div>

                    <div class="btn-group">
                      <button title="{!! @trans("aroaden.delete") !!}" class="btn btn-danger btn-sm dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-times"></i> <span class="caret"></span> 
                      </button>
                      <ul class="dropdown-menu" role="menu">
                        <form class="form" action="{!! url("/$main_route/delBudget") !!}" method="POST">  
                          {!! csrf_field() !!}

                          <input type="hidden" name="uniqid" value="{!! $item->uniqid !!}"> 
                          <input type="hidden" name="idpat" value="{!! $idpat !!}"> 

                          <li><button type="submit"> <i class="fa fa-times"></i> Eliminar </button></li>
                        </form>
                      </ul>
                    </div>

                  </div>
                </td>
                <td class="wid180"></td>
                <td class="wid95 textcent"></td>
                <td class="wid70 textcent"></td>
                <td class="wid95"></td>
              </tr>

       			@endif

    			<tr>
    	 			<td class="wid110"></td>
    	 			<td class="wid180">{!! $item->name !!}</td>
    	 			<td class="wid95 textcent">{!! $item->units !!}</td>
    	 			<td class="wid70 textcent">{!! $item->tax !!} %</td>

            @php
              $price = calcTotal($item->price, $item->tax);                          
            @endphp

            <td class="wid95 textcent">{!! $price !!} {{ $_SESSION["Alocale"]["currency_symbol"] }}</td>
    			</tr>

    	    <?php $created_at = $item->created_at; ?>
    			
    	  @endforeach
        
     	</table>
    </div>

    <table class="table table-striped table-bordered table-hover">
      <tr class="fonsi14">
        <td class="wid110">{{ @trans('aroaden.date') }}</td>
        <td class="wid180">{{ @trans('aroaden.treatment') }}</td>
        <td class="wid95 textcent">{{ @trans('aroaden.units') }}</td>             
        <td class="wid70 textcent">{{ @trans('aroaden.tax') }}</td>
        <td class="wid95 textcent">{{ @trans('aroaden.price') }}</td>
      </tr>
    </table>

  </div> </div> </div>		

@endsection