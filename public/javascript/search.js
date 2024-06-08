document.addEventListener("DOMContentLoaded", function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search');
    const list = document.getElementById('listar');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const query = searchInput.value;

        fetch(`${searchForm.action}?search=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
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
                }else{
                    list.innerHTML = data.content;
                    searchInput.value = '';
                    attachExpandListeners();
                }
            })
            .catch(error => console.error('Error:', error));
    });
});
