{{-- =========================================================
     MENÚ DESPLEGABLE DE LA CABECERA
     ========================================================= --}}

<section class="layout">
    <div class="aero-panel">
        <aside class="aero-panel-sticky sidebar">
            @auth
                {{-- Identidad del usuario --}}
                <div class="sidebar-user-row">
                
                <x-avatar :user="Auth::user()" class="avatar" />

                    <div class="sidebar-user-text nombre-usuario-contenedor">
                        <strong
                            class="nombre-usuario-recortado sidebar-user-name"
                            title="{{ Auth::user()->nick ?? 'usuario-desconocido' }}"
                        >{{ Auth::user()->nick ?? 'usuario-desconocido' }}</strong>
                        <small class="sidebar-user-meta"></small>
                    </div>
                </div>
            @endauth

            {{-- Navegación principal --}}
            <button type="button" class="glass-button" onclick="window.location.href='{{ auth()->check() ? '/dashboard' : '/' }}'">Inicio Aero</button>

            @auth
                <button type="button" class="glass-button" onclick="window.location.href='{{ route('usuario', ['usuario' => Auth::user()->nick]) }}'">Mi Espacio</button>
                <button type="button" class="glass-button" onclick="window.location.href='{{ route('profile.show') }}'">{{ __('Editar Perfil') }}</button>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="glass-button">{{ __('Cerrar Sesión') }}</button>
                </form>
            @endauth
        </aside>
    </div>