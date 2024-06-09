function attachDeleteListeners() {
    document.querySelectorAll('.btn-delete').forEach(function(button) {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            let sportId = this.getAttribute('data-id');
            let sportName = this.getAttribute('data-name');

            Swal.fire({
                title: '¿Estás seguro?',
                text: `Vas a marcar "${sportName}" como inactivo.`,
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
                    fetch(`/sport/delete/${sportId}`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ confirmar: true })
                    }).then(response => {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Inactivo',
                                text: 'Se ha marcado correctamente como inactivo',
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
                                'No se ha podido marcar como inactivo',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    attachDeleteListeners();
});
