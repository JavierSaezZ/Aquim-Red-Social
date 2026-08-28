import { avatarImage } from './avatar';

/* =========================================================
   BÚSQUEDA DE USUARIOS
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    const inputBuscador = document.getElementById('inputBuscador');
    const panelResultados = document.getElementById('panelResultados');

    /* Consulta y representación de resultados
       --------------------------------------------------------- */

    inputBuscador.addEventListener('input', function () {
        if (this.value.trim().length > 0) {
            panelResultados.style.display = 'flex';

            const search = this.value;
       

            fetch(`/u/buscador/${search}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {

                    panelResultados.innerHTML = '';

                    // La respuesta puede contener la colección en "data" o devolverla directamente.
                    const resultados = data.data || data;

                    if (resultados && resultados.length > 0) {
                        resultados.forEach(buscador => {
                            panelResultados.innerHTML += `
                                <a href="/u/${buscador.nick}" class="search-item">
                                    <div class="avatar">${avatarImage(buscador)}</div>
                                    <div class="search-item-info">
                                        <span class="search-item-name">${buscador.nick || buscador.name}</span>
                                        <span class="search-item-handle">${buscador.name}</span>
                                    </div>
                                </a>
                            `;
                        });
                    } else {
                        panelResultados.innerHTML = '<a href="#" class="search-item">Usuario no encontrado</a>';
                    }
                });
        } else {
            panelResultados.style.display = 'none';
            panelResultados.innerHTML = '';
        }
    });

    /* Cierre del panel
       --------------------------------------------------------- */

    document.addEventListener('click', function (event) {


        if (!inputBuscador.contains(event.target) && !panelResultados.contains(event.target)) {
            panelResultados.style.display = 'none';
        }
    });
});
