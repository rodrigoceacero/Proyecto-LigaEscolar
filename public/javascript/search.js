document.addEventListener('DOMContentLoaded', function () {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search');

    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const query = searchInput.value;
            fetchResults(query);
        });
    }
});

function fetchResults(query) {
    const url = new URL(window.location.href);
    url.searchParams.set('search', query);

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (!data.found) {
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
                const listContainer = document.getElementById('listar');
                if (listContainer) {
                    listContainer.innerHTML = data.content;
                    const deleteUrlBase = listContainer.getAttribute('data-delete-url');
                    attachExpandListeners();
                    attachDeleteListeners(deleteUrlBase);
                }
            }
        })
}
