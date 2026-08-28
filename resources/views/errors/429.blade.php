<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 429 - Demasiadas solicitudes | aquim Aero</title>

    @vite(['resources/css/general.css', 'resources/css/errors.css', 'resources/js/bubble.js'])
</head>
<body>

    <div class="frutiger-theme error-container error-429-page">
        <div class="workspace error-workspace">
            <div class="aero-panel error-panel-creative">
                <div class="watermark-429">429</div>

                <div class="aero-hourglass" aria-hidden="true">
                    <div class="hourglass-frame hourglass-frame-top"></div>
                    <div class="hourglass-glass">
                        <div class="hourglass-shine"></div>
                        <div class="sand sand-top"></div>
                        <div class="sand-stream"></div>
                        <div class="sand sand-bottom"></div>
                    </div>
                    <div class="hourglass-frame hourglass-frame-bottom"></div>
                </div>

                <div class="error-content">
                    <h2 class="error-title">Un respiro, por favor</h2>

                    <p class="error-description">
                        Has realizado demasiadas solicitudes en muy poco tiempo. Espera unos segundos antes de volver a intentarlo.
                    </p>

                    <a href="{{ url('/') }}" class="aero-button error-button">
                        Volver al Inicio <span>🫧</span>
                    </a>
                </div>
            </div>
        </div>

        <x-bubble/>
    </div>

</body>
</html>
