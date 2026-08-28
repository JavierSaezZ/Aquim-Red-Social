{{-- =========================================================
     MODAL DE CONFIRMACIÓN DEL PERFIL
     ========================================================= --}}

@props([
    'id' => 'modalEditarPerfil',
    'eyebrow' => null,
    'title' => null,
    'description' => null,

    'passwordModel' => 'password',
    'submitAction',

    'confirmText' => 'Confirmar',
    'loadingText' => 'Comprobando...',
    'buttonClass' => 'aero-button',

    'openEvent' => 'abrir-modal-perfil',
    'closeEvent' => 'cerrar-modal-perfil',
    'cleanupEvent' => null,
])

{{-- Estado y eventos del modal
     --------------------------------------------------------- --}}

<div
    id="{{ $id }}"
    class="modal-overlay modal-aquim modal-editar-perfil"
    x-data="{ show: false }"
    x-on:{{ $openEvent }}.window="if (window.location.hash) { history.replaceState(null, '', window.location.pathname + window.location.search); } show = true;"
    x-on:{{ $closeEvent }}.window="show = false; $wire.set('{{ $passwordModel }}', ''); @if ($cleanupEvent) window.dispatchEvent(new CustomEvent('{{ $cleanupEvent }}')); @endif"
    x-on:keydown.escape.window="if (show) { show = false; $wire.set('{{ $passwordModel }}', ''); @if ($cleanupEvent) window.dispatchEvent(new CustomEvent('{{ $cleanupEvent }}')); @endif }"
    x-on:click.self="show = false; $wire.set('{{ $passwordModel }}', ''); @if ($cleanupEvent) window.dispatchEvent(new CustomEvent('{{ $cleanupEvent }}')); @endif"
    x-show="show"
    x-cloak
>
    {{-- Estructura y cabecera
         --------------------------------------------------------- --}}

    <section class="modal-content aero-panel" role="dialog" aria-modal="true" @if ($title) aria-labelledby="{{ $id }}-title" @endif x-on:click.stop>
        <button type="button" class="close-modal" title="{{ __('Cerrar') }}" aria-label="{{ __('Cerrar') }}"
            x-on:click="show = false; $wire.set('{{ $passwordModel }}', ''); @if ($cleanupEvent) window.dispatchEvent(new CustomEvent('{{ $cleanupEvent }}')); @endif">✖</button>

        @if ($eyebrow || $title)
            <header class="modal-header">
                <div>
                    @if ($eyebrow)
                        <span class="modal-eyebrow">{{ $eyebrow }}</span>
                    @endif

                    @if ($title)
                        <h2 id="{{ $id }}-title" class="upload-modal-title">{{ $title }}</h2>
                    @endif
                </div>
            </header>
        @endif

        {{-- Formulario y acciones
             --------------------------------------------------------- --}}

        <form wire:submit.prevent="{{ $submitAction }}">
            <div class="account-modal__body">
                @if ($description)
                    <p class="account-modal__description">{{ $description }}</p>
                @endif

                <div class="form-group">
                    <label for="{{ $id }}-password">{{ __('Contraseña') }}</label>
                    <input id="{{ $id }}-password" type="password" class="aero-input" autocomplete="current-password" placeholder="{{ __('Contraseña actual') }}" wire:model="{{ $passwordModel }}">
                </div>
            </div>

            <footer class="modal-footer">
                <button type="button" class="modal-cancel-button"
                    x-on:click="show = false; $wire.set('{{ $passwordModel }}', ''); @if ($cleanupEvent) window.dispatchEvent(new CustomEvent('{{ $cleanupEvent }}')); @endif">{{ __('Cancelar') }}</button>

                <button type="submit" class="{{ $buttonClass }}" wire:loading.attr="disabled" wire:target="{{ $submitAction }}">
                    <span wire:loading.remove wire:target="{{ $submitAction }}">{{ $confirmText }}</span>
                    <span wire:loading wire:target="{{ $submitAction }}">{{ $loadingText }}</span>
                </button>
            </footer>
        </form>
    </section>
</div>
