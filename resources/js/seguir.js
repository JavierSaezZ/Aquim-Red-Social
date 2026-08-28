/* =========================================================
   SEGUIMIENTO DE USUARIOS
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-seguir').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            const boton = e.currentTarget;
            const idUser = window.Laravel.visitedUser.id;
            const idMyUser = window.Laravel.myUser.id;

            // El servidor decide si la acción debe seguir o dejar de seguir al usuario.
            fetch(`/u/follow/${idMyUser}/${idUser}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    // Se mantienen ambos pares de estados por compatibilidad con el controlador.
                    if (data.status === 'followed' || data.status === 'added') {
                        boton.textContent = 'Siguiendo';
                        boton.classList.add('follow');
                    } else if (data.status === 'unfollowed' || data.status === 'removed') {
                        boton.textContent = 'Seguir';
                        boton.classList.remove('follow');
                    }
                })
                .catch(error => {
                    console.error('Error de red:', error);
                });
        });
    });
});
