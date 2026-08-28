<div class="aero-panel" x-data="profilePassword">
    {{-- =========================================================
         ACTUALIZACIÓN DE LA CONTRASEÑA
         ========================================================= --}}

    <x-livewire-error-notification />

    <h3 class="stats-title profile-section-title">{{ __('Actualizar Contraseña') }}</h3>
    <p class="info-text">{{ __('Asegúrate de que tu cuenta usa una contraseña larga y aleatoria para mantenerse segura.') }}</p>

    <form wire:submit.prevent="updatePassword">
        <div class="form-group">
            <label for="current_password">{{ __('Contraseña Actual') }}</label>
            <input id="current_password" type="password" class="aero-input" wire:model="state.current_password" autocomplete="current-password">
        </div>

        <div class="form-group">
            <label for="password">{{ __('Nueva Contraseña') }}</label>
            <input id="password" type="password" class="aero-input" wire:model="state.password" autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">{{ __('Confirmar Contraseña') }}</label>
            <input id="password_confirmation" type="password" class="aero-input" wire:model="state.password_confirmation" autocomplete="new-password">
        </div>

        <div class="post-actions profile-actions-end">
            <button type="submit" class="aero-button" wire:loading.attr="disabled" wire:target="updatePassword">
                <span wire:loading.remove wire:target="updatePassword">{{ __('Guardar') }}</span>
                <span wire:loading wire:target="updatePassword">{{ __('Guardando...') }}</span>
            </button>
        </div>
    </form>
</div>
