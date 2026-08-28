<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'aquim Aero')</title>

    @hasSection('description')
        <meta name="description" content="@yield('description')">
    @endif

    {{-- Elementos adicionales del <head> de cada vista --}}
    @stack('head')

    {{-- Recursos globales de AQUIM --}}
    @vite([
        'resources/css/general.css',
        'resources/js/app.js',
        'resources/js/recortador.js',
        'resources/js/bubble.js',
        'resources/js/contadorcaracter.js',
        'resources/js/buscador.js',
    ])

    {{-- Recursos específicos de cada vista --}}
    @stack('styles')

    {{-- CropperJS utilizado por el modal global de subida de imágenes --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

    {{-- Configuración visual personalizada del usuario --}}
    @hasSection('auth-only-globals')
        @auth
            @include('layouts.user-config')
        @endauth
    @else
        @include('layouts.user-config')
    @endif
</head>

<body>

    {{-- Notificaciones globales --}}
    <x-notificacion />

    <div class="frutiger-theme">

        {{-- Cabecera superior común --}}
        <x-header titulo="Header" />

        {{-- Contenido específico de cada vista --}}
        @yield('content')

        {{-- Pie informativo común, excepto en las vistas que decidan ocultarlo --}}
        @unless (View::hasSection('hide-footer'))
            <x-footer />
        @endunless

        {{-- Burbujas decorativas globales --}}
        <x-bubble />

    </div>

    {{-- Modales que deben aparecer antes del modal global --}}
    @stack('modals-before')

    {{-- Modal global para subir imágenes --}}
    @hasSection('auth-only-globals')
        @auth
            <x-modalImage titulo="Modal" />
        @endauth
    @else
        <x-modalImage titulo="Modal" />
    @endif

    {{-- Modales específicos de cada vista --}}
    @stack('modals')

    {{-- JavaScript específico de cada vista --}}
    @stack('scripts')

</body>

</html>