/* =========================================================
   RECORTADOR Y SUBIDA DE PUBLICACIONES
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {
    let cropper = null;
    let urlImagenActual = null;

    const inputImagen = document.getElementById('input-imagen');
    const imagenARecortar = document.getElementById('imagen-a-recortar');
    const contenedorCrop = document.querySelector('.contenedor-crop');
    const menuAspectos = document.getElementById('menu-aspectos');
    const btnEnviar = document.getElementById('btn-enviar');
    const modalContent = document.querySelector('#uploadModal .modal-content');

    /* Notificaciones
       --------------------------------------------------------- */

    function mostrarNotificacion(tipo, mensaje) {
        const tipoFinal = tipo || 'info';
        const mensajeFinal = String(mensaje || '').trim();

        if (!mensajeFinal) {
            return;
        }

        if (typeof window.notificar === 'function') {
            window.notificar(tipoFinal, mensajeFinal);
            return;
        }

        window.dispatchEvent(
            new CustomEvent('notificacion', {
                detail: {
                    tipo: tipoFinal,
                    mensaje: mensajeFinal
                }
            })
        );
    }

    /* Inicialización y estabilización del modal
       --------------------------------------------------------- */

    if (
        !inputImagen ||
        !imagenARecortar ||
        !contenedorCrop ||
        !btnEnviar ||
        !modalContent
    ) {
        return;
    }

    if (menuAspectos) {
        menuAspectos.style.display = 'none';
    }

    function esperarTamanoEstable(callback) {
        let dimensionesAnteriores = '';
        let framesEstables = 0;
        const inicio = performance.now();

        function comprobarTamano() {
            const modalRect = modalContent.getBoundingClientRect();
            const cropRect = contenedorCrop.getBoundingClientRect();

            const dimensionesActuales = [
                Math.round(modalRect.width),
                Math.round(modalRect.height),
                Math.round(cropRect.width),
                Math.round(cropRect.height)
            ].join('-');

            if (dimensionesActuales === dimensionesAnteriores) {
                framesEstables++;
            } else {
                framesEstables = 0;
                dimensionesAnteriores = dimensionesActuales;
            }

            const tiempoTranscurrido = performance.now() - inicio;

            if (
                (framesEstables >= 5 && tiempoTranscurrido >= 120) ||
                tiempoTranscurrido >= 800
            ) {
                requestAnimationFrame(function () {
                    requestAnimationFrame(callback);
                });

                return;
            }

            requestAnimationFrame(comprobarTamano);
        }

        requestAnimationFrame(comprobarTamano);
    }

    /* Selección e inicialización de la imagen
       --------------------------------------------------------- */

    inputImagen.addEventListener('change', function (e) {
        const archivos = e.target.files;

        if (!archivos || archivos.length === 0) {
            return;
        }

        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        if (urlImagenActual) {
            URL.revokeObjectURL(urlImagenActual);
        }

        urlImagenActual = URL.createObjectURL(archivos[0]);

        contenedorCrop.style.display = 'block';
        contenedorCrop.style.visibility = 'hidden';
        btnEnviar.style.display = 'inline-block';

        imagenARecortar.onload = function () {
            esperarTamanoEstable(function () {
                    cropper = new Cropper(imagenARecortar, {
                        aspectRatio: 16 / 9,
                        viewMode: 1,
                        dragMode: 'move',
                        responsive: true,
                        cropBoxResizable: true,
                        cropBoxMovable: true,
                        zoomable: true,
                        zoomOnTouch: true,
                        zoomOnWheel: true,
                        toggleDragModeOnDblclick: false,
                        autoCropArea: 1,
                        background: false,

                        ready() {
                            requestAnimationFrame(function () {
                                contenedorCrop.style.visibility = 'visible';
                            });
                        }
                    });
            });
        };

        imagenARecortar.src = urlImagenActual;
    });

    /* Procesamiento y envío del formulario
       --------------------------------------------------------- */

    const formulario = document.querySelector('#uploadModal form');

    if (formulario) {
        formulario.addEventListener('submit', function (e) {
            if (!cropper) {
                return;
            }

            e.preventDefault();

            const data = cropper.getData(true);
            const ratioActual = data.width / data.height;

            const archivoOriginal =
                inputImagen.files && inputImagen.files.length > 0
                    ? inputImagen.files[0]
                    : null;

            if (!archivoOriginal) {
                mostrarNotificacion(
                    'error',
                    'No se ha encontrado la imagen original.'
                );
                return;
            }

            /* Generación de las versiones 2K y feed
               --------------------------------------------------------- */

            const MAX_ANCHO_2K = 2048;
            const MAX_ANCHO_FEED = 1280;
            const anchoFuente = Math.max(1, Math.round(data.width));
            const ancho2K = Math.min(anchoFuente, MAX_ANCHO_2K);
            const alto2K = Math.max(1, Math.round(ancho2K / ratioActual));
            const anchoFeed = Math.min(anchoFuente, MAX_ANCHO_FEED);
            const altoFeed = Math.max(1, Math.round(anchoFeed / ratioActual));

            const canvas2K = cropper.getCroppedCanvas({
                width: ancho2K,
                height: alto2K,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            const canvasFeed = cropper.getCroppedCanvas({
                width: anchoFeed,
                height: altoFeed,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high'
            });

            function crearBlob(canvas, calidad) {
                return new Promise(function (resolve, reject) {
                    canvas.toBlob(
                        function (blob) {
                            if (!blob) {
                                reject(
                                    new Error(
                                        'No se ha podido procesar la imagen.'
                                    )
                                );
                                return;
                            }

                            resolve(blob);
                        },
                        'image/jpeg',
                        calidad
                    );
                });
            }

            /* Preparación de archivos y petición
               --------------------------------------------------------- */

            Promise.all([
                crearBlob(canvas2K, 0.95),
                crearBlob(canvasFeed, 0.85)
            ])
                .then(function (blobs) {
                    const blob2K = blobs[0];
                    const blobFeed = blobs[1];
                    const formData = new FormData(formulario);

                    formData.delete('image_path');
                    formData.delete('image_4k');
                    formData.delete('image_2k');
                    formData.delete('image_original');
                    formData.delete('image_feed');
                    formData.delete('imagen_recortada');

                    formData.append(
                        'image_original',
                        archivoOriginal,
                        archivoOriginal.name
                    );

                    formData.append(
                        'image_2k',
                        blob2K,
                        'imagen_2k.jpg'
                    );

                    formData.append(
                        'image_feed',
                        blobFeed,
                        'imagen_feed.jpg'
                    );

                    fetch(formulario.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            Accept: 'application/json'
                        }
                    })
                        .then(async function (response) {


                            /* Tratamiento de la respuesta
                               --------------------------------------------------------- */

                            if (response.ok) {
                                window.location.href =
                                    formulario.dataset.redirect;
                                return;
                            }

                            if (response.status === 413) {
                                mostrarNotificacion(
                                    'error',
                                    'La imagen es demasiado grande. Reduce el tamaño del archivo e inténtalo de nuevo.'
                                );
                                return;
                            }

                            if (response.status === 419) {
                                mostrarNotificacion(
                                    'warning',
                                    'Tu sesión ha caducado. Recarga la página e inténtalo de nuevo.'
                                );
                                return;
                            }

                            let errorData = null;

                            const contentType =
                                response.headers.get('content-type') || '';

                            if (contentType.includes('application/json')) {
                                try {
                                    errorData = await response.json();
                                } catch (error) {
                                    console.error(
                                        'No se ha podido leer la respuesta JSON:',
                                        error
                                    );
                                }
                            }

                            if (response.status === 422) {
                                let mensaje =
                                    errorData?.message ||
                                    'Los datos enviados no son válidos.';

                                if (errorData?.errors) {
                                    const errores = Object.values(
                                        errorData.errors
                                    ).flat();

                                    if (errores.length > 0) {
                                        mensaje = errores[0];
                                    }
                                }

                                mostrarNotificacion('error', mensaje);
                                return;
                            }

                            if (response.status >= 500) {
                                console.error(
                                    'Error del servidor:',
                                    response.status,
                                    errorData
                                );

                                mostrarNotificacion(
                                    'error',
                                    'Ha ocurrido un error en el servidor al subir la imagen.'
                                );
                                return;
                            }

                            console.error(
                                'Error de Laravel:',
                                response.status,
                                errorData
                            );

                            mostrarNotificacion(
                                'error',
                                errorData?.message ||
                                    'No se ha podido subir la imagen.'
                            );
                        })
                        .catch(function (error) {
                            console.error('Error de conexión:', error);

                            mostrarNotificacion(
                                'error',
                                'No se ha podido conectar con el servidor.'
                            );
                        });
                })
                .catch(function (error) {
                    console.error('Error procesando la imagen:', error);

                    mostrarNotificacion(
                        'error',
                        'No se ha podido procesar la imagen.'
                    );
                });
        });
    }

    /* Liberación de la URL temporal
       --------------------------------------------------------- */

    window.addEventListener('beforeunload', function () {
        if (urlImagenActual) {
            URL.revokeObjectURL(urlImagenActual);
        }
    });
});