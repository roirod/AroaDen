@extends('layouts.login')

@section('content')

  <div class="row">

    <div class="col-xs-3 col-xs-offset-2">
      <div class="mar40"></div>

      <div class="row bgtra boradius border2px boxsha">
        <div class="mar20"></div>

        <div class="col-xs-12 pad15">

          <div class="col-md-12">
            <p class="fonsi18 login_text textshadow">
              {{ trans('aroaden.login') }}
            </p>
          </div> 

           <form class="form-horizontal" method="POST" action="{{ url('/login') }}">
              {!! csrf_field() !!}

              <div class="col-md-12">
                <input type="text" class="form-control input_login_form" name="username" value="{{ old('username') }}" placeholder="{{ trans('aroaden.user') }}" autofocus required>

               <br>
              </div>
            
              <div class="col-md-12">
                <input type="password" class="form-control input_login_form" name="password" placeholder="{{ trans('aroaden.password') }}" required>
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

                <div class="mar20"></div>
              </div>
          </form> 

        </div>
      </div>
    </div>

    <div class="col-xs-6">
      <div class="mar70"></div>

      <div class="col-md-12 textcent">
        <h1 class="fonsi46 login_text textshadow textcent">
          <i class="fa fa-child"></i>
          <br>
          Aroa<small>Den</small>
        </h1>
        <br>
      </div> 

      <p class="login_text pad20 fonsi20 fontwe4 textshadow textcent">
        {{ trans('aroaden.aroaden_desc') }}
      </p>
    </div>

  </div>
   
@endsection