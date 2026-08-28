@extends('layouts.aquim')

@section('title', 'aquim Aero')

{{-- =========================================================
     RECURSOS DE LA VISTA
     ========================================================= --}}

@push('head')
    @livewireStyles
@endpush

@push('styles')
    @vite([
        'resources/css/index.css',
        'resources/js/scroll.js',
        'resources/js/likes.js',
    ])
@endpush

{{-- =========================================================
     CONTENIDO PRINCIPAL
     ========================================================= --}}

@section('content')
    <div class="workspace">
        <x-header2 titulo="HeaderIzquierdo" />

        <section class="main-feed">
            <div class="feed">
                @forelse ($images as $image)
                    <article class="post-card">
                        <div class="post-inner">
                            <header class="post-header">
                                <div class="author">
                                    <a href="{{ route('usuario', ['usuario' => $image->user->nick ?? 'usuario-desconocido']) }}"><x-avatar :user="$image->user" class="avatar" /></a>

                                    <div class="nombre-usuario-contenedor">
                                        <strong class="nombre-usuario-recortado" title="{{ $image->user->nick ?? 'usuario-desconocido' }}">
                                            <a href="{{ route('usuario', ['usuario' => $image->user->nick ?? 'usuario-desconocido']) }}">{{ $image->user->nick ?? 'usuario-desconocido' }}</a>
                                        </strong>

                                        <span>{{ $image->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </header>

                            <div class="photo-frame">
                                <a href="{{ route('unaimagen', ['id' => $image->id]) }}">
                                    <img src="{{ asset('storage/images/' . $image->image_path) }}" class="imagen-real" alt="Imagen del feed" decoding="async"
                                        @if ($loop->first)
                                            fetchpriority="high" loading="eager"
                                        @else
                                            loading="lazy"
                                        @endif
                                    >
                                </a>

                                @if (!empty($image->description))
                                    @php
                                        $lineas = explode("\n", $image->description);
                                        $primeraLinea = $lineas[0];
                                    @endphp

                                    <div class="photo-caption">{{ $primeraLinea }}{{ count($lineas) > 1 ? '...' : '' }}</div>
                                @endif
                            </div>

                            <div class="post-actions">
                                <div class="left-actions">
                                    <button class="glass-button btn-like btn-imagen {{ $image->has_liked ? 'liked' : '' }}" data-id="{{ $image->id }}">
                                        <svg class="icon-heart" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                            <path d="M12 21.35 10.55 20.03C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09 C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5 c0 3.78-3.4 6.86-8.55 11.54L12 21.35Z" />
                                        </svg>
                                    </button>

                                    @php
                                        $likesCount = (int) ($image->likes_count ?? 0);

                                        if ($likesCount >= 1000000) {
                                            $likesFormateados = rtrim(rtrim(number_format($likesCount / 1000000, 1, ',', '.'), '0'), ',') . ' M';
                                        } elseif ($likesCount >= 1000) {
                                            $decimales = $likesCount < 10000 ? 1 : 0;
                                            $likesFormateados = rtrim(rtrim(number_format($likesCount / 1000, $decimales, ',', '.'), '0'), ',') . ' mil';
                                        } else {
                                            $likesFormateados = (string) $likesCount;
                                        }
                                    @endphp

                                    <span class="numero numerolike">{{ $likesFormateados }}</span>

                                    <button class="glass-button btn-imagen" title="Comentarios" aria-label="Comentarios">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none" aria-hidden="true">
                                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5 a8.48 8.48 0 0 1 8 8v.5z" />
                                        </svg>
                                    </button>

                                    <span class="numero">{{ $image->comments_count }}</span>

                                    <button class="glass-button btn-imagen" title="Compartir" aria-label="Compartir">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none" aria-hidden="true">
                                            <path d="M14 5.25c0-.67.77-1.05 1.3-.63l6.75 5.4a.8.8 0 0 1 0 1.26 l-6.75 5.4c-.53.42-1.3.04-1.3-.63v-2.78 c-3.26.08-5.85.67-7.94 1.92-2.16 1.29-3.84 3.3-5.31 6.33 -.27.56-1.12.38-1.14-.24-.15-4.33 1.02-7.82 3.48-10.27 C5.64 8.5 9.22 7.32 14 7.13V5.25Z" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="right-actions">
                                    <button class="glass-button btn-imagen" title="Guardar" aria-label="Guardar">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white" stroke="none" aria-hidden="true">
                                            <path d="M7 3.25A2.75 2.75 0 0 0 4.25 6v14.878 c0 .745.84 1.18 1.447.75L12 17.414l6.303 4.214 c.607.406 1.447-.005 1.447-.75V6A2.75 2.75 0 0 0 17 3.25H7Z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="post-card">
                        <div class="post-inner text-center feed-empty">
                            <div class="avatar feed-empty__icon">🌱</div>
                            <strong class="feed-empty__title">Aún no hay imágenes</strong>
                            <p class="feed-empty__text">Sube tu primera foto para empezar el feed.</p>
                        </div>
                    </article>
                @endforelse

                <div id="loading" class="feed-loading">Cargando más fotos...</div>
            </div>
        </section>

        <x-header3 titulo="HeaderDerecho" />
    </div>
@endsection

{{-- =========================================================
     SCRIPTS DE LA VISTA
     ========================================================= --}}

@push('scripts')
    @livewireScripts

    <script>
        window.nextPageUrl = "{{ $images->nextPageUrl() }}";
    </script>
@endpush
