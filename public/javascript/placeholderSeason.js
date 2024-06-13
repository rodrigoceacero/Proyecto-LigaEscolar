document.addEventListener('DOMContentLoaded', function() {
    const element = document.querySelector('.form-select-temporadas');
    if (element) {
        const choices = new Choices(element, {
            placeholderValue: 'Selecciona una o varias temporadas',
            removeItemButton: true,
        });
    }
});