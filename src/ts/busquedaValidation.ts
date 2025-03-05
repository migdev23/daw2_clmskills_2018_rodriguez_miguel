const form = document.getElementById('busquedaForm') as HTMLFormElement;

if (form) {
    form.addEventListener('submit', function (event: Event): void {
        event.preventDefault();
        let isValid: Boolean = false;
        
        const campos: string[] = ['title', 'category', 'author', 'pageLimit'];
        
        campos.forEach((campoId: string) => {
            const input = document.getElementById(campoId) as HTMLInputElement;
            input.classList.remove('is-invalid', 'is-valid');
            input.setCustomValidity('');
        });

        for (const campoId of campos) {
            const field = document.getElementById(campoId) as HTMLInputElement;
            if (field.value.trim() !== '') {
                isValid = true;
                break;
            }
        }

        if (!isValid) {
            campos.forEach((campoId: string) => {
                const input = document.getElementById(campoId) as HTMLInputElement;
                input.setCustomValidity('Rellen algun campo');
                input.classList.add('is-invalid');
            });
            
            form.classList.add('was-validated');
            event.preventDefault();
        } else {
            form.submit();
        }
    });
}