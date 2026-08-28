<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Error 500 - Error interno | aquim Aero</title>

    @vite([
        'resources/css/general.css',
        'resources/css/errors.css',
        'resources/js/bubble.js'
    ])
</head>
<body>

    <div class="frutiger-theme error-container error-500-page">
        <main class="workspace error-workspace">

            <section class="aero-panel error-panel-creative" aria-labelledby="error-title">

                <div class="watermark-500" aria-hidden="true">500</div>

                <!-- Servidor Frutiger Aero -->
                <div class="aero-server" aria-hidden="true">

                    <div class="server-unit server-unit-top">
                        <span class="server-light"></span>
                        <span class="server-light"></span>
                        <span class="server-slot"></span>
                    </div>

                    <div class="server-unit server-unit-middle">
                        <span class="server-light"></span>
                        <span class="server-light"></span>
                        <span class="server-slot"></span>
                    </div>

                    <div class="server-unit server-unit-bottom">
                        <span class="server-light"></span>
                        <span class="server-light"></span>
                        <span class="server-slot"></span>
                    </div>

                    <div class="error-spark">
                        <span class="spark-core">!</span>
                    </div>

                </div>

                <div class="error-content">

                    <h1 id="error-title" class="error500-title">
                        Algo se ha torcido
                    </h1>

                    <p class="error500-description">
                        Ha ocurrido un error interno y no hemos podido completar la solicitud.
                        Vuelve al inicio e inténtalo de nuevo dentro de unos instantes.
                    </p>

                    <a href="{{ url('/') }}" class="aero-button error500-button">
                        Volver al inicio <span aria-hidden="true">🫧</span>
                    </a>

                </div>

            </section>

        </main>

        <x-bubble/>
    </div>

</body>
</html>
