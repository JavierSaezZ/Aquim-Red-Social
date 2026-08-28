function avatarImage(user) {
    const textoBase = String(user?.name || user?.nick || 'U').trim() || 'U';
    const alt = document.createElement('div');
    alt.textContent = `Avatar de ${textoBase}`;

    if (user?.profile_photo_path) {
        const archivo = String(user.profile_photo_path).split('/').pop();
        return `<div class="user-avatar avatar"><img src="/storage/profile-photos/small/${encodeURIComponent(archivo)}" alt="${alt.innerHTML}"></div>`;
    }

    const letra = Array.from(textoBase)[0].toLocaleUpperCase('es-ES');
    return `<div class="user-avatar avatar"><img class="img-letter" src="/images/avatar-letters-small/${encodeURIComponent(letra)}.webp" alt="${alt.innerHTML}"></div>`;
}

function escapeHtml(text = '') {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/* =========================================================
   CARGA DE COMENTARIOS
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const btnLoadMore = document.getElementById('btnLoadMoreComments');
    const loadMoreContainer = document.getElementById('loadMoreContainer');
    const commentsList = document.querySelector('.comments-list');
    let isLoading = false;

    /* Contexto de usuario y permisos
       --------------------------------------------------------- */

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const authId = window.Laravel && window.Laravel.myUser ? window.Laravel.myUser.id : null;
    const imageOwnerId = window.Laravel && window.Laravel.imageOwnerId ? window.Laravel.imageOwnerId : null;

    /* Paginación y creación de comentarios
       --------------------------------------------------------- */

    if (btnLoadMore) {
        btnLoadMore.addEventListener('click', function () {
            let nextCommentsUrl = btnLoadMore.getAttribute('data-next-url');

            if (isLoading || !nextCommentsUrl) return;

            isLoading = true;
            btnLoadMore.classList.add('loading');

            fetch(nextCommentsUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    data.comments.forEach(comment => {
                        const commentDiv = document.createElement('div');
                        commentDiv.className = 'comment-item';

                        const userNick = comment.user && comment.user.nick ? comment.user.nick : 'usuario-desconocido';
                        const userAvatar = comment.user ? avatarImage(comment.user) : '';
                        const userNickSeguro = escapeHtml(userNick);
                        const commentContent = escapeHtml(comment.content || '').replace(/\n/g, '<br>');

                        // El comentario puede eliminarlo su autor o el propietario de la imagen.
                        const canDelete = authId && (authId == comment.user_id || authId == imageOwnerId);

                        let menuHtml = '';

                        if (canDelete) {


                            menuHtml = `
                                <li>
                                    <form class="action-modal__form" action="/comments/destroycomment/${comment.id}" method="POST">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="item-menu peligro" style="display:block; text-decoration:none;">Eliminar</button>
                                    </form>
                                </li>
                            `;
                        } else {
                            menuHtml = `<li><button class="item-menu">Denunciar</button></li>`;
                        }

                        commentDiv.innerHTML = `
                            <a href="/u/${encodeURIComponent(userNick)}">${userAvatar}</a>
                            <div class="comment-content">
                                <p><a href="/u/${encodeURIComponent(userNick)}"><strong class="nombre-usuario-inline" title="${userNickSeguro}">${userNickSeguro}</strong></a> ${commentContent}</p>
                                <div class="comment-meta">
                                    <span>${comment.time_ago}</span>
                                    <span>Me gusta</span>
                                    <span>Responder</span>

                                    <div class="options">
                                        <div class="opciones-container">
                                            <input type="checkbox" id="menu-toggle-comment-${comment.id}" class="checkbox-oculto">
                                            <label for="menu-toggle-comment-${comment.id}" class="btn-tres-puntos">⋮</label>
                                            <ul class="menu-desplegable">${menuHtml}</ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        commentsList.insertBefore(commentDiv, loadMoreContainer);
                    });

                    let nuevaUrl = data.next_page;

                    if (nuevaUrl) {
                        btnLoadMore.setAttribute('data-next-url', nuevaUrl);
                    } else {
                        loadMoreContainer.style.display = 'none';
                    }

                    btnLoadMore.classList.remove('loading');
                    isLoading = false;
                })
                .catch(error => {
                    console.error('Error cargando más comentarios:', error);
                    btnLoadMore.classList.remove('loading');
                    isLoading = false;
                });
        });
    }
});