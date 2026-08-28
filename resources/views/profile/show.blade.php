<x-app-layout>
    {{-- =========================================================
         DEPENDENCIAS DE LA VISTA
         ========================================================= --}}

    @vite([
        'resources/css/general.css',
        'resources/css/perfil.css',
        'resources/js/recortador-perfil.js',
        'resources/js/notificacion.js',
        'resources/js/recortador.js',
        'resources/js/contadorcaracter.js',
        'resources/js/bubble.js',
        'resources/js/avatar.js'

    ])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <x-notificacion />

    {{-- =========================================================
         CONFIGURACIÓN DEL PERFIL
         ========================================================= --}}

    <div class="frutiger-theme">
        <div class="workspace">
            <x-header titulo="Header" />
            <x-header2 titulo="HeaderIzquierdo" />

            <div class="profile-workspace">
                <div class="feed">
                    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                        @livewire('profile.update-profile-information-form')
                        <hr class="aero-divider">
                    @endif

                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                        @livewire('profile.update-password-form')
                        <hr class="aero-divider">
                    @endif

                    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                        @livewire('profile.two-factor-authentication-form')
                        <hr class="aero-divider">
                    @endif

                    @livewire('profile.logout-other-browser-sessions-form')

                    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                        <hr class="aero-divider">
                        @livewire('profile.delete-user-form')
                    @endif
                </div>
            </div>

            <x-header3 titulo="HeaderDerecho" />
        </div>
        <x-modalImage titulo="Modal" />

        <x-bubble />
    </div>
</x-app-layout>
