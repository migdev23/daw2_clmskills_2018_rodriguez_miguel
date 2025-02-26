"use strict";
/*
    * Este script está diseñado para detectar cuándo un usuario intenta cerrar una pestaña o el navegador,
    * y realiza una solicitud para cerrar sesión en el servidor. Esto se logra utilizando el almacenamiento
    * compartido entre pestañas (localStorage) y los eventos de los navegadores.
    *
    * El proceso funciona de la siguiente manera:
    *
    * 1. **Antes de cerrar la pestaña** (`beforeunload`):
    *    - Cuando el usuario intenta cerrar o recargar la pestaña, el valor `isBrowserClosing` se establece en `localStorage`,
    *      indicando que el navegador está a punto de cerrarse.
    *    - Este valor se elimina después de un breve retraso para permitir que otras pestañas detecten el cambio.
    *
    * 2. **Detección del cambio en otras pestañas** (`storage`):
    *    - El evento `storage` se dispara en todas las pestañas cuando el valor de `isBrowserClosing` cambia en `localStorage`.
    *    - Si alguna pestaña detecta que otra pestaña está cerrando (cuando `isBrowserClosing` se establece en `true`),
    *      entonces realiza la accion deseada`.
    *
    * 3. **Cuando la página se oculta o recarga** (`pagehide`):
    *    - Si el valor `isBrowserClosing` está presente en `localStorage`, indica que el navegador o pestaña está cerrándose,
    *      y se realiza la solicitud de cierre de sesión a `/logout` para finalizar la sesión antes de que la página sea descargada.
    *
    * La clave de esta implementación es la sincronización de pestañas mediante el uso de `localStorage`, que permite que
    * las pestañas compartan el estado de si el navegador se está cerrando, y que la lógica de cierre de sesión se ejecute
    * en todas las pestañas relevantes.
    *
    * Nota importante: La opción `keepalive: true` en la solicitud `fetch` asegura que la petición de cierre de sesión se
    * complete, incluso si el navegador está cerrado, evitando que se cancele durante este proceso.
*/
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
//# sourceMappingURL=global.js.map