<div class="row">
	 <form role="form" class="form">

		 <div class="input-group">

			  <span class="input-group-btn pad10"> <p> &nbsp; {{ Lang::get('aroaden.search_in') }}</p> </span>

			  <div class="col-sm-2">
	  			<select name="search_in" class="form-control string_class" required>
	  				<option value="surname" selected>{{ Lang::get('aroaden.surnames') }}</option>
	  				<option value="dni">{{ Lang::get('aroaden.dni') }}</option>
				</select>
			  </div>

			  <div class="col-sm-4">
			   		<input type="search" name="string" id="string" class="form-control string_class" placeholder="{{ Lang::get('aroaden.write_2_or_more') }}" autofocus required>
			  </div>

			  <div class="col-sm-3">
		          <a href="{{ url("/$main_route") }}" role="button" class="btn btn-md btn-danger">
		            <i class="fa fa-trash"></i> {{ Lang::get('aroaden.remove_text') }}
		          </a>
			  </div>

		 </div>
	 </form>
</div>