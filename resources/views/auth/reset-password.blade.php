{{-- =========================================================
     RESTABLECIMIENTO DE CONTRASEÑA
     Valida el enlace recibido y permite definir las nuevas
     credenciales de acceso de la cuenta.
     ========================================================= --}}

<x-guest-layout>
    @vite([
        'resources/css/login.css',
        'resources/css/general.css',
        'resources/js/bubble.js'
    ])

    <x-notificacion />

    <div class="frutiger-theme">
        <div class="aero-panel">
            {{-- Formulario de restablecimiento
                 --------------------------------------------------------- --}}
            <div class="auth-container">
                <div class="auth-form form-login">
                    <div class="logo"><img class="brand-logo" src="{{ asset('images/logo/AquimLogo.webp') }}" alt="AQUIM"></div>

                    <h2>Nueva contraseña</h2>
                    <p class="switch-text">Introduce tu correo y elige una nueva contraseña para recuperar el acceso a tu cuenta.</p>

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- El token vincula el formulario con la solicitud de recuperación recibida. --}}
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <input id="email" type="email" name="email" class="aero-input" placeholder="Correo electrónico" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                        <input id="password" type="password" name="password" class="aero-input" placeholder="Nueva contraseña" required autocomplete="new-password">
                        <input id="password_confirmation" type="password" name="password_confirmation" class="aero-input" placeholder="Confirmar nueva contraseña" required autocomplete="new-password">

                        <button type="submit" class="aero-button">Cambiar contraseña</button>
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
