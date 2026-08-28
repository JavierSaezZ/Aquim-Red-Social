<div x-data>
    {{-- =========================================================
         ELIMINACIÓN DE LA CUENTA
         ========================================================= --}}

    <div class="aero-panel danger-profile-panel">
        <x-livewire-error-notification />

        <h3 class="stats-title danger-profile-title">{{ __('Borrar Cuenta') }}</h3>
        <p class="info-text danger-profile-subtitle">{{ __('Borrar tu cuenta de forma permanente.') }}</p>

        <div class="info-text danger-profile-description">
            {{ __('Una vez que tu cuenta sea borrada, todos sus recursos y datos serán eliminados permanentemente. Antes de borrar tu cuenta, por favor descarga cualquier dato o información que desees conservar.') }}
        </div>

        <div class="post-actions danger-profile-actions">
            <a href="#" class="btn-danger" wire:click="$set('password', '')" x-on:click.prevent="window.dispatchEvent(new CustomEvent('abrir-delete-account-password'))">{{ __('Borrar Cuenta') }}</a>
        </div>
    </div>

    {{-- =========================================================
         CONFIRMACIÓN DEL BORRADO
         ========================================================= --}}

    <x-modal-editarPerfil
        id="deleteAccountPasswordModal"
        :eyebrow="__('Acción irreversible')"
        :title="__('Borrar Cuenta')"
        :description="__('Esta acción eliminará permanentemente tu cuenta y todos sus datos. Introduce tu contraseña para confirmar que deseas continuar.')"
        password-model="password"
        submit-action="deleteUser"
        :confirm-text="__('Borrar Cuenta')"
        :loading-text="__('Eliminando...')"
        button-class="btn-danger"
        open-event="abrir-delete-account-password"
        close-event="cerrar-delete-account-password"
    />
</div>
