"use strict";
(() => {
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach((form) => {
        const password = form.querySelector('#password');
        const repassword = form.querySelector('#repassword');
        const validarPassword = () => {
            if (password && repassword) {
                if (password.value !== repassword.value) {
                    repassword.setCustomValidity('Las contraseñas no coinciden.');
                }
                else {
                    repassword.setCustomValidity('');
                }
            }
        };
        if (password && repassword) {
            password.addEventListener('input', validarPassword);
            repassword.addEventListener('input', validarPassword);
        }
        form.addEventListener('submit', (event) => {
            validarPassword();
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
//# sourceMappingURL=registerValidation.js.map