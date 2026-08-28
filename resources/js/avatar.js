/* =========================================================
   AVATAR DEL USUARIO
   ========================================================= */

export function avatarImage(usuario) {
    if (!usuario.profile_photo_path) {
        const textoBase = (usuario.nick || usuario.name || 'U').trim();

        const letra = textoBase
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .slice(0, 1)
            .toUpperCase();

        const letraAvatar = /^[A-Z]$/.test(letra) ? letra : 'U';

        return `<img class="img-letter" src="/images/avatar-letters-small/${letraAvatar}.webp" alt="Avatar">`;
    }

    return `<img src="${usuario.profile_photo_url}" alt="Avatar">`;
}