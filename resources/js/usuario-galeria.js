/* =========================================================
   GALERÍA DEL USUARIO
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const galleryGrid = document.getElementById('galleryGrid');
    const btnLoadMore = document.getElementById('btnLoadMoreGallery');
    const loadMoreContainer = document.getElementById('loadMoreGalleryContainer');

    if (!galleryGrid || !btnLoadMore) {
        return;
    }

    let isLoading = false;

    const galleryUrl = btnLoadMore.dataset.galleryUrl;
    const loginUrl = btnLoadMore.dataset.loginUrl;
    const isAuthenticated = btnLoadMore.dataset.authenticated === '1';

    /* Carga paginada
       --------------------------------------------------------- */

    btnLoadMore.addEventListener('click', async function () {
        if (!isAuthenticated) {
            window.location.href = loginUrl;
            return;
        }

        const nextOffset = btnLoadMore.dataset.nextOffset;

        if (isLoading || !nextOffset) {
            return;
        }

        isLoading = true;
        btnLoadMore.disabled = true;
        btnLoadMore.classList.add('loading');

        try {
            const url = new URL(galleryUrl, window.location.origin);
            url.searchParams.set('gallery_offset', nextOffset);

            const response = await fetch(url.toString(), {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            // Redirige también cuando una sesión válida al abrir la página caduca después.
            if (response.status === 401) {
                window.location.href = data.login_url || loginUrl;
                return;
            }

            if (!response.ok) {
                throw new Error(data.message || 'No se pudieron cargar más publicaciones.');
            }

            data.images.forEach(function (image) {
                galleryGrid.insertAdjacentHTML('beforeend', createGalleryItem(image));
            });

            if (data.has_more && data.next_offset !== null) {
                btnLoadMore.dataset.nextOffset = data.next_offset;
            } else {
                if (loadMoreContainer) {
                    loadMoreContainer.remove();
                }
            }
        } catch (error) {
            console.error('Error cargando más publicaciones:', error);
        } finally {
            isLoading = false;

            if (document.body.contains(btnLoadMore)) {
                btnLoadMore.disabled = false;
                btnLoadMore.classList.remove('loading');
            }
        }
    });
});


/* Creación de elementos de la galería
   --------------------------------------------------------- */

function formatearCantidad(numero = 0) {
    if (numero >= 1000000) {
        return `${parseFloat((numero / 1000000).toFixed(1)).toLocaleString('es-ES')} M`;
    }

    if (numero >= 1000) {
        const decimales = numero < 10000 ? 1 : 0;
        return `${parseFloat((numero / 1000).toFixed(decimales)).toLocaleString('es-ES')} mil`;
    }

    return String(numero);
}

function createGalleryItem(image) {
    return `
        <article class="photo-frame">
            <a href="${image.detail_url}">
                <img src="${image.image_url}" class="imagen-real" alt="Imagen del feed">
                <div class="photo-overlay">
                    <div class="overlay-stat">❤︎ ${formatearCantidad(Number(image.likes_count) || 0)}</div>
                    <div class="overlay-stat">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5 a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9 L3 21 l1.9-5.7 a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9 h.5 a8.48 8.48 0 0 1 8 8 v.5z"></path>
                        </svg>
                        ${formatearCantidad(Number(image.comments_count) || 0)}
                    </div>
                </div>
            </a>
        </article>
    `;
}