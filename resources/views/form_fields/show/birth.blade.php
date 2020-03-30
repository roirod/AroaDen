	 <div class="col-sm-4 pad4"> 
		<i class="fa fa-circle-o fa-min"></i> 
    F. nacimiento: 
    <span class="bggrey pad4">
      {{ date ('d-m-Y', strtotime ($object->birth) )}}
    </span>
	 </div>