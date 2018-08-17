	<div class="col-sm-2 pad4 max150">
	  <img src="{!! $profile_photo !!}" class="max150 pad4">
	</div>

	<script>
	  $(document).ready(function(){
	  	$("#uploadProfilePhoto").on('submit', function(event){
  		  event.stopPropagation();
  		  event.preventDefault();

        var formData = new FormData(this);
    
        $('input[type="file"]').val('');

        var obj = {
          data: formData,          
          url: '{!! "/$main_route" !!}',
          uploadFiles: true
        };

        util.processAjaxReturnsJson(obj).done(function(response) {
          if (response.error)
            return util.showPopup(response.msg, false);

          $("#profile_photo div img").remove();
          $("#profile_photo div").append('<img src="' + response.profile_photo + '" class="max150 pad4" />').fadeIn(4000);
        });
      });
	  });
	</script>
