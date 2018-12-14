<div class="row">
	 <form class="form">
		 <div class="input-group">
			  <span class="input-group-btn pad10"> <p> &nbsp; {{ Lang::get('aroaden.search') }}</p> </span>

			  <div class="col-sm-4">
			   	<input type="search" name="string" id="string" class="form-control string_class" placeholder="{{ Lang::get('aroaden.write_name_surname_id') }}" autofocus required>
			  </div>

			  <div class="col-sm-3">
		          <a href="{{ $main_route }}" role="button" class="btn btn-md btn-danger">
		            <i class="fa fa-trash"></i> {{ Lang::get('aroaden.remove_text') }}
		          </a>
			  </div>
		 </div>
	 </form>
</div>