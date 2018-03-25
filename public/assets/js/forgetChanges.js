$(function() {
	$('#form').areYouSure( {'message':'No has guardado los cambios.'} );
	$('#form').on('dirty.areYouSure', function() {
	   // Enable save button only as the form is dirty.
	   $(this).find('input[type="submit"]').removeAttr('disabled');
	 });
	$('#form').on('clean.areYouSure', function() {
	   // Form is clean so nothing to save - disable the save button.
	   $(this).find('input[type="submit"]').attr('disabled', 'disabled');
	 });
});