"use strict";
const form = document.getElementById('busquedaForm');
if (form) {
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        let isValid = false;
        const campos = ['title', 'category', 'author', 'pageLimit'];
        campos.forEach(campoId => {
            const input = document.getElementById(campoId);
            input.classList.remove('is-invalid');
            input.setCustomValidity('');
        });
        for (const campoId of campos) {
            const field = document.getElementById(campoId);
            if (field.value.trim() !== '') {
                isValid = true;
                break;
            }
        }
        if (!isValid) {
            campos.forEach(campoId => {
                const input = document.getElementById(campoId);
                input.classList.add('is-invalid');
            });
            form.classList.add('was-validated');
            event.preventDefault();
        }
        else {
            form.submit();
        }
    });
}
//# sourceMappingURL=busquedaValidation.js.map