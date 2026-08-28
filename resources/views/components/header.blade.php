{{-- =========================================================
     CABECERA PRINCIPAL
     ========================================================= --}}

<header class="topbar">
    {{-- Marca y acceso al inicio --}}
    <div class="brand">
        <a href="{{ auth()->check() ? '/dashboard' : '/' }}">
            <div class="brand-logo"><img src="{{ asset('images/logo/AquimLogo-128.webp') }}" alt="AQUIM"></div>
        </a>
    </div>

    {{-- Buscador y panel de resultados --}}
    <div class="search-shell">
        <input type="search" id="inputBuscador" autocomplete="off" placeholder="Buscar imágenes, jardines, usuarios..." />
        <div class="search-orb">⌕</div>
        <div id="panelResultados" class="search-dropdown" style="display: none;"></div>
    </div>

    {{-- Acciones según el estado de autenticación --}}
    <div class="top-actions">
        @auth
            <div class="menu-flotante-container" x-data="{ openMenu: false }">
                <button @click="openMenu = !openMenu" class="icon-button" title="Menú">✦ </button>

                <div x-show="openMenu" @click.away="openMenu = false" class="dropdown-menu-flotante" style="display: none;">
                    <x-header2 />
                </div>
            </div>

            <a href="#uploadModal" class="aero-button">Subir imagen</a>
        @endauth

        @guest
            <a href="{{ route('login') }}" class="aero-button">Iniciar sesión</a>
        @endguest
    </div>
</header>
