@extends('layouts.main')

@section('content')

	@include('includes.users_nav')

	<div class="row">
  	<div class="col-sm-10">

      <fieldset>
        <legend>
          {!! @trans('aroaden.del_user') !!}
        </legend>

        <div id="del_user">
          <form class="del_user" v-on:submit.prevent="onSubmit">
            {!! csrf_field() !!}

						<div class="input-group"> 
							<span class="input-group-btn pad4"> 
								<p> &nbsp; {!! Lang::get('aroaden.user') !!}:</p> 
							</span>
				 			<div class="col-sm-6">
				 				<select name="uid" class="form-control">
			 
									@foreach($main_loop as $user)
										@continue($user->username == 'admin')
						   
						  			<option value="{!! $user->uid !!}">{!! $user->username !!}({!! $user->full_name !!})</option> 
									@endforeach
			 
			 					</select>
			 				</div>
			 			</div>

				   	<div class="pad4">
							<button type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-times"></i> {!! @trans("aroaden.delete") !!} <span class="caret"></span>  
							</button>
							<ul class="dropdown-menu" role="menu"> 
								<li>
									@include('includes.delete_button')
								</li>
							</ul>
		 				</div>

          </form>
        </div>

        <script type="text/javascript">
          var rq_url = '{!! url("/$main_route/$form_route") !!}';

          var vmdel_user = new Vue({
            el: '#del_user',
            methods: {
              onSubmit: function() {
                var data = $("form.del_user").serialize();

                axios.post(rq_url, data).then(function (res) {
                  if (res.data.error)
                    return util.showPopup(res.data.msg, false);

                  util.showPopup();
                  util.redirectTo(res.data.redirectTo);
                });
              }
            }
          });

        </script>

			</fieldset>
			
 		</div>
	</div>
 
@endsection