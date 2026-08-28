/* =========================================================
   CONTADORES Y LÍMITES DE TEXTO
   ========================================================= */

document.addEventListener('DOMContentLoaded', () => {
    /* Utilidades comunes
       --------------------------------------------------------- */

    const actualizarContador = (textarea, contador) => {
        if (!contador) return;

        const actual = textarea.value.length;
        const limite = Number(textarea.getAttribute('maxlength')) || 0;

        contador.textContent = limite ? `${actual} / ${limite}` : `${actual}`;
        contador.style.color = limite && limite - actual <= 50 ? '#dc3545' : '#558737';
    };

    const limpiarSaltos = (textarea) => {
        textarea.value = textarea.value
            .replace(/\n{3,}/g, '\n\n')
            .replace(/^\n+/, '')
            .replace(/\n+$/, '');
    };

    /* Descripciones de publicaciones y perfil
       --------------------------------------------------------- */

    const textareasDescripcion = document.querySelectorAll('textarea[id="descriptionEdit"]');

    textareasDescripcion.forEach((textarea) => {
        // El contador se busca en el mismo formulario porque el ID puede repetirse en la página.
        const formulario = textarea.closest('form');
        const contador = formulario
            ? formulario.querySelector('[id="charCountEdit"]')
            : null;

        const esDescripcionPerfil = textarea.classList.contains('profile-description-text');

        if (!esDescripcionPerfil) {
            const actualizar = () => {
                actualizarContador(textarea, contador);
            };

            textarea.addEventListener('input', actualizar);

            textarea.addEventListener('blur', () => {
                limpiarSaltos(textarea);
                actualizar();
            });

            if (formulario) {
                formulario.addEventListener('submit', () => {
                    limpiarSaltos(textarea);
                    actualizar();
                });
            }

            actualizar();
            return;
        }

        /* Descripción del perfil: máximo de cinco líneas visuales
           --------------------------------------------------------- */

        textarea.style.overflowY = 'hidden';
        textarea.style.resize = 'none';

        // La altura inicial del textarea con rows="5" actúa como límite visual.
        const alturaMaxima = textarea.clientHeight;

        let ultimoValorValido = textarea.value;
        let ultimaSeleccionInicio = textarea.selectionStart ?? textarea.value.length;
        let ultimaSeleccionFin = textarea.selectionEnd ?? textarea.value.length;

        const guardarEstadoValido = () => {
            ultimoValorValido = textarea.value;
            ultimaSeleccionInicio = textarea.selectionStart ?? textarea.value.length;
            ultimaSeleccionFin = textarea.selectionEnd ?? textarea.value.length;
        };

        const comprobarLineas = () => {
            // scrollHeight incluye tanto los saltos manuales como las líneas partidas visualmente.
            if (textarea.scrollHeight > alturaMaxima + 2) {
                textarea.value = ultimoValorValido;
                textarea.setSelectionRange(ultimaSeleccionInicio, ultimaSeleccionFin);
            } else {
                guardarEstadoValido();
            }

            actualizarContador(textarea, contador);
        };

        textarea.addEventListener('input', comprobarLineas);

        textarea.addEventListener('blur', () => {
            limpiarSaltos(textarea);
            comprobarLineas();
        });

        if (formulario) {
            formulario.addEventListener('submit', () => {
                limpiarSaltos(textarea);
                comprobarLineas();
            });
        }

        actualizarContador(textarea, contador);
    });

    /* Comentarios con crecimiento automático
       --------------------------------------------------------- */

    const comentarios = document.querySelectorAll('textarea[id="commentText"]');

    comentarios.forEach((commentTextarea) => {
        const formulario = commentTextarea.closest('form');
        const commentCounter = formulario
            ? formulario.querySelector('[id="charCountComment"]')
            : document.querySelector('[id="charCountComment"]');

        if (!commentCounter) return;

        const minHeight = 44;
        const maxHeight = 86;

        const actualizarComentario = () => {
            actualizarContador(commentTextarea, commentCounter);

            commentTextarea.style.height = 'auto';

            const nuevaAltura = Math.max(
                minHeight,
                Math.min(commentTextarea.scrollHeight, maxHeight)
            );

            commentTextarea.style.height = `${nuevaAltura}px`;
            commentTextarea.style.overflowY =
                commentTextarea.scrollHeight > maxHeight ? 'auto' : 'hidden';
        };

        const limpiarComentario = () => {
            limpiarSaltos(commentTextarea);
            actualizarComentario();
        };

        commentTextarea.addEventListener('input', actualizarComentario);
        commentTextarea.addEventListener('blur', limpiarComentario);

        if (formulario) {
            formulario.addEventListener('submit', limpiarComentario);
        }

        actualizarComentario();
    });
});
