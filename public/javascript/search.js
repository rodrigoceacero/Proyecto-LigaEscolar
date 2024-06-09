document.addEventListener("DOMContentLoaded", function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search');
    const containerList = document.getElementById('listar');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const name = searchInput.value;
        const inactive = searchForm.querySelector('input[name="inactive"]').value;
        console.log('Search query:', name); // Log de depuración

        fetch(`${searchForm.action}?search=${encodeURIComponent(name)}&inactive=${inactive}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data); // Log de depuración
                if (!data.found) {
                    Swal.fire({
                        title: 'No hay equipos con ese nombre, inténtalo de nuevo',
                        icon: 'info',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn-sweet',
                            title: 'titulo-sweet'
                        }
                    });
                } else {
                    containerList.innerHTML = data.content;
                    searchInput.value = '';
                    attachExpandListeners();
                    attachDeleteListeners();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    attachExpandListeners();
    attachDeleteListeners();
});
