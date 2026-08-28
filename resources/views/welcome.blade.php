<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>aquim Aero | Tu ecosistema creativo</title>
    <meta
        name="description"
        content="Entra en aquim Aero, un ecosistema visual para descubrir, compartir y conectar a través de imágenes."
    >

    @vite([
        'resources/css/general.css',
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/bubble.js'

    ])
</head>
<body>
    <main class="frutiger-theme aquim-home-page">
        <section class="workspace aquim-home-shell" aria-labelledby="aquim-home-title">
            <div class="aero-panel aquim-home-card">

                <div class="aquim-home-visual" aria-hidden="true">
                    <div class="landing-emblem">
                        <div class="brand-logo landing-emblem__logo">                
                            <img class="logo"src="{{ asset('images/logo/AquimLogo.webp') }}" alt="AQUIM">
                        </div>
                        <div class="brand-logo landing-emblem__logoText">
                                                <img class="logo"src="{{ asset('images/logo/AquimLogoPalabra.webp') }}" alt="AQUIM">
                    </div>
                    </div>

                    <div class="aquim-home-light"></div>

     
                </div>

                <div class="aquim-home-content">
                    <p class="aquim-home-eyebrow">Explora · Comparte · Conecta</p>

                    <h1 id="aquim-home-title">
                        Todo tu mundo creativo,
                        <span>en un mismo ecosistema.</span>
                    </h1>

                    <p class="aquim-home-description">
                        Descubre nuevas ideas, comparte tus imágenes y conecta con una comunidad
                        diseñada para hacer crecer tu creatividad.
                    </p>

                    <div class="aquim-home-benefits" aria-label="Características principales">
                        <span>Inspírate</span>
                        <span>Comparte</span>
                        <span>Conecta</span>
                    </div>

                    <div class="aquim-home-actions">
                        @auth
                            <a href="{{ route('dashboard') }}" class="aero-button aquim-home-cta">
                                <span>Acceder al ecosistema</span>
                                <span class="aquim-home-cta__icon" aria-hidden="true">➜</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="aero-button aquim-home-cta">
                                <span>Acceder al ecosistema</span>
                                <span class="aquim-home-cta__icon" aria-hidden="true">➜</span>
                            </a>

                            @if (Route::has('register'))
                                <p class="aquim-home-register">
                                    ¿Aún no formas parte?
                                   <a href="{{ route('login', ['register' => 1]) }}">Crear una cuenta</a>
                                </p>
                            @endif
                        @endauth
                    </div>
                </div>
           <x-bubble/>
               
            </div>
        </section>
        
    </main>
</body>
</html>
