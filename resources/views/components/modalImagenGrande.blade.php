{{-- =========================================================
     VISOR DE IMAGEN AMPLIADA
     ========================================================= --}}

@props(['image'])

<div id="imagenGrande" class="visor-imagen" aria-hidden="true">
    <a href="#imagen-publicacion" class="visor-imagen__fondo" aria-label="Cerrar imagen ampliada"></a>

    <div class="visor-imagen__contenido" role="dialog" aria-modal="true" aria-label="Imagen ampliada">
        <a href="#imagen-publicacion" class="visor-imagen__cerrar" aria-label="Cerrar imagen ampliada">×</a>
        <img src="{{ asset('storage/images/' . $image->image_path) }}" alt="Imagen ampliada de la publicación">
    </div>
</div>
