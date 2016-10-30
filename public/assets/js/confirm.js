
$( document ).ready(function() {

	//console.log('pagina cargada');

	$( ".confirm" ).click(function(e) {

		e.preventDefault();

		//console.log('boton submit');

		var form = $(this).parents('form');

		swal({
	        title: 'Estás seguro?',
            type: 'warning',
            showCancelButton: true,
	        allowOutsideClick: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si',
            cancelButtonText: 'No',
            confirmButtonClass: 'confirm-class',
            cancelButtonClass: 'cancel-class',
		}).then(
			function(isConfirm) {
			  if (isConfirm) form.submit();
			  //console.log('form submit');
			}
		)
	});
});