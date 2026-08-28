@if ($errors->any())
    <div
        wire:key="livewire-error-{{ uniqid() }}"
        x-init="
            $nextTick(() => {
                window.notificar(
                    'error',
                    @js($errors->first())
                );
            });
        "
    ></div>
@endif