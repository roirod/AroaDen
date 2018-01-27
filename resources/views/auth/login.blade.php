@extends('layouts.login')

@section('content')


<div class="col-xs-12">
	<div class="row">

    <div class="col-xs-3 bgtra boradius border2px boxsha col_centered">

      <div class="row">
        <div class="col-xs-12 pad10">

          <div class="col-md-12 textcent">
            <h1 class="fonsi36 login_text textshadow textcent">
              <i class="fa fa-child"></i>
              <br>
              Aroa<small>Den</small>
            </h1>
            <br>
          </div> 

       <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
          {!! csrf_field() !!}

          <div class="col-md-12">
            <input type="text" class="form-control input_login_form" name="username" value="{{ old('username') }}" placeholder="{{ trans('aroaden.user') }}" autofocus required>

           <br>
          </div>
        
          <div class="col-md-12">
            <input type="password" class="form-control input_login_form" name="password" placeholder="{{ trans('aroaden.password') }}" required >
            <br> 
          </div> 

      	  @if ($errors->has('username'))
               <span class="help-block pad10 mar4">
      	          <strong>{{ $errors->first('username') }}</strong>
      	      </span>
      	  @endif  
       
           @if ($errors->has('password'))
               <span class="help-block pad10 mar4">
                   <strong>{{ $errors->first('password') }}</strong>
               </span>
           @endif 

          <div class="col-md-12">
            <button type="submit" class="btn btn_login">
              Acceder <i class="fa fa-chevron-circle-right"></i> 
            </button> 
          </div>

      </form> 

        </div>
      </div>

    <br> 

</div> </div> </div>
 
@endsection