function toggleExpand(e) {
    const link = e.currentTarget;
    const contDisplay = link.closest('.contenedor-expandir').querySelector('.contenedor-desplegable');
    const icon = link.querySelector('i');
    let contExpanded = document.querySelector('.contenedor-desplegable.expanded');

    if (e.target.closest('.boton')) {
        return;
    }

    e.preventDefault();

    if (contExpanded === contDisplay) {
        contDisplay.classList.remove('expanded');
        icon.classList.remove('ti-chevron-up');
        icon.classList.add('ti-chevron-down');
        contExpanded = null;
    } else {
        if (contExpanded) {
            const prevIcon = contExpanded.previousElementSibling.querySelector('.desplegar i');
            contExpanded.classList.remove('expanded');
            prevIcon.classList.remove('ti-chevron-up');
            prevIcon.classList.add('ti-chevron-down');
        }

        contDisplay.classList.add('expanded');
        icon.classList.remove('ti-chevron-down');
        icon.classList.add('ti-chevron-up');
        contExpanded = contDisplay;
    }
}

function attachExpandListeners() {
    const contExpansible = document.querySelectorAll('.desplegar');
    contExpansible.forEach((link) => {
        link.removeEventListener('click', toggleExpand);
        link.addEventListener('click', toggleExpand);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    attachExpandListeners();
});
