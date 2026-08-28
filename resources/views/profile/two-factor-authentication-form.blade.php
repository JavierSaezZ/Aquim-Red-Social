<div x-data="profileTwoFactor" x-on:password-confirmed.window="handleTwoFactorPasswordConfirmed($event)" x-on:limpiar-two-factor-password.window="cancelTwoFactorAction()">
    {{-- =========================================================
         AUTENTICACIÓN EN DOS PASOS
         ========================================================= --}}

    <div class="aero-panel">
        <x-livewire-error-notification />

        <h3 class="stats-title profile-section-title">{{ __('Autenticación en Dos Pasos') }}</h3>
        <p class="info-text">{{ __('Añade seguridad adicional a tu cuenta utilizando la autenticación en dos pasos.') }}</p>

        <hr class="aero-divider two-factor-divider">

        <h4 class="two-factor-status-title">
            @if ($this->enabled)
                @if ($showingConfirmation)
                    {{ __('Termina de activar la autenticación en dos pasos.') }}
                @else
                    {{ __('Has activado la autenticación en dos pasos.') }}
                @endif
            @else
                {{ __('No has activado la autenticación en dos pasos.') }}
            @endif
        </h4>

        <div class="info-text">
            <p>{{ __('Cuando la autenticación en dos pasos está activada, se te pedirá un token seguro y aleatorio durante el inicio de sesión. Puedes obtener este token de la aplicación Google Authenticator de tu teléfono.') }}</p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="info-text">
                    <p>
                        @if ($showingConfirmation)
                            {{ __('Para terminar de activar la autenticación en dos pasos, escanea el siguiente código QR usando la aplicación de autenticación de tu teléfono o introduce la clave de configuración y proporciona el código OTP generado.') }}
                        @else
                            {{ __('La autenticación en dos pasos está ahora activada. Escanea el siguiente código QR usando la aplicación de autenticación de tu teléfono o introduce la clave de configuración.') }}
                        @endif
                    </p>
                </div>

                <div class="qr-container">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>

                <div class="info-text">
                    <p><strong>{{ __('Clave de Configuración') }}:</strong> {{ decrypt($this->user->two_factor_secret) }}</p>
                </div>

                @if ($showingConfirmation)
                    <div class="form-group two-factor-code-group">
                        <label for="code">{{ __('Código') }}</label>
                        <input id="code" type="text" name="code" class="aero-input" inputmode="numeric" autofocus autocomplete="one-time-code" wire:model="code" wire:keydown.enter="confirmTwoFactorAuthentication">
                    </div>
                @endif
            @endif

            @if ($showingRecoveryCodes)
                <div class="info-text">
                    <p>{{ __('Guarda estos códigos de recuperación en un gestor de contraseñas seguro. Se pueden usar para recuperar el acceso a tu cuenta si pierdes tu dispositivo de autenticación en dos pasos.') }}</p>
                </div>

                <div class="recovery-codes-box">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="post-actions profile-actions-start-tight">
            @if (! $this->enabled)
                <a href="#" class="aero-button" x-on:click.prevent="window.dispatchEvent(new CustomEvent('abrir-two-factor-password')); startTwoFactorAction('enable');">{{ __('Activar') }}</a>
            @else
                @if ($showingRecoveryCodes)
                    <a href="#" class="btn-secondary" x-on:click.prevent="window.dispatchEvent(new CustomEvent('abrir-two-factor-password')); startTwoFactorAction('regenerate');">{{ __('Regenerar Códigos') }}</a>
                @elseif ($showingConfirmation)
                    <a href="#" class="aero-button" x-on:click.prevent="window.dispatchEvent(new CustomEvent('abrir-two-factor-password')); startTwoFactorAction('confirm');">{{ __('Confirmar') }}</a>
                @else
                    <a href="#" class="btn-secondary" x-on:click.prevent="window.dispatchEvent(new CustomEvent('abrir-two-factor-password')); startTwoFactorAction('show-codes');">{{ __('Mostrar Códigos') }}</a>
                @endif

                @if ($showingConfirmation)
                    <a href="#" class="btn-secondary" x-on:click.prevent="window.dispatchEvent(new CustomEvent('abrir-two-factor-password')); startTwoFactorAction('cancel');">{{ __('Cancelar') }}</a>
                @else
                    <a href="#" class="btn-danger" x-on:click.prevent="window.dispatchEvent(new CustomEvent('abrir-two-factor-password')); startTwoFactorAction('disable');">{{ __('Desactivar') }}</a>
                @endif
            @endif
        </div>
    </div>

    {{-- =========================================================
         CONFIRMACIÓN DE CONTRASEÑA DEL 2FA
         ========================================================= --}}

    <x-modal-editarPerfil
        id="twoFactorPasswordModal"
        :eyebrow="__('Seguridad')"
        :title="__('Confirmar Contraseña')"
        :description="__('Por seguridad, introduce tu contraseña antes de continuar con esta acción.')"
        password-model="confirmablePassword"
        submit-action="confirmPassword"
        :confirm-text="__('Confirmar')"
        :loading-text="__('Comprobando...')"
        button-class="aero-button"
        open-event="abrir-two-factor-password"
        close-event="cerrar-two-factor-password"
        cleanup-event="limpiar-two-factor-password"
    />
