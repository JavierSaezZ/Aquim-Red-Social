{{-- =========================================================
     RECUPERACIÓN DE CONTRASEÑA
     Solicita el correo de la cuenta para enviar el enlace con
     el que el usuario podrá establecer una nueva contraseña.
     ========================================================= --}}

<x-guest-layout>
    @vite([
        'resources/css/login.css',
        'resources/css/general.css',
        'resources/js/bubble.js'
    ])

    <div class="frutiger-theme">
        <div class="aero-panel">
            {{-- Estado y validación
                 --------------------------------------------------------- --}}
            <x-validation-errors class="mb-4" style="color: #991b1b; font-weight: bold; font-size: 0.9rem;" />

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">{{ $value }}</div>
            @endsession

            {{-- Formulario de recuperación
                 --------------------------------------------------------- --}}
            <div class="auth-container">
                <div class="auth-form form-login">
                    <div class="logo"><img class="brand-logo" src="{{ asset('images/logo/AquimLogo.webp') }}" alt="AQUIM"></div>

                    <h2>Recuperar contraseña</h2>
                    <p class="switch-text">Introduce tu correo electrónico y te enviaremos un enlace para que puedas crear una nueva contraseña.</p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <input type="email" name="email" class="aero-input" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus autocomplete="username">
                        <button type="submit" class="aero-button">Enviar enlace</button>
                    </form>

                    <p class="switch-text"><a href="{{ route('login') }}" class="switch-label">Volver al inicio de sesión</a></p>
                </div>
            </div>
        </div>
    </div>

    {{-- Burbujas decorativas del fondo
         --------------------------------------------------------- --}}
           <x-bubble/>

</x-guest-layout>
