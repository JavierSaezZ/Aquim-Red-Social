{{-- =========================================================
     ACCESO Y REGISTRO
     Reúne ambos formularios y alterna su visibilidad mediante CSS.
     ========================================================= --}}

<x-guest-layout>
    @vite([
        'resources/css/login.css',
        'resources/css/general.css',
        'resources/js/notificacion.js',
        'resources/js/bubble.js'
    ])

    <div class="frutiger-theme auth-page">
        <a href="{{ url('/') }}" class="auth-back-button" aria-label="Volver al inicio" title="Volver al inicio">
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M15 5 8 12l7 7" />
            </svg>
        </a>

        {{-- Centraliza los errores de Laravel/Fortify, los mensajes de sesión y los eventos de JavaScript o Livewire. --}}
        <x-notificacion />

        {{-- Este control permite alternar entre los formularios de acceso y registro. --}}
        <input type="checkbox" id="auth-toggle" class="toggle-checkbox" {{ request()->boolean('register') ? 'checked' : '' }}>

        <div class="aero-panel">
            <div class="auth-container">
                {{-- Inicio de sesión
                     --------------------------------------------------------- --}}
                <div class="auth-form form-login">
                    <div class="logo"><img class="brand-logo" src="{{ asset('images/logo/AquimLogo.webp') }}" alt="AQUIM"></div>

                    <h2>Iniciar Sesión</h2>

                    {{-- Laravel gestiona la validación para que todos los mensajes lleguen al componente de notificación. --}}
                    <form method="POST" action="{{ route('login') }}" novalidate>
                        @csrf

                        <input type="text" name="email" class="aero-input" placeholder="Correo electrónico / @Nickname / Nickname" value="{{ old('email') }}" required autofocus autocomplete="username">
                        <input type="password" name="password" class="aero-input" placeholder="Contraseña" required autocomplete="current-password">

                        <div class="extra-actions">
                            <label for="remember_me" class="remember-label">
                                <input type="checkbox" id="remember_me" name="remember">
                                <span>Recordarme</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="forgot-link" href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                            @endif
                        </div>

                        <button type="submit" class="aero-button">Entrar</button>
                    </form>

                    <p class="switch-text">¿No tienes cuenta? <label for="auth-toggle" class="switch-label">Regístrate aquí</label></p>
                </div>

                {{-- Registro
                     --------------------------------------------------------- --}}
                <div class="auth-form form-register">
                    <div class="brand-logo"></div>

                    <h2>Crear Cuenta</h2>

                    <form method="POST" action="{{ route('register') }}" novalidate>
                        @csrf

                        <input type="text" name="name" class="aero-input" placeholder="Nombre" value="{{ old('name') }}" required autocomplete="name">
                        <input type="text" name="nick" class="aero-input" placeholder="Nombre de usuario (ej: @usuario)" value="{{ old('nick') }}" required autocomplete="nick">
                        <input type="email" name="email" class="aero-input" placeholder="Correo electrónico" value="{{ old('email') }}" required autocomplete="username">
                        <input type="password" name="password" class="aero-input" placeholder="Contraseña" required autocomplete="new-password">
                        <input type="password" name="password_confirmation" class="aero-input" placeholder="Confirmar contraseña" required autocomplete="new-password">

                        <button type="submit" class="aero-button">Registrarse</button>
                    </form>

                    <p class="switch-text">¿Ya tienes cuenta? <label for="auth-toggle" class="switch-label">Inicia sesión</label></p>
                </div>
            </div>
        </div>
        <x-footer />
    </div>
    <x-bubble/>
</x-guest-layout>