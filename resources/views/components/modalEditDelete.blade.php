{{-- =========================================================
     MODALES PARA EDITAR Y ELIMINAR PUBLICACIONES
     ========================================================= --}}

@props(['image'])

@auth
    @if (auth()->id() === $image->user_id)
        {{-- Edición de la publicación
             --------------------------------------------------------- --}}
        <div id="modalEditar" class="modal-overlay modal-action-overlay">
            <section class="modal-content aero-panel action-modal action-modal--edit" role="dialog" aria-modal="true" aria-labelledby="modalEditarTitle">
                <a href="#" class="close-modal action-modal__close" title="Cerrar" aria-label="Cerrar modal">✖</a>

                <header class="action-modal__header">
                    <span class="action-modal__eyebrow">Editar contenido</span>
                    <h2 id="modalEditarTitle" class="action-modal__title">Editar publicación</h2>
                    <p class="action-modal__subtitle">Modifica la descripción de tu publicación.</p>
                </header>

                <form class="action-modal__form" action="{{ route('publicaciones.update', $image->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="action-modal__field">
                        <div class="action-modal__field-heading">
                            <label for="descriptionEdit">Descripción</label>
                            <span id="charCountEdit" class="action-modal__counter">0 / 2200</span>
                        </div>

                        <textarea id="descriptionEdit" class="action-modal__textarea" name="description" rows="5" maxlength="2200" placeholder="Escribe una descripción…">{{ $image->description }}</textarea>
                    </div>

                    <footer class="action-modal__footer">
                        <a href="#" class="action-modal__cancel">Cancelar</a>
                        <button type="submit" class="aero-button action-modal__primary">Guardar cambios</button>
                    </footer>
                </form>
            </section>
        </div>

        {{-- Eliminación irreversible de la publicación
             --------------------------------------------------------- --}}
        <div id="modalEliminar" class="modal-overlay modal-action-overlay">
            <section
                class="modal-content aero-panel action-modal action-modal--delete"
                role="alertdialog"
                aria-modal="true"
                aria-labelledby="modalEliminarTitle"
                aria-describedby="modalEliminarDescription"
            >
                <a href="#" class="close-modal action-modal__close" title="Cerrar" aria-label="Cerrar modal">✖</a>

                <header class="action-modal__header action-modal__header--center">
                    <span class="action-modal__warning-icon" aria-hidden="true">!</span>
                    <span class="action-modal__eyebrow action-modal__eyebrow--danger">Acción irreversible</span>
                    <h2 id="modalEliminarTitle" class="action-modal__title action-modal__title--danger">Eliminar publicación</h2>
                    <p id="modalEliminarDescription" class="action-modal__subtitle action-modal__subtitle--center">
                        ¿Estás seguro de que quieres eliminar esta publicación?
                        Esta acción no se puede deshacer.
                    </p>
                </header>

                <form class="action-modal__form" action="{{ route('publicaciones.destroy', $image->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <footer class="action-modal__footer action-modal__footer--delete">
                        <a href="#" class="action-modal__cancel">Cancelar</a>
                        <button type="submit" class="action-modal__danger">Sí, eliminar</button>
                    </footer>
                </form>
            </section>
        </div>
    @endif
@endauth
