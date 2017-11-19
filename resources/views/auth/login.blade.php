@extends('layouts.login')

@section('content')


<div class="col-xs-8 col-xs-offset-4">
	<div class="row">

  <br>

    <div class="center-block">
      <div class="col-xs-5 bgtra boradius border2px boxsha">

        <div class="row pad10">
          <div class="col-xs-12">

            <div class="col-md-12 text-left pad10 textcent">
              <h1 class="mar10 fonsi36 login_text textshadow textcent">
                <i class="fa fa-child"></i>
                <br>
                Aroa<small>Den</small>
              </h1>
            </div> 

        <br> <br> 

         <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
            {!! csrf_field() !!}

            <div class="col-md-12 text-left mar4 textcent">
              <input type="text" class="form-control input_login_form" name="username" value="{{ old('username') }}" placeholder="{{ trans('aroaden.user') }}" autofocus required>  
            </div> 
           
            <div class="col-md-12 text-left mar4 textcent">
              <input type="password" class="form-control input_login_form" name="password" placeholder="{{ trans('aroaden.password') }}" required >
            </div> 

          	  @if ($errors->has('username'))
          	      <span class="help-block">
          	          <strong>{{ $errors->first('username') }}</strong>
          	      </span>
          	  @endif  
           
               @if ($errors->has('password'))
                   <span class="help-block">
                       <strong>{{ $errors->first('password') }}</strong>
                   </span>
               @endif 


           <div class="col-md-12 mar4">
            <div class="checkbox login_text"> <label>
              <input type="checkbox" name="remember"> Recordarme </label>
           </div> </div>
     
           <div class="col-md-12 mar4 textcent">
            <br> 
              <button type="submit" class="btn btn_login">
                Acceder <i class="fa fa-chevron-circle-right"></i> 
              </button> 
             </div>

        </form> 

          </div>
        </div>

    <br> 

</div> </div> </div> </div>
 
@endsection