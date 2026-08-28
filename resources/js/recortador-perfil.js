/* =========================================================
   RECORTADOR DE IMAGEN DE PERFIL
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {


    const modalPerfil = document.getElementById('profileImageModal');
    const inputPerfil = document.getElementById('input-imagen-perfil');
    const imagenPerfil = document.getElementById('imagen-a-recortar-perfil');
    const contenedorPerfil = document.getElementById('contenedor-crop-perfil');
    const botonAplicar = document.getElementById('btn-aplicar-perfil');
    const nombreImagen = document.getElementById('nombre-imagen-perfil');

    if (
        !modalPerfil ||
        !inputPerfil ||
        !imagenPerfil ||
        !contenedorPerfil ||
        !botonAplicar
    ) {
        console.error('Faltan elementos del recortador de perfil');
        return;
    }

    let cropperPerfil = null;
    let urlImagenPerfil = null;

    /* Limpieza de recursos y estado
       --------------------------------------------------------- */

    function destruirCropper() {
        if (cropperPerfil) {
            cropperPerfil.destroy();
            cropperPerfil = null;
        }
    }

    function liberarUrl() {
        if (urlImagenPerfil) {
            URL.revokeObjectURL(urlImagenPerfil);
            urlImagenPerfil = null;
        }
    }

    function limpiarRecortador() {
        destruirCropper();
        liberarUrl();

        inputPerfil.value = '';
        imagenPerfil.onload = null;
        imagenPerfil.onerror = null;
        imagenPerfil.removeAttribute('src');

        contenedorPerfil.style.display = 'none';
        contenedorPerfil.style.visibility = 'hidden';

        botonAplicar.style.display = 'none';
        botonAplicar.disabled = false;

        if (nombreImagen) {
            nombreImagen.textContent = 'PNG o WebP';
        }
    }

    /* Selección e inicialización de la imagen
       --------------------------------------------------------- */

    inputPerfil.addEventListener('change', function () {


        const archivo = inputPerfil.files && inputPerfil.files[0];

        if (!archivo) {
            return;
        }

        if (!archivo.type.startsWith('image/')) {
            limpiarRecortador();
            alert('El archivo seleccionado no es una imagen válida.');
            return;
        }

        destruirCropper();
        liberarUrl();

        if (nombreImagen) {
            nombreImagen.textContent = archivo.name;
        }

        urlImagenPerfil = URL.createObjectURL(archivo);

        // Cropper necesita un contenedor visible para calcular correctamente sus medidas.
        contenedorPerfil.style.display = 'block';
        contenedorPerfil.style.visibility = 'visible';

        botonAplicar.style.display = 'none';
        botonAplicar.disabled = false;

        imagenPerfil.onload = function () {


            if (typeof Cropper === 'undefined') {
                console.error('Cropper no está cargado');
                alert('Cropper no está cargado en la página.');
                return;
            }

            destruirCropper();

            // Dos frames permiten que el modal y el contenedor terminen de redimensionarse.
            requestAnimationFrame(function () {
                requestAnimationFrame(function () {
                    cropperPerfil = new Cropper(imagenPerfil, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        responsive: true,
                        autoCropArea: 1,
                        background: false,

                        ready() {
                            contenedorPerfil.style.display = 'block';
                            contenedorPerfil.style.visibility = 'visible';
                            botonAplicar.style.display = 'inline-flex';
                        }
                    });
                });
            });
        };

        imagenPerfil.onerror = function () {
            limpiarRecortador();
            alert('No se ha podido cargar la imagen seleccionada.');
        };

        imagenPerfil.src = urlImagenPerfil;
    });

    /* Generación de la imagen recortada
       --------------------------------------------------------- */

    botonAplicar.addEventListener('click', function (evento) {
        evento.preventDefault();

        if (!cropperPerfil || botonAplicar.disabled) {
            return;
        }

        botonAplicar.disabled = true;

        const canvas = cropperPerfil.getCroppedCanvas({
            width: 512,
            height: 512,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        if (!canvas) {
            botonAplicar.disabled = false;
            return;
        }

        canvas.toBlob(
            function (blob) {
                if (!blob) {
                    botonAplicar.disabled = false;
                    return;
                }

                const archivoPerfil = new File(
                    [blob],
                    'foto_perfil.png',
                    {
                        type: 'image/png',
                        lastModified: Date.now()
                    }
                );

                window.dispatchEvent(
                    new CustomEvent('imagen-perfil-lista', {
                        detail: {
                            file: archivoPerfil,
                            blob: blob
                        }
                    })
                );
            },
            'image/png',
            0.9
        );
    });

    /* Cierre y descarga de recursos
       --------------------------------------------------------- */

    modalPerfil
        .querySelectorAll('[data-close-profile-crop]')
        .forEach(function (elemento) {
            elemento.addEventListener('click', function () {
                limpiarRecortador();
            });
        });

    window.addEventListener('limpiar-recortador-perfil', limpiarRecortador);

    window.addEventListener('hashchange', function () {
        if (window.location.hash !== '#profileImageModal') {
            limpiarRecortador();
        }
    });

    window.addEventListener('beforeunload', function () {
        destruirCropper();
        liberarUrl();
    });
});
