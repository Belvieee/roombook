// Petit script : empeche de choisir une date passee et confirme les actions sensibles
document.querySelectorAll('input[type=date].no-past').forEach(function (input) {
    input.min = new Date().toISOString().split('T')[0];
});

document.querySelectorAll('[data-confirm]').forEach(function (element) {
    element.addEventListener('click', function (event) {
        if (!confirm(element.getAttribute('data-confirm'))) {
            event.preventDefault();
        }
    });
});
