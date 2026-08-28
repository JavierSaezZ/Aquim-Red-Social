@extends('layouts.aquim')

@section('title', 'Detalle de Imagen - aquim Aero')

@section('description', 'Publicación de ' . ($image->user?->name ?? 'un usuario') . ' en aquim Aero. Descubre la imagen y participa en los comentarios.')

{{-- =========================================================
   RECURSOS DE LA VISTA
   ========================================================= --}}

@push('head')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@push('styles')
    @vite([
        'resources/css/imagen.css',
        'resources/js/likes.js',
        'resources/js/comments.js',
        'resources/js/menusimagen.js',
    ])
@endpush

{{-- =========================================================
   CONTENIDO PRINCIPAL
   ========================================================= --}}

@section('content')

    @php
        $formatearCantidad = function ($numero) {
            $numero = (int) $numero;

            if ($numero >= 1000000) {
                return rtrim(rtrim(number_format($numero / 1000000, 1, ',', '.'), '0'), ',') . ' M';
            }

            if ($numero >= 1000) {
                $decimales = $numero < 10000 ? 1 : 0;
                $valor = number_format($numero / 1000, $decimales, ',', '.');

                if ($decimales > 0) {
                    $valor = rtrim(rtrim($valor, '0'), ',');
                }

                return $valor . ' mil';
            }

            return (string) $numero;
        };
    @endphp


    <div class="single-post-container" role="main">

        {{-- Imagen principal y acceso al modal de visualización ampliada y descarga de la imagen. --}}
        <div class="post-image-col">

            <div class="post-image-wrapper" id="imagen-publicacion">

                <img id="imagen" src="{{ asset('storage/images/' . $image->image_path) }}" alt="Imagen de la publicación">

                <a href="#imagenGrande" class="btn-ver-imagen" title="Ver imagen completa" aria-label="Ver imagen completa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </a>
                
                
            <a href="{{ asset('storage/images/original/' . $image->original_name) }}"download class="btn-descargar-imagen" title="Descargar imagen original" aria-label="Descargar imagen original">
    <svg xmlns="http://www.w3.org/2000/svg"  width="21"  height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <path d="M12 3v12" /><path d="m7 10 5 5 5-5" /><path d="M5 21h14" />
    </svg>
