/* =========================================================
   MENÚS DE OPCIONES DE LAS IMÁGENES
   ========================================================= */

document.addEventListener('click', function (event) {
    const botonTresPuntos = event.target.closest('.btn-tres-puntos');

    if (botonTresPuntos) {
        const idCheckbox = botonTresPuntos.getAttribute('for');

        document.querySelectorAll('.checkbox-oculto').forEach((checkbox) => {
            if (checkbox.id !== idCheckbox) {
                checkbox.checked = false;
            }
        });

        return;
    }

    if (!event.target.closest('.opciones-container')) {
        document.querySelectorAll('.checkbox-oculto').forEach((checkbox) => {
            checkbox.checked = false;
        });
    }
});