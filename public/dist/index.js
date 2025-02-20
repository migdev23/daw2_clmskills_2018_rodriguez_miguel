"use strict";
class ImageGallery {
    constructor(apiUrl) {
        this.apiUrl = apiUrl;
        this.currentPage = 1;
        this.category = '';
        this.author = '';
        document.addEventListener("DOMContentLoaded", () => this.fetchImages(this.currentPage));
        this.addPaginationEventListeners();
    }
    async fetchImages(page) {
        try {
            const response = await fetch(`${this.apiUrl}?page=${page}&category=${this.category}&author=${this.author}`);
            const data = await response.json();
            console.log(data);
            this.renderImages(data.imgs);
            this.renderPagination(data.totalPages, data.currentPage);
        }
        catch (error) {
            console.error("Error al obtener imágenes:", error);
        }
    }
    renderImages(imgs) {
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
    renderPagination(totalPages, currentPage) {
        const paginationContainer = document.getElementById("pagination");
        if (paginationContainer) {
            let paginationHTML = "";
            if (currentPage > 1) {
                paginationHTML += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage - 1}">Anterior</a></li>`;
            }
            for (let i = 1; i <= totalPages; i++) {
                paginationHTML += `<li class="page-item ${i === currentPage ? "active" : ""}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>`;
            }
            if (currentPage < totalPages) {
                paginationHTML += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPage + 1}">Siguiente</a></li>`;
            }
            paginationContainer.innerHTML = paginationHTML;
        }
    }
    // Método para cambiar de página
    changePage(page) {
        this.fetchImages(page);
    }
    addPaginationEventListeners() {
        const paginationContainer = document.getElementById("pagination");
        if (paginationContainer) {
            paginationContainer.addEventListener("click", (event) => {
                const target = event.target;
                if (target && target.dataset.page) {
                    const page = parseInt(target.dataset.page, 10);
                    this.changePage(page);
                }
            });
        }
    }
}
const gallery = new ImageGallery("/api/imgs");
document.querySelectorAll('.author').forEach(authorElement => {
    authorElement.addEventListener('click', (event) => {
        const author = authorElement.getAttribute('data-author') || "";
        gallery.author = author;
        gallery.currentPage = 1;
        gallery.fetchImages(gallery.currentPage);
        document.querySelectorAll('.author').forEach(item => item.classList.remove('bg-primary', 'text-white'));
        authorElement.classList.add('bg-primary', 'text-white');
    });
});
document.querySelectorAll('.category').forEach(categoryElement => {
    categoryElement.addEventListener('click', (event) => {
        const category = categoryElement.getAttribute('data-category') || "";
        gallery.category = category;
        gallery.currentPage = 1;
        gallery.fetchImages(gallery.currentPage);
        document.querySelectorAll('.category').forEach(item => item.classList.remove('bg-primary', 'text-white'));
        categoryElement.classList.add('bg-primary', 'text-white');
    });
});
document.querySelector('#deleteFilter')?.addEventListener('click', () => {
    gallery.category = "";
    gallery.author = "";
    gallery.currentPage = 1;
    document.querySelectorAll('.category').forEach(item => item.classList.remove('bg-primary', 'text-white'));
    document.querySelectorAll('.author').forEach(item => item.classList.remove('bg-primary', 'text-white'));
    gallery.fetchImages(gallery.currentPage);
});
//# sourceMappingURL=index.js.map