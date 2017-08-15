$( document ).ready(function() {

    $(".del_btn").click(function(event) {
        event.stopPropagation();
        event.preventDefault();

        var $this = $(this);
        showAlert($this);
    });

    function showAlert($this) {

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
              if (isConfirm) $this.closest('form').submit();
            }
        );

    }

});