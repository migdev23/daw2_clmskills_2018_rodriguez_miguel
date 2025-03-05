(() => {
    const forms: NodeListOf<HTMLFormElement> = document.querySelectorAll('.needs-validation');

    forms.forEach((form: HTMLFormElement) => {
        form.addEventListener('submit', (event: Event) => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
