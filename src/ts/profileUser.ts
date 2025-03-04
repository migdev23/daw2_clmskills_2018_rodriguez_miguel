(async () => {
    const imagesPerPage: number = 6;
    let currentPage: number = 1;
    let totalImages: number = 0;

    interface Image {
        iid: number;
        titulo: string;
        descripcion: string;
    }

    interface ApiResponse {
        totalImgs: number;
        imgs: Image[];
    }

    async function cargarImagenes(page: number): Promise<void> {
        const container: HTMLElement | null = document.querySelector('#imgsProfile');
        try {
            const response: Response = await fetch(`/api/imgsProfile?page=${page}`);

            if (!response.ok) throw new Error("Error al obtener las imágenes");
            
            const data: ApiResponse = await response.json();
            totalImages = data.totalImgs || 0;
            currentPage = page;
            
            if (container){
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
                } else {
                    container.innerHTML = `<div class='col-4 mb-3'>No se encontraron fotografías.</div>`;
                }
                
                btnDelete(); 
                paginacion();
            }  
        } catch (error: unknown) {
            console.log(error)
            if (container){
                container.innerHTML = `<div class='col-4 mb-3'>No se encontraron fotografías.</div>`;
            }
        }
    }

    function btnDelete(): void {
        document.querySelectorAll<HTMLButtonElement>('.btnDeletePhoto').forEach(button => {
            button.addEventListener('click', async (event: Event) => {
                const target = event.currentTarget as HTMLElement;
                const iid = target.dataset.iid;
                if (iid) {
                    try {
                        const response: Response = await fetch('/deletePhoto', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                            body: `iid=${encodeURIComponent(iid)}`
                        });
                        
                        const { status }: { status: string } = await response.json();
                        if (status === 'success') {
                            target.closest('.col-4')?.remove();
                        } else {
                            alert('Fallo al eliminar la imagen.');
                        }
                    } catch (error: unknown) {
                        console.error("Error eliminando la imagen", error);
                    }
                }
            });
        });
    }

    function paginacion(): void {
        const paginationContainer: HTMLElement | null = document.querySelector('#pagination');
        if (paginationContainer){
            paginationContainer.innerHTML = "";
            const totalPages: number = Math.ceil(totalImages / imagesPerPage);
    
            for (let i = 1; i <= totalPages; i++) {
                const pageItem: HTMLLIElement = document.createElement("li");
                
                pageItem.classList.add("page-item");
                if (i === currentPage) {
                    pageItem.classList.add("active");
                }

                pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                
                pageItem.addEventListener("click", (event: Event) => {
                    event.preventDefault();
                    if (i !== currentPage) cargarImagenes(i);
                });
                
                paginationContainer.appendChild(pageItem);
            }
        }
    }

    cargarImagenes(1);
})();
