
$(document).ready(function() {
	$.ajaxSetup({
	   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
	}); 

   $(document).on('submit','#new_budgets_form',function(evt){	 	   	 
	    var new_url = $("#new_url").attr("value");
	    var eventrig = $(this);

	    $("input[type=submit]").attr("disabled", "disabled");
	      
	    $.ajax({

	        type     : 'POST',
	        url      : new_url,
	        data     : $(eventrig).serialize(),
	        
	    }).done(function(response) {
	    	
	       $("#budgets_list").html(response);
	       $("input[type=submit]").removeAttr("disabled");
	       		   		          
	      }).fail(function() {
	    	         
	        $("input[type=submit]").removeAttr("disabled");
	        alert("Error!!!");
	             	         
	    });

	    $("input[type=submit]").removeAttr("disabled");
	    evt.preventDefault();
   });
});