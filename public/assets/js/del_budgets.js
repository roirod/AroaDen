$(document).ready(function() {

   $(document).on('submit','#del_budgets_form',function(evt){

      $.ajaxSetup({
         headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
      });
 	   	 	   	 
     	 var del_url = $("#del_url").attr("value");
     	 var eventrig = $(this);
     	 
     	 $("input[type=submit]").attr("disabled", "disabled");
     	  
        $.ajax({
        	
            type     : 'POST',
            url      : del_url,
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