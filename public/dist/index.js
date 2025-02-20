"use strict";
let currentPage = 1;
const apiUrl = "/api/imgs";
async function fetchImages(page) {
    try {
        const response = await fetch(`${apiUrl}?page=${page}`);
        const data = await response.json();
        console.log(data);
        renderImages(data.imgs);
        renderPagination(data.totalPages, data.currentPage);
    }
    catch (error) {
        console.error("Error al obtener imágenes:", error);
    }
}
function renderImages(imgs) {
    const imagesContainer = document.getElementById("images-container");
    if (imagesContainer) {
        imagesContainer.innerHTML = imgs.map(imagen => `
            <div class='col-4 mb-3'>
                <div class="card shadow-sm">
                    <img src="/imgIid/${imagen.iid}" width="100%" height="200" class="card-img-top" alt="Imagen de ${imagen.titulo}">
                    <div class="card-body">
                        <h5 class="card-title">${imagen.titulo}</h5>
                        <p class="card-text">${imagen.descripcion}</p>
                        <p class="card-text text-muted">Autor: ${imagen.usuarios.map((u) => u.nombre).join(", ")}</p>
                        <p class="card-text">
                            <small class="text-muted">
                                Categorías: ${Array.isArray(imagen.categorias) && imagen.categorias.length > 0
            ? imagen.categorias.map((c) => c.nombre).join(", ")
            : "Sin categoría"}
                            </small>
                        </p>
                        <a href="/details/${imagen.iid}" class="btn btn-secondary">Ver detalles</a>
                    </div>
                </div>
            </div>
        `).join("");
    }
}
function renderPagination(totalPages, currentPage) {
    const paginationContainer = document.getElementById("pagination");
    if (paginationContainer) {
        let paginationHTML = "";
        if (currentPage > 1) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Anterior</a></li>`;
        }
        for (let i = 1; i <= totalPages; i++) {
            paginationHTML += `<li class="page-item ${i === currentPage ? "active" : ""}">
                <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
            </li>`;
        }
        if (currentPage < totalPages) {
            paginationHTML += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Siguiente</a></li>`;
        }
        paginationContainer.innerHTML = paginationHTML;
    }
}
function changePage(page) {
    fetchImages(page);
}
document.addEventListener("DOMContentLoaded", () => fetchImages(currentPage));
//# sourceMappingURL=index.js.map