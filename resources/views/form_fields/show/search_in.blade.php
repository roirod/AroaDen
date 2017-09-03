<div class="row">
	 <form role="form" class="form">

		 <div class="input-group">

			  <span class="input-group-btn pad10"> <p> &nbsp; Buscar en:</p> </span>

			  <div class="col-sm-2">

	  			<select name="busen" class="form-control busca_class" required>

	  				<option value="surname" selected> Apellido/s </option>
	  				<option value="dni"> DNI </option>

				</select>

			  </div>

			  <div class="col-sm-4">
			   		<input type="search" name="busca" id="busca" class="form-control busca_class" placeholder="Buscar..." autofocus required>
			  </div>

			  <div class="col-sm-3">
		          <a href="{{url("/$main_route")}}" role="button" class="btn btn-md btn-danger">
		            <i class="fa fa-trash"></i> Borrar texto
		          </a>
			  </div>

		 </div>
	 </form>
</div>