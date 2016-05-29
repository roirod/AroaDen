$(document).ready(function() {

   $(document).on('submit','#delform',function(evt){

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
 	   	 	   	 
     	 var delurl = $("#delurl").attr("value");
     	 
     	 var eventrig = $(this);
     	 
     	 $("input[type=submit]").attr("disabled", "disabled");
     	  
        $.ajax({
        	
            type     : 'POST',
            url      : delurl,
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