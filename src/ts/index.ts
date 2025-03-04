interface Usuario {
    nombre: string;
}

interface Categoria {
    nombre: string;
}

interface Imagen {
    iid: string;
    titulo: string;
    descripcion: string;
    usuarios: Usuario[];
    categorias: Categoria[];
}

interface ApiResponse {
    imgs: Imagen[];
    totalPages: number;
    currentPage: number;
}

class ImageGallery {
    public currentPage: number;
    public apiUrl: string;
    public category: string;
    public author: string;
    public title: string;
    public limitPage: number;

    constructor(apiUrl: string) {
        const urlParams = new URLSearchParams(window.location.search);
        this.apiUrl = apiUrl;
        this.currentPage = 1;
        this.category = urlParams.get('category') ?? '';
        this.author = urlParams.get('author') ?? '';
        this.title = urlParams.get('title') ?? '';

        const pageLimitParam = urlParams.get('pageLimit');
        this.limitPage = pageLimitParam !== null ? parseInt(pageLimitParam) : 6;

        document.addEventListener("DOMContentLoaded", () => this.fetchImages(this.currentPage));

        this.addPaginationEventListeners();
    }

    async fetchImages(page: number): Promise<void> {
        try {
            const response = await fetch(`${this.apiUrl}?page=${page}&category=${this.category}&author=${this.author}&title=${this.title}&pageLimit=${this.limitPage}`);
            const data: ApiResponse = await response.json();
            this.renderImages(data.imgs);
            this.renderPagination(data.totalPages, data.currentPage);
        } catch (error: unknown) {
            const imagesContainer: HTMLElement | null = document.getElementById("images-container");
            if (imagesContainer) {
                imagesContainer.innerHTML = `<div class='col-4 mb-3'> 
                                                No se encontró ninguna fotografía...
                                            </div>`;
            }
        }
    }

    renderImages(imgs: Imagen[]): void {
        const imagesContainer : HTMLElement | null = document.getElementById("images-container");
        if (imagesContainer) {
            if (imgs.length > 0) {
                imagesContainer.innerHTML = imgs.map(imagen => `
                    <div class='col-4 mb-3'>
                        <div class="card shadow-sm">
                            <img src="/imgIid/${imagen.iid}" width="100%" height="200" class="card-img-top" alt="Imagen de ${imagen.titulo}">
                            <div class="card-body">
                                <h5 class="card-title">${imagen.titulo}</h5>
                                <p class="card-text">${imagen.descripcion}</p>
                                <p class="card-text text-muted">Autor: ${imagen.usuarios.map(u => u.nombre).join(", ")}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Categorías: ${
                                            Array.isArray(imagen.categorias) && imagen.categorias.length > 0 
                                            ? imagen.categorias.map(c => c.nombre).join(", ") 
                                            : "Sin categoría"
                                        }
                                    </small>
                                </p>
                                <a href="/details/${imagen.iid}" class="btn btn-secondary">Ver detalles</a>
                            </div>
                        </div>
                    </div>
                `).join("");
            } else {
                imagesContainer.innerHTML = `<div class='col-4 mb-3'> 
                                                No se encontró ninguna fotografía...
                                            </div>`;
            }
        }
    }

    renderPagination(totalPages: number, currentPage: number): void {
        const paginationContainer: HTMLElement | null = document.getElementById("pagination");
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

    changePage(page: number): void {
        this.fetchImages(page);
    }

    addPaginationEventListeners(): void {
        const paginationContainer: HTMLElement | null = document.getElementById("pagination");
        if (paginationContainer) {
            paginationContainer.addEventListener("click", (event) => {
                const target = event.target as HTMLElement;
                if (target && target.dataset.page) {
                    const page = parseInt(target.dataset.page);
                    this.changePage(page);
                }
            });
        }
    }
}

const gallery = new ImageGallery("/api/imgs");

document.querySelectorAll('.author').forEach(authorElement => {
    authorElement.addEventListener('click', () => {
        const author = authorElement.getAttribute('data-author') || "";
        gallery.author = author;
        gallery.currentPage = 1;
        gallery.limitPage = 6;
        gallery.title = '';
        gallery.fetchImages(gallery.currentPage);

        document.querySelectorAll('.author').forEach(item => item.classList.remove('bg-primary', 'text-white'));
        authorElement.classList.add('bg-primary', 'text-white');
    });
});

document.querySelectorAll('.category').forEach(categoryElement => {
    categoryElement.addEventListener('click', () => {
        const category = categoryElement.getAttribute('data-category') || "";
        gallery.category = category;
        gallery.currentPage = 1;
        gallery.limitPage = 6;
        gallery.title = '';
        gallery.fetchImages(gallery.currentPage);

        document.querySelectorAll('.category').forEach(item => item.classList.remove('bg-primary', 'text-white'));
        categoryElement.classList.add('bg-primary', 'text-white');
    });
});

document.querySelector('#deleteFilter')?.addEventListener('click', () => {
    gallery.category = "";
    gallery.author = "";
    gallery.currentPage = 1;
    gallery.limitPage = 6;
    gallery.title = '';
    document.querySelectorAll('.category').forEach(item => item.classList.remove('bg-primary', 'text-white'));
    document.querySelectorAll('.author').forEach(item => item.classList.remove('bg-primary', 'text-white'));
    gallery.fetchImages(gallery.currentPage);
});
