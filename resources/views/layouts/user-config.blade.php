@auth
    @php
        // 1. Preparamos los datos en PHP puro para que Blade no se atragante con los corchetes
        $datosMiUsuario = auth()->user()->only(['id', 'name', 'avatar']);
        
        $datosVisitado = null;
        if (isset($usuario)) {
            $datosVisitado = $usuario->only(['id', 'nick', 'name', 'avatar']);
        }
    @endphp

  <script>
    // 2. Inyectamos los datos en JS usando la sintaxis nativa
    window.Laravel = {
        myUser: {!! json_encode($datosMiUsuario) !!},
        visitedUser: {!! json_encode($datosVisitado) !!},
        
        // AÑADE ESTA LÍNEA AQUÍ (Le pasamos el ID del creador de la foto)
        imageOwnerId: {{ isset($image) ? $image->user_id : 'null' }}
    };
</script>
@endauth