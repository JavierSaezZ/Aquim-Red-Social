/* =========================================================
   ME GUSTA DE PUBLICACIONES
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    function obtenerCantidad(texto = '0') {
        const textoLimpio = texto.trim().toLowerCase().replace(/\s/g, '').replace(/\./g, '').replace(',', '.');
        const numero = parseFloat(textoLimpio) || 0;

        if (textoLimpio.endsWith('mil')) return Math.round(numero * 1000);
        if (textoLimpio.endsWith('m')) return Math.round(numero * 1000000);

        return Math.round(numero);
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

    // La delegación permite gestionar también las publicaciones añadidas dinámicamente.
    document.addEventListener('click', function (e) {
        const btnLike = e.target.closest('.btn-like');

        if (!btnLike) return;

        e.preventDefault();

        const idUser = window.Laravel?.myUser?.id;

        if (!idUser) return;



        btnLike.classList.toggle('liked');

        const contador = btnLike.parentElement.querySelector('.numerolike');

        if (contador) {
            const numeroActual = obtenerCantidad(contador.textContent);
            const idImage = btnLike.getAttribute('data-id');

            if (btnLike.classList.contains('liked')) {
                contador.textContent = formatearCantidad(numeroActual + 1);


                fetch(`/likes/${idUser}/${idImage}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })

            } else {
                contador.textContent = formatearCantidad(Math.max(0, numeroActual - 1));
              

                fetch(`/likes/${idUser}/${idImage}`, {
                    // La ruta actual gestiona tanto la activación como la desactivación.
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                    .then(response => response.json())

            }
        }
    });
});