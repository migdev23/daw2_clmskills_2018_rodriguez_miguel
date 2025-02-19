"use strict";
const btnsDelete = document.querySelectorAll('.btnDeletePhoto');
btnsDelete.forEach(btn => {
    btn.addEventListener('click', async function (event) {
        const iid = btn.getAttribute('iid');
        if (iid) {
            const response = await fetch('/deletePhoto', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `iid=${encodeURIComponent(iid)}`
            });
            const { status } = await response.json();
            if (status == 'success') {
                const card = btn.parentNode?.parentNode?.parentNode;
                if (card && card instanceof Element) {
                    card.remove();
                }
            }
            else {
                alert('Fallo al borrarla');
            }
        }
    });
});
//# sourceMappingURL=profileUser.js.map