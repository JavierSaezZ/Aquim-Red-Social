<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 404 - Página no encontrada | aquim Aero</title>

    @vite(['resources/css/general.css', 'resources/css/errors.css', 'resources/js/bubble.js'])
</head>
<body>
    <div class="frutiger-theme error-container error-404-page">
        <div class="workspace error-workspace">
            <div class="aero-panel error-panel-creative">
                <div class="watermark-404">404</div>

                <div class="aero-lens">
                    <div class="lens-glass"><span class="question-mark">?</span></div>
                    <div class="lens-handle"></div>
                </div>

                <div class="error-content">
                    <h2 class="error-title">Ruta Extraviada</h2>

                    <p class="error-description">
                        Parece que te has salido del camino. La página que buscas no existe o ha sido movida a otra parte de nuestro ecosistema.
                    </p>

                    <a href="{{ url('/dashboard') }}" class="error-btn-link">
                        <button class="aero-button error-btn-custom">Volver al Inicio <span>🫧</span></button>
                    </a>
                </div>
            </div>
        </div>

        <x-bubble />
    </div>
</body>
</html>
