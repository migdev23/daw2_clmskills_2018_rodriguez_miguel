"use strict";
(async () => {
    const imagesPerPage = 6;
    let currentPage = 1;
    let totalImages = 0;
    async function cargarImagenes(page) {
        const container = document.querySelector('#imgsProfile');
        try {
            const response = await fetch(`/api/imgsProfile?page=${page}`);
            if (!response.ok)
                throw new Error("Error al obtener las imágenes");
            const data = await response.json();
            totalImages = data.totalImgs || 0;
            currentPage = page;
            if (container) {
                container.innerHTML = "";
                if (data.imgs.length > 0) {
                    data.imgs.forEach((imagen) => {
                        const card = document.createElement("div");
                        card.className = "col-4 mb-3";
                        card.innerHTML = `
                            <div class="card shadow-sm">
                                <img src="/imgIid/${imagen.iid}" width="100%" height="200" class="card-img-top" alt="Imagen de ${imagen.titulo}">
                                <div class="card-body">
                                    <h5 class="card-title">${imagen.titulo}</h5>
                                    <p class="card-text">${imagen.descripcion}</p>
                                    <button class='btn btn-danger btnDeletePhoto' data-iid='${imagen.iid}'>Eliminar Foto</button>
                                    <a class='btn btn-secondary' href='/details/${imagen.iid}'>Ver detalles</a>
                                </div>
                            </div>
                        `;
                        container.appendChild(card);
                    });
                }
                else {
                    container.innerHTML = `<div class='col-4 mb-3'>No se encontraron fotografías.</div>`;
                }
                btnDelete();
                paginacion();
            }
        }
        catch (error) {
            console.log(error);
            if (container) {
                container.innerHTML = `<div class='col-4 mb-3'>No se encontraron fotografías.</div>`;
            }
        }
    }
    function btnDelete() {
        document.querySelectorAll('.btnDeletePhoto').forEach(button => {
            button.addEventListener('click', async (event) => {
                const target = event.currentTarget;
                const iid = target.dataset.iid;
                if (iid) {
                    try {
                        const response = await fetch('/deletePhoto', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `iid=${encodeURIComponent(iid)}`
                        });
                        const { status } = await response.json();
                        if (status === 'success') {
                            target.closest('.col-4')?.remove();
                        }
                        else {
                            alert('Fallo al eliminar la imagen.');
                        }
                    }
                    catch (error) {
                        console.error("Error eliminando la imagen", error);
                    }
                }
            });
        });
    }
    function paginacion() {
        const paginationContainer = document.querySelector('#pagination');
        if (paginationContainer) {
            paginationContainer.innerHTML = "";
            const totalPages = Math.ceil(totalImages / imagesPerPage);
            for (let i = 1; i <= totalPages; i++) {
                const pageItem = document.createElement("li");
                pageItem.classList.add("page-item");
                if (i === currentPage) {
                    pageItem.classList.add("active");
                }
                pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                pageItem.addEventListener("click", (event) => {
                    event.preventDefault();
                    if (i !== currentPage)
                        cargarImagenes(i);
                });
                paginationContainer.appendChild(pageItem);
            }
        }
    }
    cargarImagenes(1);
})();
//# sourceMappingURL=profileUser.js.map