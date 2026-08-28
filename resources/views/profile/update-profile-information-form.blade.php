<div x-data="profileInformation" x-on:saved.window="window.dispatchEvent(new CustomEvent('cerrar-confirm-profile'))">
    <x-livewire-error-notification />

    <div class="aero-panel">
        <h3 class="stats-title profile-section-title">{{ __('Información del Perfil') }}</h3>
        <p class="profile-section-description">{{ __('Actualiza la información del perfil y la dirección de correo electrónico de tu cuenta.') }}</p>

        <div x-data x-on:imagen-perfil-lista.window="
            @this.upload(
                'photo',
                $event.detail.file,
                () => {
                    const reader = new FileReader();

                    reader.onload = (evento) => {
                        const preview = document.getElementById('photo-preview-element');
                        const contenedorPreview = document.getElementById('photo-preview-container');
                        const contenedorActual = document.getElementById('photo-current-container');

                        if (preview) {
                            preview.style.backgroundImage = 'url(\'' + evento.target.result + '\')';
                        }

                        if (contenedorPreview) {
                            contenedorPreview.style.display = 'block';
                        }

                        if (contenedorActual) {
                            contenedorActual.style.display = 'none';
                        }
                    };

                    reader.readAsDataURL($event.detail.blob);
                    window.location.hash = '';
                    window.dispatchEvent(new CustomEvent('limpiar-recortador-perfil'));
                },
                () => {
                    window.notificar('error', 'profile.photo_error');
                }
            );
        ">
            <form x-on:submit.prevent="
                const nickOriginal = @js($this->user->nick);
                const emailOriginal = @js($this->user->email);
                const nickActual = $wire.state.nick;
                const emailActual = $wire.state.email;
                const requierePassword = nickActual !== nickOriginal || emailActual !== emailOriginal;

                if (requierePassword) {
                    if (window.location.hash) {
                        history.replaceState(null, '', window.location.pathname + window.location.search);
                    }

                    window.dispatchEvent(new CustomEvent('limpiar-recortador-perfil'));
                    window.dispatchEvent(new CustomEvent('abrir-confirm-profile'));
                } else {
                    $wire.updateProfileInformation();
                }
            ">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="form-group">
                        <label>{{ __('Foto') }}</label>

                        <div class="profile-photo-wrapper">
                            <div id="photo-current-container">
                                <x-avatar :user="$this->user" class="profile-avatar" />
                            </div>

                            <div id="photo-preview-container" class="profile-photo-preview-container">
                                <span id="photo-preview-element" class="profile-avatar profile-avatar-preview"></span>
                            </div>

                            <div class="profile-photo-actions">
                                <a href="#profileImageModal" class="btn-secondary">{{ __('Subir Foto') }}</a>

                                @if ($this->user->profile_photo_path)
                                    <button type="button" class="btn-danger-outline" wire:click="deleteProfilePhoto" wire:loading.attr="disabled" wire:target="deleteProfilePhoto">{{ __('Eliminar Foto') }}</button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label for="name">{{ __('Nombre') }}</label>
                    <input id="name" type="text" class="aero-input" wire:model="state.name" autocomplete="name">
                </div>

                <div class="form-group">
                    <label for="nick">{{ __('Nick') }}</label>
                    <input id="nick" type="text" class="aero-input" wire:model="state.nick" maxlength="30" autocomplete="username" spellcheck="false">
                </div>

                <div class="form-group">
                    <label for="email">{{ __('Correo Electrónico') }}</label>
                    <input id="email" type="email" class="aero-input" wire:model="state.email" autocomplete="email">
                </div>

                <div class="post-actions profile-actions-end">
                    <button type="submit" class="aero-button" wire:loading.attr="disabled" wire:target="photo">{{ __('Guardar Cambios') }}</button>
                </div>
            </form>
        </div>
    </div>

    <x-modal-editarPerfil
        id="confirmProfileModal"
        :eyebrow="__('Seguridad')"
        :title="__('Confirmar Cambios')"
        :description="__('Por seguridad, introduce tu contraseña actual para guardar los cambios realizados en tu cuenta.')"
        password-model="state.current_password"
        submit-action="updateProfileInformation"
        :confirm-text="__('Guardar Cambios')"
        :loading-text="__('Comprobando...')"
        button-class="aero-button"
        open-event="abrir-confirm-profile"
        close-event="cerrar-confirm-profile"
    />

    <x-modal-imagen-perfil />
</div>