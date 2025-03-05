(() => {
    const forms: NodeListOf<HTMLFormElement> = document.querySelectorAll('.needs-validation');

    forms.forEach((form: HTMLFormElement) => {
        const password: HTMLInputElement | null = form.querySelector('#password');
        const repassword: HTMLInputElement | null = form.querySelector('#repassword');

        const validarPassword = () => {
            if (password && repassword) {
                if (password.value !== repassword.value) {
                    repassword.setCustomValidity('Las contraseñas no coinciden.');
                } else {
                    repassword.setCustomValidity('');
                }
            }
        };

        if (password && repassword) {
            password.addEventListener('input', validarPassword);
            repassword.addEventListener('input', validarPassword);
        }

        form.addEventListener('submit', (event: Event) => {
            validarPassword();

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
