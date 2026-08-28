<div x-data="profileSessions">
    {{-- =========================================================
         SESIONES DEL NAVEGADOR
         ========================================================= --}}

    <div class="aero-panel">
        <x-livewire-error-notification />

        <h3 class="stats-title profile-section-title">{{ __('Sesiones del Navegador') }}</h3>
        <p class="info-text">{{ __('Gestiona y cierra tus sesiones activas en otros navegadores y dispositivos.') }}</p>

        <div class="info-text profile-info-spaced">
            {{ __('Si es necesario, puedes cerrar la sesión de todos tus otros navegadores en todos tus dispositivos. A continuación se enumeran algunas de tus sesiones recientes; sin embargo, esta lista puede no ser exhaustiva. Si crees que tu cuenta ha sido comprometida, también deberías actualizar tu contraseña.') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="session-list">
                @foreach ($this->sessions as $session)
                    <div class="session-item">
                        <div class="session-icon">
                            @if ($session->agent->isDesktop())
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                </svg>
                            @endif
                        </div>

                        <div class="session-details">
                            <div class="session-platform">
                                {{ $session->agent->platform() ?: __('Desconocido') }} - {{ $session->agent->browser() ?: __('Desconocido') }}
                            </div>

                            <div class="session-meta">
                                {{ $session->ip_address }},

                                @if ($session->is_current_device)
                                    <span class="session-current">{{ __('Este dispositivo') }}</span>
                                @else
                                    {{ __('Última vez activo') }} {{ $session->last_active }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="post-actions profile-actions-start">
            <a href="#" class="aero-button" x-on:click.prevent="window.dispatchEvent(new CustomEvent('abrir-sessions-password'))">{{ __('Cerrar Sesión en Otros Navegadores') }}</a>
        </div>
    </div>

    {{-- =========================================================
         CONFIRMACIÓN DE CONTRASEÑA
         ========================================================= --}}

    <x-modal-editarPerfil
        id="sessionsPasswordModal"
        :eyebrow="__('Seguridad')"
        :title="__('Cerrar Sesión en Otros Navegadores')"
        :description="__('Introduce tu contraseña para confirmar que deseas cerrar las sesiones abiertas en tus otros dispositivos.')"
        password-model="password"
        submit-action="logoutOtherBrowserSessions"
        :confirm-text="__('Cerrar Sesiones')"
        :loading-text="__('Cerrando...')"
        button-class="aero-button"
        open-event="abrir-sessions-password"
        close-event="cerrar-sessions-password"
    />
</div>
