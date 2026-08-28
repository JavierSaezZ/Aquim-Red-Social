<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 419 - Sesión caducada | aquim Aero</title>

    @vite(['resources/css/general.css', 'resources/css/errors.css', 'resources/js/bubble.js'])
</head>
<body>

    <div class="frutiger-theme error-container error-419-page">
        <main class="workspace error-workspace">
            <section class="aero-panel error-panel-creative" aria-labelledby="error-title">

                <div class="watermark-419" aria-hidden="true">419</div>

                <!-- Reloj de arena Frutiger Aero -->
                <div class="aero-hourglass" aria-hidden="true">
                    <div class="hourglass-cap hourglass-cap-top"></div>

                    <div class="hourglass-glass">
                        <div class="sand sand-top"></div>
                        <div class="sand-stream"></div>
                        <div class="sand sand-bottom"></div>
                    </div>

                    <div class="hourglass-cap hourglass-cap-bottom"></div>
                </div>

                <div class="error-content">
                    <h1 id="error-title" class="error419-title">Sesión caducada</h1>

                    <p class="error419-description">
                        La página ha permanecido abierta demasiado tiempo y tu sesión de seguridad ha expirado. Vuelve al inicio para continuar dentro del ecosistema.
                    </p>

                    <a href="{{ url('/') }}" class="aero-button error419-button">
                        Volver al inicio <span aria-hidden="true">🫧</span>
                    </a>
                </div>

            </section>
        </main>

        <x-bubble/>
    </div>

</body>
</html>
