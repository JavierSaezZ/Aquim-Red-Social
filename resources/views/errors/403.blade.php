<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Error 403 - Acceso Denegado | aquim Aero</title>
    
    @vite([ 'resources/css/general.css', 'resources/css/errors.css','resources/js/bubble.js'])
    
</head>
<body>

    <div class="frutiger-theme error-container error-403-page">
        
      
        <div class="workspace error403-workspace">
            
            <div class="aero-panel error-panel-creative">
                
                <!-- 403 Gigante -->
                <div class="watermark-403">403</div>

                <!-- Candado Frutiger Aero (Ahora calcado del Botón) -->
                <div class="aero-lock">
                    <div class="shackle"></div>
                    <div class="body">
                        <div class="keyhole"></div>
                    </div>
                </div>
                
                <!-- Textos y Botón -->
                <div class="error-content">
                    <h2 class="error403-title">
                        Acceso Restringido
                    </h2>
                    
                    <p class="error403-description">
                        Esta área es privada y tu cuenta actual no dispone de los permisos necesarios. El ecosistema está protegido.
                    </p>
                    
                    <!-- Botón -->
                    <a href="{{ url('/') }}" class="error403-link">
                        <button class="aero-button error403-button">
                            Volver al Ecosistema <span>🫧</span>
                        </button>
                    </a>
                </div>
                
            </div>
            
        </div>
        <x-bubble/>
    </div>
    

</body>
</html>