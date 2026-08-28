{{-- =========================================================
     MODAL PARA SUBIR IMÁGENES
     ========================================================= --}}

@auth
    <div id="uploadModal" class="modal-overlay">
        <section class="modal-content aero-panel" role="dialog" aria-modal="true" aria-labelledby="uploadModalTitle">
            <a href="#" class="close-modal" title="Cerrar" aria-label="Cerrar">✖</a>

            <header class="modal-header">
                <div>
                    <span class="modal-eyebrow">Crear contenido</span>
                    <h2 id="uploadModalTitle" class="upload-modal-title">Nueva publicación</h2>
                </div>
            </header>

            <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data" data-redirect="{{ route('dashboard') }}">
                @csrf

                <div class="upload-layout">
                    {{-- Selección y descripción de la imagen --}}
                    <div class="modalform">
                        <div class="form-group upload-group">
                            <label for="input-imagen">Imagen</label>

                            <label for="input-imagen" class="file-picker">
                                <span class="file-picker__icon" aria-hidden="true">＋</span>
                                <span class="file-picker__copy">
                                    <strong>Seleccionar una imagen</strong>
                                    <small>JPG, PNG o WebP</small>
                                </span>
                            </label>

                            <input type="file" id="input-imagen" class="file-picker__input" accept="image/*">
                            <input type="hidden" name="image_path" id="imagen_recortada" required>
                        </div>

                        <div class="form-group description-group">
                            <div class="field-heading">
                                <label for="descriptionEdit">Descripción</label>
                                <span id="charCountEdit" class="character-counter">0 / 2200</span>
                            </div>

                            <textarea id="descriptionEdit" name="description" rows="3" maxlength="2200" placeholder="Escribe una descripción…"></textarea>
                        </div>
                    </div>

                    {{-- Previsualización y relación de aspecto --}}
                    <div class="modalimg" aria-label="Editor de imagen">
                        <div class="preview-heading">
                            <span>Previsualización</span>
                            <small>Ajusta el encuadre antes de publicar</small>
                        </div>

                        <div class="menu-aspectos" id="menu-aspectos">
                            <button type="button" class="opcion-btn" data-ratio="1.7777"><span>16:9</span><span class="icono-panoramico" aria-hidden="true"></span></button>
                        </div>

                        <div class="contenedor-crop">
                            <img id="imagen-a-recortar" alt="Previsualización de la imagen seleccionada">
                        </div>
                    </div>
                </div>

                {{-- Acciones del modal --}}
                <footer class="modal-footer">
                    <a href="#" class="modal-cancel-button">Cancelar</a>
                    <button id="btn-enviar" type="submit" class="aero-button publish-button">Publicar</button>
                </footer>
            </form>
        </section>
    </div>
@endauth
