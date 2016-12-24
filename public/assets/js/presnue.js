
$(document).ready(function() {

	$.ajaxSetup({
	   headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
	}); 

   $(document).on('submit','#nueform',function(evt){	 	   	 
	    var nueurl = $("#nueurl").attr("value");
	    var eventrig = $(this);

	    $("input[type=submit]").attr("disabled", "disabled");
	      
	    $.ajax({
	        type     : 'POST',
	        url      : nueurl,
	        data     : $(eventrig).serialize(),
	        
	    }).done(function(response) {
	    	
	       $("#presup").html(response);
	       
	       $("input[type=submit]").removeAttr("disabled");
	       		   		          
	      }).fail(function() {
	    	         
	        $("input[type=submit]").removeAttr("disabled");
	        
	        alert("Hubo un problema");
	             	         
	    });

	    $("input[type=submit]").removeAttr("disabled");

	    evt.preventDefault();   
	          	
   });
      
});