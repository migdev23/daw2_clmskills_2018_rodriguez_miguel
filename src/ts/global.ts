window.addEventListener('beforeunload', function (event) {
    localStorage.setItem('isBrowserClosing', 'true');

    setTimeout(() => {
        localStorage.removeItem('isBrowserClosing');
    }, 100);
});


window.addEventListener('storage', function (event) {
    if (event.key === 'isBrowserClosing' && event.newValue === 'true') {
        fetch('/logoutCloseNavegation', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            keepalive: true 
        });
    }
});


window.addEventListener('pagehide', function (event) {
    if (localStorage.getItem('isBrowserClosing') === 'true') {
        fetch('/logoutCloseNavegation', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            keepalive: true 
        });
    }
});

//COOKIES

const crearCookie = (name: string, value: string, days?: number): void => {
    let expires: string = "";
    if (days) {
        const date: Date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = `${name}=${value}${expires}; path=/`;
}

const obtenerCookie = (name: string): string | null => {
    const nameEQ: string = `${name}=`;
    const ca: string[] = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c: string = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

document.addEventListener("DOMContentLoaded", () => {
    if (!obtenerCookie("aceptada")) {
        const cookieModal = new bootstrap.Modal(document.getElementById('cookieModal') as HTMLElement);
        cookieModal.show();

        const acceptCookiesButton = document.getElementById("acceptCookies") as HTMLElement;
        acceptCookiesButton.addEventListener("click", () => {
            crearCookie("aceptada", "true", 365);
            cookieModal.hide();
        });
    }
});