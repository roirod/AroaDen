var util = {

  multiply: function(num1, num2) {
	var num1 = parseInt(num1, 10);
	var num2 = parseInt(num2, 10);
	var total = num1 * num2;

	return total;
  },

  showPopup: function(msg, success = true, timer = 1000) {
  	if (success) {
		swal({
		    title: msg,
		    type: 'success',
		    showConfirmButton: false,	            
		    timer: timer
		});
  	} else {
		swal({
		    text: msg,
		    type: 'warning',
		});
  	}
  },

  getTodayDate: function() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() +1;
	var yyyy = today.getFullYear();

	if(dd < 10) {
	    dd = '0' + dd
	} 

	if(mm < 10) {
	    mm = '0' + mm
	} 

	today = yyyy + '-' + mm + '-' + dd;

	return today;
  },

};