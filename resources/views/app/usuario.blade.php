@extends('layouts.aquim')

@section('title', 'Perfil de Usuario - aquim Aero')
@section('description', 'Perfil de ' . e($usuario->name) . ' en AQUIM. Descubre sus publicaciones y fotografías.')

{{-- =========================================================
     RECURSOS DE LA VISTA
     ========================================================= --}}

@section('auth-only-globals', '1')

@push('head')
    @livewireStyles
@endpush

@push('styles')
    @vite([
        'resources/css/usuario.css',
        'resources/css/index.css',
        'resources/js/seguir.js',
        'resources/js/usuario-galeria.js',
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

    <div class="workspace">
        <x-header2 titulo="Header" />

        <main class="main-feed">
            {{-- =========================================================
                 PERFIL DEL USUARIO
                 ========================================================= --}}

            <section class="profile-header">
                <x-avatar :user="$usuario" size="large" class="profile-avatar" />

                <div class="profile-info">
                    <div class="name-block">
                        <h2>{{ ($usuario->nick ?? 'user') }}</h2>
                        <span class="username">{{ $usuario->name }}</span>
                    </div>

                    <div class="profile-stats-simple">
                        <span><strong>{{ $usuario->images->count() }}</strong> publicaciones</span>
                        <span><strong>{{ $usuario->followers->count() }}</strong> seguidores</span>
                        <span><strong>{{ $usuario->likes->count() }}</strong> likes</span>
                    </div>

                    @if (!auth()->check() || auth()->id() !== $usuario->id)
                        <div class="profile-bio profile-description-text">{{ $usuario->description }}</div>

                        @auth
                            <div class="profile-actions">
                                <button class="aero-button btn-seguir {{ $usuario->isFollowedBy(auth()->id()) ? 'follow' : '' }}" data-id="{{ $usuario->id }}">
                                    {{ $usuario->isFollowedBy(auth()->id()) ? 'Siguiendo' : 'Seguir' }}
                                </button>

                                <button class="glass-button mensaje-button">Enviar mensaje</button>

                                <button class="icon-button profile-user-button" title="Añadir usuario" aria-label="Añadir usuario">
                                    <span class="profile-user-button__mobile" aria-hidden="true">
                                        <svg class="profile-user-button__desktop" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M15 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="8.5" cy="7" r="4"></circle>
                                            <path d="M19 8v6"></path>
                                            <path d="M22 11h-6"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        @endauth
                    @else
                        <form id="userDescriptionForm" action="{{ route('usuario.description.update') }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <textarea
                                id="descriptionEdit" data-user-description
                                class="user-modal__textarea profile-description-text" name="description"
                                rows="5" maxlength="650" placeholder="Escribe una descripción…"
                            >{{ $usuario->description }}</textarea>

                            <span id="charCountEdit"></span>
                            <button class="aero-button" type="submit" data-id="{{ $usuario->id }}">Guardar</button>
                        </form>
                    @endif
                </div>
            </section>

            {{-- =========================================================
                 GALERÍA DE PUBLICACIONES
                 ========================================================= --}}

            <section class="gallery-grid" id="galleryGrid">
                @if ($profileImages->isNotEmpty())
                    @foreach ($profileImages as $image)
                        <article class="photo-frame">
                            <a href="{{ route('unaimagen', ['id' => $image->id]) }}">
                                <img
                                    src="{{ asset('storage/images/feed/' . $image->image_path) }}" class="imagen-real" alt="Imagen del feed"
                                    loading="{{ $loop->index < 6 ? 'eager' : 'lazy' }}" decoding="async"
                                >

                                <div class="photo-overlay">
                                    <div class="overlay-stat">
                                        <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 21.35 10.55 20.03C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09 C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5 c0 3.78-3.4 6.86-8.55 11.54L12 21.35Z"/>
                                        </svg>

                                        {{ $formatearCantidad($image->likes_count ?? 0) }}
                                    </div>

                                    <div class="overlay-stat">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M21 11.5 a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9 L3 21 l1.9-5.7 a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9 h.5 a8.48 8.48 0 0 1 8 8 v.5z"></path>
                                        </svg>

                                        {{ $formatearCantidad($image->comments_count ?? 0) }}
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                @else
                    <div class="profile-gallery-empty">
                        <div class="profile-gallery-empty__icon" aria-hidden="true">📷</div>

                        @if (auth()->check() && auth()->id() === $usuario->id)
                            <strong>Todavía no has subido ninguna publicación</strong>
                            <span>Cuando publiques una imagen, aparecerá aquí.</span>
                        @else
                            <strong>Este usuario todavía no ha subido ninguna publicación</strong>
                            <span>Sus imágenes aparecerán aquí cuando empiece a publicar.</span>
                        @endif
                    </div>
                @endif
            </section>

            @if ($hasMoreImages)
                <div id="loadMoreGalleryContainer">
                    <button
                        type="button" id="btnLoadMoreGallery" class="aero-button"
                        data-next-offset="{{ $nextGalleryOffset }}" data-gallery-url="{{ url()->current() }}"
                        data-login-url="{{ route('login') }}" data-authenticated="{{ auth()->check() ? '1' : '0' }}"
                    >Cargar más</button>
                </div>
            @endif
        </main>

        @auth
            <x-header3 titulo="HeaderDerecho" />
        @endauth
    </div>
@endsection

@push('scripts')
    @livewireScripts
@endpush