</a>

            </div>

        </div>

        <div class="post-sidebar">

            {{-- =========================================================
               AUTOR Y OPCIONES DE LA PUBLICACIÓN
               ========================================================= --}}

            <header class="sidebar-header">
                <div class="user">
                    <a href="{{ route('usuario', ['usuario' => $image->user->nick ?? 'usuario-desconocido']) }}"
                        class="sidebar-author-avatar" aria-label="Ver perfil de {{ $image->user?->nick ?? 'usuario' }}">
                        <x-avatar :user="$image->user" class="avatar" />
                    </a>

                    <div class="sidebar-author-text">
                        <a href="{{ route('usuario', ['usuario' => $image->user->nick ?? 'usuario-desconocido']) }}"
                            class="sidebar-author-name nombre-usuario-recortado"
                            title="{{ $image->user?->nick ?? 'usuario-desconocido' }}">
                            <strong class="nombre-usuario-recortado">{{ $image->user?->nick ?? 'usuario-desconocido' }}</strong>
                        </a>

                        <span class="sidebar-published">
                            Publicado el <span
                                class="post-date">{{ $image->created_at->locale('es')->translatedFormat('d M Y') }}</span>
                        </span>
                    </div>
                </div>

                <div class="options">
                    <div class="opciones-container">
                        <input type="checkbox" id="menu-toggle-1" class="checkbox-oculto">

                        <label for="menu-toggle-1" class="btn-tres-puntos">⋮</label>

                        <ul class="menu-desplegable">
                            {{-- Las acciones de edición y eliminación solo están disponibles para el autor de la publicación. --}}
                            @if (auth()->check() && auth()->user()->id == $image->user->id)
                                <li>
                                    <a href="#modalEditar" class="item-menu item-menu-bloque">Editar</a>
                                </li>

                                <li>
                                    <a href="#modalEliminar" class="item-menu peligro item-menu-bloque">Eliminar</a>
                                </li>
                            @endif
                            <li><button class="item-menu">Comprtir</button></li>
                            <li><button class="item-menu">Reportar</button></li>

                        </ul>
                    </div>
                </div>
            </header>

            {{-- =========================================================
               DESCRIPCIÓN Y COMENTARIOS
               ========================================================= --}}

            <div class="comments-list">

                {{-- La descripción de la publicación se muestra con el mismo formato visual que un comentario. --}}
                @if (!empty($image->description))
                    <a href="{{ route('usuario', ['usuario' => $image->user->nick ?? 'usuario-desconocido']) }}">

                        <div class="comment-item">
                            <x-avatar :user="$image->user" class="avatar" />
                            <div class="comment-content">
                                <p><strong class="nombre-usuario-inline" title="{{ $image->user?->nick ?? 'usuario-desconocido' }}">{{ $image->user?->nick ?? 'usuario-desconocido' }}</strong>
                    </a>
                    {!! nl2br(e($image->description)) !!}</p>
            </div>
        </div>
        @endif


        @foreach ($comments as $comment)


            <div class="comment-item">
                <a href="{{ route('usuario', ['usuario' => $comment->user->nick ?? 'usuario-desconocido']) }}">
                    <x-avatar :user="$comment->user" class="avatar" />

                    <div class="comment-content">
                        <p><strong class="nombre-usuario-inline" title="{{ $comment->user?->nick ?? 'usuario-desconocido' }}">{{ $comment->user?->nick ?? 'usuario-desconocido' }}</strong>
                </a>
                {!! nl2br(e($comment->content)) !!}
                </p>

                <div class="comment-meta">
                    <span>{{ $comment->time_ago = $comment->created_at->locale('es')->diffForHumans() }}</span>
                    <span>Me gusta</span>
                    <span>Responder</span>
                    <div class="options">
                        <div class="opciones-container">
                            {{-- Cada comentario utiliza su propio ID para que el menú desplegable se controle de forma independiente. --}}
                            <input type="checkbox" id="menu-toggle-comment-{{ $comment->id }}" class="checkbox-oculto">

                            <label for="menu-toggle-comment-{{ $comment->id }}" class="btn-tres-puntos-comentarios">⋮</label>

                            <ul class="menu-desplegable">
                                {{-- Puede eliminar el comentario su autor o el propietario de la publicación. --}}
                                @if (auth()->check() && (auth()->id() == $comment->user_id || auth()->id() == $image->user_id))
                                    <li>
                                        <form class="action-modal__form" action="{{ route('comentarios.destroy', $comment->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="item-menu peligro item-menu-bloque">
                                                Eliminar
                                            </button>
                                        </form>
                                    </li>
                                @else
                                    <li><button class="item-menu">Denunciar</button></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        @endforeach

        {{-- La URL de la siguiente página se conserva en el botón para la carga progresiva de más comentarios. --}}
        @if ($comments->nextPageUrl())
            <div class="load-more-container" id="loadMoreContainer">
                <button id="btnLoadMoreComments" class="btn-ig-more" data-next-url="{{ $comments->nextPageUrl() }}"
                    aria-label="Cargar más comentarios">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                </button>
            </div>
        @endif

            </div>

            {{-- =========================================================
               ACCIONES Y FORMULARIO DE COMENTARIOS
               ========================================================= --}}

            <footer class="sidebar-footer">
                <div class="action-icons">
                    <div class="action-icons-left">
                        <button class="glass-button btn-like btn-imagen {{ $image->has_liked ? 'liked' : '' }}"
                            data-id="{{ $image->id }}"> <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 21.35 10.55 20.03C5.4 15.36 2 12.28 2 8.5
                 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09
                 C13.09 3.81 14.76 3 16.5 3
                 19.58 3 22 5.42 22 8.5
                 c0 3.78-3.4 6.86-8.55 11.54L12 21.35Z" />
                            </svg></button> <span class="numero numerolike">{{ $formatearCantidad($image->likes_count ?? $image->likes->count()) }} </span>
                        <button class="glass-button btn-imagen " aria-label="Comentarios"><svg xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none">
                                <path
                                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                                </path>
                            </svg>
                        </button>
                        <span class="numero">
                            {{ $formatearCantidad($image->comments_count ?? $image->comments->count()) }}
                        </span>
                        </span>
                        <button class="glass-button btn-imagen " aria-label="Compartir publicación">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none"
                                aria-hidden="true">
                                <path
                                    d="M14 5.25c0-.67.77-1.05 1.3-.63l6.75 5.4a.8.8 0 0 1 0 1.26l-6.75 5.4c-.53.42-1.3.04-1.3-.63v-2.78c-3.26.08-5.85.67-7.94 1.92-2.16 1.29-3.84 3.3-5.31 6.33-.27.56-1.12.38-1.14-.24-.15-4.33 1.02-7.82 3.48-10.27C5.64 8.5 9.22 7.32 14 7.13V5.25Z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div>
                        <button class="glass-button btn-imagen " aria-label="Guardar publicación">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none"
                                aria-hidden="true">
                                <path
                                    d="M7 3.25A2.75 2.75 0 0 0 4.25 6v14.878c0 .745.84 1.18 1.447.75L12 17.414l6.303 4.214c.607.406 1.447-.005 1.447-.75V6A2.75 2.75 0 0 0 17 3.25H7Z">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>



                @auth
                    <form class="add-comment-form" action="{{ route('comentar.guardar', $image->id) }}" method="POST">
                        @csrf
                        <div class="comment-textarea-wrap">
                            <textarea id="commentText" name="contenido" maxlength="2200" rows="1" placeholder="Añad un comentario..."
                                aria-label="Añade un comentario" required autocomplete="off"></textarea>
                            <span id="charCountComment" class="character-counter comment-character-counter">0 / 2200</span>
                        </div>
                        <button class="glass-button btn-imagen" aria-label="Enviar comentario">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none"
                                aria-hidden="true">
                                <path
                                    d="M2.7 3.4c-.72-.42-1.58.2-1.36.99l2.23 7.11h7.18a.5.5 0 0 1 0 1H3.57l-2.23 7.11c-.22.79.64 1.41 1.36.99l18.1-7.75c.77-.33.77-1.42 0-1.75L2.7 3.4Z" />
                            </svg>
                        </button>
                    </form>


                @else
                    <p class="login-comment-message">
                        <a href="{{ route('login') }}">Inicia sesión</a> para comentar.
                    </p>
                @endauth
            </footer>

        </div>

    </div>

@endsection

{{-- =========================================================
   MODALES DE LA VISTA
   ========================================================= --}}

@push('modals-before')
    <x-modalImagenGrande :image="$image" />
@endpush

@push('modals')
    <x-modalEditDelete :image="$image" />
@endpush