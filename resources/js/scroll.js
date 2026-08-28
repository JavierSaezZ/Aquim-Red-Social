/* =========================================================
   CARGA INFINITA DEL FEED
   ========================================================= */

// La posición inicial debe depender del contenedor del feed, no del historial del navegador.
if ('scrollRestoration' in history) history.scrollRestoration = 'manual';

document.addEventListener('DOMContentLoaded', function () {
    let nextPageUrl = window.nextPageUrl;

    const feed = document.querySelector('.feed');
    const loading = document.getElementById('loading');
    const scroller = document.querySelector('.frutiger-theme');

    let isLoading = false;

    /* Validación e inicialización
       --------------------------------------------------------- */

    if (!scroller) {
        console.error('No se encontró .frutiger-theme');
        return;
    }

    if (!feed) {
        console.error('No se encontró .feed');
        return;
    }

    if (!loading) {
        console.error('No se encontró #loading');
        return;
    }

    scroller.scrollTop = 0;

    /* Formato seguro del contenido
       --------------------------------------------------------- */

    function escapeHtml(text = '') {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

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

    function avatarImage(user) {
        const textoBase = String(user?.name || user?.nick || 'U').trim() || 'U';
        const alt = escapeHtml(`Avatar de ${textoBase}`);

        if (user?.profile_photo_path) {
            const archivo = String(user.profile_photo_path).split('/').pop();
            return `<div class="user-avatar avatar"><img src="/storage/profile-photos/small/${encodeURIComponent(archivo)}" alt="${alt}"></div>`;
        }

        const letra = Array.from(textoBase)[0].toLocaleUpperCase('es-ES');
        return `<div class="user-avatar avatar"><img class="img-letter" src="/images/avatar-letters-small/${encodeURIComponent(letra)}.webp" alt="${alt}"></div>`;
    }

    function fechaRelativa(fecha) {
        const ahora = new Date();
        const creada = new Date(fecha);
        const segundos = Math.floor((ahora - creada) / 1000);

        if (segundos < 60) return 'hace unos segundos';

        const minutos = Math.floor(segundos / 60);
        if (minutos < 60) return `hace ${minutos} ${minutos === 1 ? 'minuto' : 'minutos'}`;

        const horas = Math.floor(minutos / 60);
        if (horas < 24) return `hace ${horas} ${horas === 1 ? 'hora' : 'horas'}`;

        const dias = Math.floor(horas / 24);
        if (dias < 30) return `hace ${dias} ${dias === 1 ? 'día' : 'días'}`;

        const meses = Math.floor(dias / 30);
        if (meses < 12) return `hace ${meses} ${meses === 1 ? 'mes' : 'meses'}`;

        const años = Math.floor(dias / 365);
        return `hace ${años} ${años === 1 ? 'año' : 'años'}`;
    }

    /* Detección del final y carga de publicaciones
       --------------------------------------------------------- */

    function checkScroll() {
        const cercaDelFinal = scroller.scrollTop + scroller.clientHeight >= scroller.scrollHeight - 500;

        if (cercaDelFinal && !isLoading && nextPageUrl) loadMorePhotos();
    }

    scroller.addEventListener('scroll', checkScroll, { passive: true });

    function loadMorePhotos() {
        isLoading = true;
        loading.classList.add('is-visible');

        fetch(nextPageUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
            .then(response => {
                if (!response.ok) throw new Error(`Error HTTP ${response.status}`);
                return response.json();
            })
            .then(data => {
                const pixelExacto = scroller.scrollTop;

                data.images.forEach(image => {
                    const article = document.createElement('article');
                    article.className = 'post-card';

                    const userName = image.user?.name || 'Usuario';
                    const userNick = image.user?.nick || 'usuario-desconocido';
                    const likesCount = formatearCantidad(Number(image.likes_count) || 0);
                    const commentsCount = image.comments_count || 0;
                    const fecha = fechaRelativa(image.created_at);

                    const avatar = avatarImage(image.user);

                    let descripcion = '';

                    if (image.description) {
                        const lineas = image.description.split('\n');
                        const primeraLinea = escapeHtml(lineas[0]);
                        descripcion = `<div class="photo-caption">${primeraLinea}${lineas.length > 1 ? '...' : ''}</div>`;
                    }

                    article.innerHTML = `
                        <div class="post-inner">
                            <header class="post-header">
                                <div class="author">
                                    <a href="/u/${encodeURIComponent(userNick)}">${avatar}</a>
                                    <div class="nombre-usuario-contenedor">
                                        <strong class="nombre-usuario-recortado"><a href="/u/${encodeURIComponent(userNick)}">${escapeHtml(userNick)}</a></strong>
                                        <span>${fecha}</span>
                                    </div>
                                </div>
                            </header>

                            <div class="photo-frame">
                                <a href="/images/${image.id}">
                                    <img src="/storage/images/feed/${encodeURIComponent(image.image_path)}" class="imagen-real" alt="Imagen del feed" decoding="async" loading="lazy">
                                </a>
                                ${descripcion}
                            </div>

                            <div class="post-actions">
                                <div class="left-actions">
                                    <button class="glass-button btn-like btn-imagen ${image.has_liked ? 'liked' : ''}" data-id="${image.id}">
                                        <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 21.35 10.55 20.03C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09 C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5 c0 3.78-3.4 6.86-8.55 11.54L12 21.35Z" />
                                        </svg>
                                    </button>

                                    <span class="numero numerolike">${likesCount}</span>

                                    <button class="glass-button btn-imagen" title="Comentarios" aria-label="Comentarios">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none" aria-hidden="true">
                                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                                        </svg>
                                    </button>

                                    <span class="numero">${commentsCount}</span>

                                    <button class="glass-button btn-imagen" title="Compartir" aria-label="Compartir">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none" aria-hidden="true">
                                            <path d="M14 5.25c0-.67.77-1.05 1.3-.63l6.75 5.4a.8.8 0 0 1 0 1.26l-6.75 5.4c-.53.42-1.3.04-1.3-.63v-2.78c-3.26.08-5.85.67-7.94 1.92-2.16 1.29-3.84 3.3-5.31 6.33-.27.56-1.12.38-1.14-.24-.15-4.33 1.02-7.82 3.48-10.27C5.64 8.5 9.22 7.32 14 7.13V5.25Z"></path>
                                        </svg>
                                    </button>
                                </div>

                                <div class="right-actions">
                                    <button class="glass-button btn-imagen" title="Guardar" aria-label="Guardar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none" aria-hidden="true">
                                            <path d="M7 3.25A2.75 2.75 0 0 0 4.25 6v14.878c0 .745.84 1.18 1.447.75L12 17.414l6.303 4.214c.607.406 1.447-.005 1.447-.75V6A2.75 2.75 0 0 0 17 3.25H7Z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    feed.insertBefore(article, loading);
                });

                nextPageUrl = data.next_page;
                loading.classList.remove('is-visible');

                if (!nextPageUrl) {
                    loading.innerHTML = 'No hay más publicaciones.';
                    loading.classList.add('is-visible');
                }

                scroller.scrollTop = pixelExacto;

                // Evita encadenar solicitudes mientras el navegador estabiliza el contenido nuevo.
                setTimeout(() => {
                    isLoading = false;
                    checkScroll();
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                isLoading = false;
                loading.classList.remove('is-visible');
            });
    }
});