{{-- =========================================================
     BARRA LATERAL DE ESTADÍSTICAS Y ACTIVIDAD
     ========================================================= --}}

@props([
    'titulo' => '',
    'images' => null,
    'recentActivity' => null,
])

@auth
    <aside class="rightbar nav">

        {{-- Resumen del usuario --}}
        <div class="aero-panel">
            <h3 class="stats-title">Estadísticas</h3>

            <div class="stats-grid">
                <div class="stat-card"><strong class="stat-value">{{ auth()->user()->images()->count() }}</strong> Fotos subidas</div>
                <div class="stat-card">Calidad del aire <strong class="stat-value" id="widget-temp">89%</strong></div>
            </div>
        </div>

        {{-- Actividad reciente de las cuentas seguidas --}}
        <div class="aero-panel">
            <div class="tarjeta-verde">
                <h3>ÚLTIMA ACTIVIDAD</h3>

                @if (collect($recentActivity)->isNotEmpty())
                    <ul class="lista-actividad">
                        @foreach ($recentActivity as $activity)
                            <li>
                                <a href="{{ route('usuario', ['usuario' => $activity->user->nick]) }}"><x-avatar :user="$activity->user" class="avatar" /></a>

                                <div class="info-actividad">
                                  <a href="{{ route('usuario', ['usuario' => $activity->user->nick]) }}" title="{{ $activity->user->nick }}"><span class="nombre-usuario">{{ $activity->user->nick }}</span></a>
                                   <span class="tiempo-actividad">• {{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                @elseif (auth()->user()->following()->exists())
                    <p class="sin-actividad">Todavía no hay publicaciones de las personas que sigues.</p>

                @else
                    <p class="sin-actividad">Sigue a nuevos usuarios para ver su actividad aquí.</p>
                @endif
            </div>
        </div>

    </aside>
@endauth