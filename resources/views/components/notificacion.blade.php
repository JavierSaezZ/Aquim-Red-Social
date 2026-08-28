{{-- =========================================================
     NOTIFICACIÓN GLOBAL
     ========================================================= --}}

@php
    /* Catálogo y resolución de mensajes
       --------------------------------------------------------- */

    $catalogoNotificaciones = trans('aquim');

    if (!is_array($catalogoNotificaciones)) {
        $catalogoNotificaciones = [];
    }

    /* Resuelve claves propias, mensajes de proveedores y textos
       que Laravel ya pueda traducir mediante sus archivos de idioma. */
    $resolverMensaje = function ($mensaje) use ($catalogoNotificaciones) {
        if (!is_string($mensaje) || $mensaje === '') {
            return $mensaje;
        }

        $mensajePorClave = data_get($catalogoNotificaciones, $mensaje);

        if (is_string($mensajePorClave)) {
            return $mensajePorClave;
        }

        if (isset($catalogoNotificaciones['vendor'][$mensaje])) {
            return $catalogoNotificaciones['vendor'][$mensaje];
        }

        $traducido = __($mensaje);

        return is_string($traducido)
            ? $traducido
            : $mensaje;
    };

    /* Mensaje inicial
       --------------------------------------------------------- */

    /* Los errores de Laravel y Fortify se transforman directamente
       en el toast global, sin utilizar <x-validation-errors>. */
    $tipoInicial = null;
    $mensajeInicial = null;

    if ($errors->any()) {
        $tipoInicial = 'error';
        $mensajeInicial = $resolverMensaje($errors->first());
    } elseif (session()->has('error')) {
        $tipoInicial = 'error';
        $mensajeInicial = $resolverMensaje(session()->pull('error'));
    } elseif (session()->has('warning')) {
        $tipoInicial = 'warning';
        $mensajeInicial = $resolverMensaje(session()->pull('warning'));
    } elseif (session()->has('success')) {
        $tipoInicial = 'success';
        $mensajeInicial = $resolverMensaje(session()->pull('success'));
    } elseif (session()->has('info')) {
        $tipoInicial = 'info';
        $mensajeInicial = $resolverMensaje(session()->pull('info'));
    } elseif (session()->has('status')) {
        /* Fortify usa "status" en flujos como la recuperación de contraseña. */
        $tipoInicial = 'success';
        $mensajeInicial = $resolverMensaje(session()->pull('status'));
    }
@endphp

{{-- Catálogo de idioma disponible para los scripts de la aplicación
     --------------------------------------------------------- --}}
<script>
    window.catalogoNotificaciones = @js($catalogoNotificaciones);
</script>

{{-- Toast único: gestiona la visibilidad, el temporizador y el cierre manual
     --------------------------------------------------------- --}}
<div
    x-data="{
        visible: false,
        tipo: @js($tipoInicial ?? 'info'),
        mensaje: @js($mensajeInicial ?? ''),
        timer: null,

        iniciarTemporizador() {
            if (this.timer) {
                clearTimeout(this.timer);
            }

            this.timer = setTimeout(
                () => {
                    this.visible = false;
                    this.timer = null;
                },
                3800
            );
        },

        mostrar(tipoNuevo, mensajeNuevo) {
            /* Evita sustituir una notificación mientras siga visible. */
            if (this.visible) {
                return;
            }

            this.tipo = tipoNuevo || 'info';
            this.mensaje = mensajeNuevo || '';

            if (!this.mensaje) {
                return;
            }

            this.visible = true;
            this.iniciarTemporizador();
        },

        cerrar() {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }

            this.visible = false;
        },

        icono() {
            switch (this.tipo) {
                case 'success':
                    return '✨';

                case 'error':
                    return '❌';

                case 'warning':
                    return '⚠️';

                case 'info':
                    return 'ℹ️';

                default:
                    return '🔔';
            }
        }
    }"
    x-init="
        if (mensaje) {
            visible = true;
            iniciarTemporizador();
        }
    "
    x-on:notificacion.window="
        mostrar(
            $event.detail.tipo,
            $event.detail.mensaje
        )
    "
>
    <template x-if="visible">
        <div class="toasts">
            <div class="toast-wrap">
                <div class="frutiger-toast">
                    <div class="toast-content">
                        <div class="toast-icon" x-text="icono()"></div>
                        <div class="toast-text"><span x-text="mensaje"></span></div>
                    </div>

                    <button type="button" class="icon-button toast-close" aria-label="Cerrar" x-on:click="cerrar()">❌</button>
                </div>
            </div>
        </div>
    </template>
</div>
@once
    <script>
        const ocultarErrorSesionLivewire = () => {
            Livewire.hook('request', ({ fail }) => {
                fail(({ status, preventDefault }) => {
                    if (status === 419) {
                        preventDefault();
                    }
                });
            });
        };

        if (window.Livewire) {
            ocultarErrorSesionLivewire();
        } else {
            document.addEventListener(
                'livewire:init',
                ocultarErrorSesionLivewire,
                { once: true }
            );
        }
    </script>
@endonce
