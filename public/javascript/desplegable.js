document.addEventListener('DOMContentLoaded', function () {
    const contExpansible = document.querySelectorAll('.desplegar');
    const contDesplegar = document.querySelectorAll('.contenedor-desplegable');
    let contExpandido = null;

    contExpansible.forEach((link, index) => {
        link.addEventListener('click', function (e) {
            if (e.target.closest('.boton')) {
                return;
            }

            e.preventDefault();
            const icono = link.querySelector('i');

            if (contExpandido === contDesplegar[index]) {
                contDesplegar[index].classList.remove('expanded');
                icono.classList.remove('ti-chevron-up');
                icono.classList.add('ti-chevron-down');
                contExpandido = null;
            } else {
                if (contExpandido) {
                    const prevIcon = contExpandido.previousElementSibling.querySelector('.desplegar i');
                    contExpandido.classList.remove('expanded');
                    prevIcon.classList.remove('ti-chevron-up');
                    prevIcon.classList.add('ti-chevron-down');
                }

                contDesplegar[index].classList.add('expanded');
                icono.classList.remove('ti-chevron-down');
                icono.classList.add('ti-chevron-up');
                contExpandido = contDesplegar[index];
            }
        });
    });
});
