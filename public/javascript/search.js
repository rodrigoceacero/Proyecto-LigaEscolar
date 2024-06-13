document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const list = document.getElementById('listar');

    searchForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(searchForm);
        const params = new URLSearchParams(formData).toString();

        fetch(`${searchForm.action}?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (!data.found){
                    Swal.fire({
                        title: 'No se han encontrado datos, inténtalo de nuevo',
                        icon: 'info',
                        confirmButtonText: 'OK',
                        customClass: {
                            confirmButton: 'btn-sweet',
                            title: 'titulo-sweet'
                        }
                    });
                } else {
                    list.innerHTML = data.content;
                    attachExpandListeners();
                    const deleteUrlBase = list.getAttribute('data-delete-url');
                    attachDeleteListeners(deleteUrlBase);
                }
            })
    });
});
