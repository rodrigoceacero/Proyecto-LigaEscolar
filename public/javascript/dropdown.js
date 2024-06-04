document.addEventListener('DOMContentLoaded', function () {
    const dropdownBoton = document.querySelector('.dropdown-boton');
    const dropdownContenido = document.querySelector('.dropdown-contenido');
    const dropdownFlecha = document.getElementById('dropdown-flecha');

    dropdownBoton.addEventListener('click', function () {
        dropdownContenido.classList.toggle('show');
        dropdownFlecha.classList.toggle('ti-chevron-down');
        dropdownFlecha.classList.toggle('ti-chevron-up');
    });

    window.addEventListener('click', function (e) {
        if (!dropdownBoton.contains(e.target) && !dropdownContenido.contains(e.target)) {
            dropdownContenido.classList.remove('show');
            dropdownFlecha.classList.add('ti-chevron-down');
            dropdownFlecha.classList.remove('ti-chevron-up');
        }
    });
});