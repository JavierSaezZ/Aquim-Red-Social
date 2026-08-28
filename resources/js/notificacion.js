/* =========================================================
   NOTIFICACIONES DE LA APLICACIÓN
   Integra el catálogo de traducciones con Alpine y Livewire.
   ========================================================= */

/* Resolución de mensajes
   --------------------------------------------------------- */

function obtenerPorClave(objeto, clave) {
    if (!objeto || !clave) {
        return undefined;
    }

    return clave
        .split('.')
        .reduce((actual, parte) => actual?.[parte], objeto);
}

function obtenerMensajeNotificacion(mensaje) {
    const mensajeOriginal = String(mensaje ?? '').trim();

    if (!mensajeOriginal) {
        return '';
    }

    const catalogo = window.catalogoNotificaciones ?? {};

    // Resuelve claves internas como "profile.updated" o "2fa.enabled".
    const mensajePorClave = obtenerPorClave(catalogo, mensajeOriginal);

    if (typeof mensajePorClave === 'string') {
        return mensajePorClave;
    }

    // También contempla los mensajes literales de Laravel, Jetstream y Fortify.
    const mensajeVendor = catalogo?.vendor?.[mensajeOriginal];

    if (typeof mensajeVendor === 'string') {
        return mensajeVendor;
    }

    // Los errores ya traducidos por Laravel se muestran sin modificaciones.
    return mensajeOriginal;
}

/* Emisión y seguimiento de eventos
   --------------------------------------------------------- */

window.notificar = (tipo, mensaje) => {
    const texto = obtenerMensajeNotificacion(mensaje);

    if (!texto) {
        return;
    }

    window.dispatchEvent(
        new CustomEvent('notificacion', {
            detail: {
                tipo: tipo || 'info',
                mensaje: texto
            }
        })
    );
};

function notificarEvento($wire, evento, mensaje, tipo = 'success') {
    $wire.on(evento, () => {
        window.notificar(tipo, mensaje);
    });
}

function tieneErrores(snapshot) {
    return Object.keys(snapshot?.memo?.errors ?? {}).length > 0;
}

function escucharAcciones($wire, acciones) {
    $wire.$hook('commit', ({ commit, succeed }) => {
        const llamada = (commit.calls ?? []).find(call => acciones[call.method]);

        if (!llamada) {
            return;
        }

        succeed(({ snapshot, effects }) => {
            if (tieneErrores(snapshot)) {
                return;
            }

            acciones[llamada.method]({
                snapshot,
                effects
            });
        });
    });
}

/* Componentes de perfil en Alpine
   --------------------------------------------------------- */

document.addEventListener('alpine:init', () => {
    Alpine.data('profilePassword', () => ({
        init() {
            notificarEvento(this.$wire, 'saved', 'password.updated');
        }
    }));

    Alpine.data('profileSessions', () => ({
        init() {
            notificarEvento(this.$wire, 'loggedOut', 'sessions.closed');
        }
    }));

    Alpine.data('profileInformation', () => ({
        init() {
            this.$wire.on('saved', () => {
                window.location.hash = '';
                this.$wire.set('state.current_password', '');
                window.notificar('success', 'profile.updated');
            });

            escucharAcciones(this.$wire, {
                deleteProfilePhoto: () => {
                    window.notificar('success', 'profile.photo_deleted');
                }
            });
        }
    }));

    Alpine.data('profileTwoFactor', () => ({
        intent: null,

        setTwoFactorIntent(intent) {
            this.intent = intent;
        },

        init() {
            escucharAcciones(this.$wire, {
                confirmTwoFactorAuthentication: () => {
                    window.notificar('success', '2fa.enabled');
                },

                regenerateRecoveryCodes: () => {
                    window.notificar('success', '2fa.recovery_regenerated');
                },

                disableTwoFactorAuthentication: () => {
                    if (this.intent === 'cancel') {
                        window.notificar('info', '2fa.cancelled');
                    } else {
                        window.notificar('success', '2fa.disabled');
                    }

                    this.intent = null;
                }
            });
        }

    }));

    const configurarRecargaSesion = () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                preventDefault();
                window.location.reload();
            }
        });
    });
};

if (window.Livewire) {
    configurarRecargaSesion();
} else {
    document.addEventListener('livewire:init', configurarRecargaSesion, { once: true });
}
});
