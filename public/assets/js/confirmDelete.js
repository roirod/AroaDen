$(document).ready(function() {
	$(".del_btn").click(function(event) {
	  event.stopPropagation();
	  event.preventDefault();

	  var $this = $(this);
	  util.confirmDeleteAlert($this);
	});
});
