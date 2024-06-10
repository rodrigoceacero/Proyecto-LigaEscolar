function attachDeleteListeners(urlDelete) {
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            let fieldId = this.getAttribute('data-id');
            let fieldName = this.getAttribute('data-name');
            let deleteUrl = urlDelete + fieldId;

            Swal.fire({
                title: '¿Estás seguro?',
                text: `Vas a eliminar "${fieldName}" . No se podrá deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    text: 'titulo-sweet',
                    title: 'titulo-sweet',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(deleteUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ confirmar: true })
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Eliminado',
                                text: 'Se ha eliminado correctamente',
                                icon: 'success',
                                timer: 5000,
                                timerProgressBar: true,
                                willClose: () => {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire(
                                'Error',
                                'No se ha podido eliminar',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
}
