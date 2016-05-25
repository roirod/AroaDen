$(document).ready(function() {

   $(document).on('submit','form',function(evt){
 	   	 	   	 
     	 var posturl = $("#posturl").attr("value");
     	 
     	 var eventrig = $(this);
     	 
     	 $("input[type=submit]").attr("disabled", "disabled");
     	  
        $.ajax({
        	
            type     : 'POST',
            url      : posturl,
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