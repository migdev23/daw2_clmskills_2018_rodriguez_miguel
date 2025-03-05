const form = document.getElementById('busquedaForm') as HTMLFormElement;

if (form) {
    form.addEventListener('submit', function (event: Event): void {
        event.preventDefault();
        let isValid = false;
        
        const campos: string[] = ['title', 'category', 'author', 'pageLimit'];
        
        campos.forEach(campoId => {
            const input = document.getElementById(campoId) as HTMLInputElement;
            input.classList.remove('is-invalid');
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
            campos.forEach(campoId => {
                const input = document.getElementById(campoId) as HTMLInputElement;
                input.classList.add('is-invalid');
            });
            
            form.classList.add('was-validated');
            event.preventDefault();
        } else {
            form.submit();
        }
    });
}