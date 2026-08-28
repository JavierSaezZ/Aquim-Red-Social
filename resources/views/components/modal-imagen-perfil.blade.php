{{-- =========================================================
     MODAL PARA CAMBIAR LA FOTO DE PERFIL
     ========================================================= --}}

@auth
    <div id="profileImageModal" class="modal-overlay" wire:ignore>
        <section class="modal-content aero-panel" role="dialog" aria-modal="true" aria-labelledby="profileImageModalTitle">
            <a href="#" class="close-modal" data-close-profile-crop title="{{ __('Cerrar') }}" aria-label="{{ __('Cerrar') }}">✖</a>

            <header class="modal-header">
                <div>
                    <span class="modal-eyebrow">{{ __('Foto de perfil') }}</span>
                    <h2 id="profileImageModalTitle" class="upload-modal-title">{{ __('Ajustar imagen') }}</h2>
                </div>
            </header>

            <form id="profile-photo-crop-form" onsubmit="return false;">
                
                {{-- Selección y recorte de la imagen --}}
                <div class="upload-layout">
                    <div class="modalform">
                        <div class="form-group upload-group">
                            <label for="input-imagen-perfil">{{ __('Imagen') }}</label>

                            <label for="input-imagen-perfil" class="file-picker">
                                <span class="file-picker__icon" aria-hidden="true">＋</span>
                                <span class="file-picker__copy">
                                    <strong>{{ __('Seleccionar una imagen') }}</strong>
                                    <small id="nombre-imagen-perfil">JPG, PNG o WebP</small>
                                </span>
                            </label>

                            <input type="file" id="input-imagen-perfil" class="file-picker__input" accept="image/jpeg,image/png,image/webp">
                        </div>
                    </div>

                    <div class="modalimg" aria-label="{{ __('Editor de foto de perfil') }}">
                        <div class="preview-heading">
                            <span>{{ __('Previsualización') }}</span>
                            <small>{{ __('Ajusta el recorte cuadrado') }}</small>
                        </div>

                       <div id="contenedor-crop-perfil" class="contenedor-crop-perfil">
                            <img id="imagen-a-recortar-perfil" alt="{{ __('Previsualización de la foto seleccionada') }}">
                        </div>
                    </div>
                </div>

                {{-- Acciones del modal --}}
                <footer class="modal-footer">
                    <a href="#" class="modal-cancel-button" data-close-profile-crop>{{ __('Cancelar') }}</a>
                    <button id="btn-aplicar-perfil" type="button" class="aero-button publish-button profile-apply-photo">{{ __('Usar foto') }}</button>
                </footer>
            </form>
        </section>
    </div>
@endauth
