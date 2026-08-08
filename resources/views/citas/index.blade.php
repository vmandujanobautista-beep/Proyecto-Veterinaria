<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Citas</h2>
                <p class="text-sm text-slate-500 mt-0.5">Agenda y gestión de consultas veterinarias</p>
            </div>
            <button type="button"
                    id="btn-agendar-cita"
                    @click="$dispatch('agendar-cita')"
                    class="ml-4 mt-2 inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700
                           text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-all
                           duration-200 shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Agendar Cita
            </button>
        </div>
    </x-slot>

    {{-- ── STATS ROW ── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
        @php
            $estadoStats = [
                ['label' => 'Pendientes',  'key' => 'pendiente',  'color' => 'text-amber-600 bg-amber-50 border-amber-200',     'emoji' => '⏳'],
                ['label' => 'Confirmadas', 'key' => 'confirmada', 'color' => 'text-sky-600 bg-sky-50 border-sky-200',            'emoji' => '✅'],
                ['label' => 'Completadas', 'key' => 'completada', 'color' => 'text-emerald-600 bg-emerald-50 border-emerald-200','emoji' => '🏁'],
                ['label' => 'Canceladas',  'key' => 'cancelada',  'color' => 'text-rose-600 bg-rose-50 border-rose-200',         'emoji' => '❌'],
            ];
        @endphp
        @foreach($estadoStats as $stat)
            <a href="{{ route('citas.index', array_merge(request()->except('estado','page'), ['estado' => request('estado') === $stat['key'] ? '' : $stat['key']])) }}"
               class="flex items-center gap-3 p-3 rounded-xl border {{ $stat['color'] }} transition-all
                      hover:shadow-sm {{ request('estado') === $stat['key'] ? 'ring-2 ring-offset-1 ring-violet-400' : '' }}">
                <span class="text-xl">{{ $stat['emoji'] }}</span>
                <div>
                    <p class="text-xs font-medium opacity-70">{{ $stat['label'] }}</p>
                    <p class="text-lg font-bold">{{ $conteoEstados[$stat['key']] ?? 0 }}</p>
                </div>
            </a>
        @endforeach
    </div>

    {{-- ── FILTROS ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('citas.index') }}" class="flex flex-col gap-3">

            {{-- Fila 1: buscador + rango personalizado --}}
            <div class="flex flex-col lg:flex-row gap-3">
                {{-- Buscador --}}
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text"
                           id="buscar-cita"
                           name="buscar"
                           value="{{ request('buscar') }}"
                           placeholder="Buscar por cliente, mascota o servicio..."
                           class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl
                                  focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                  bg-slate-50 transition-all">
                </div>

                {{-- Rango personalizado --}}
                <div class="flex flex-wrap items-center gap-2">
                    <input type="date"
                           id="filtro-fecha-desde"
                           name="fecha_desde"
                           value="{{ request('fecha_desde') }}"
                           class="px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none
                                  focus:ring-2 focus:ring-violet-500 bg-slate-50 text-slate-700 transition-all">
                    <span class="text-slate-400 text-sm">—</span>
                    <input type="date"
                           id="filtro-fecha-hasta"
                           name="fecha_hasta"
                           value="{{ request('fecha_hasta') }}"
                           class="px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none
                                  focus:ring-2 focus:ring-violet-500 bg-slate-50 text-slate-700 transition-all">
                    <button type="submit"
                            class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl transition-colors">
                        Filtrar
                    </button>
                    @if(request()->hasAny(['buscar','estado','fecha_filtro','fecha_desde','fecha_hasta']))
                        <a href="{{ route('citas.index') }}"
                           class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                            Limpiar
                        </a>
                    @endif
                </div>
            </div>

            {{-- Fila 2: Accesos rápidos de fecha --}}
            <div class="flex gap-2 flex-wrap items-center">
                <span class="text-xs text-slate-500 font-medium mr-1">Filtros rápidos:</span>
                @foreach([['hoy','Hoy'],['semana','Esta semana'],['mes','Este mes']] as [$val,$lbl])
                    <a href="{{ route('citas.index', array_merge(request()->except('fecha_filtro','fecha_desde','fecha_hasta','page'), ['fecha_filtro' => request('fecha_filtro') === $val ? '' : $val])) }}"
                       class="px-3 py-1.5 text-xs font-medium rounded-lg border transition-colors
                              {{ request('fecha_filtro') === $val
                                  ? 'bg-violet-600 text-white border-violet-600'
                                  : 'border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                        {{ $lbl }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    {{-- ── TABLA ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        @if($citas->isNotEmpty())
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando <span class="text-slate-800 font-bold">{{ $citas->count() }}</span> de
                    <span class="text-slate-800 font-bold">{{ $citas->total() }}</span> citas
                </p>
                @if(request()->hasAny(['buscar','estado','fecha_filtro','fecha_desde','fecha_hasta']))
                    <span class="text-xs text-violet-600 font-medium">Filtros activos</span>
                @endif
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                        <th class="text-left px-5 py-3.5 font-semibold">Cliente</th>
                        <th class="text-left px-5 py-3.5 font-semibold">Mascota</th>
                        <th class="text-left px-5 py-3.5 font-semibold">Fecha</th>
                        <th class="text-left px-5 py-3.5 font-semibold hidden sm:table-cell">Hora</th>
                        <th class="text-left px-5 py-3.5 font-semibold hidden lg:table-cell">Servicio</th>
                        <th class="text-left px-5 py-3.5 font-semibold">Precio</th>
                        <th class="text-center px-5 py-3.5 font-semibold">Estado</th>
                        <th class="text-center px-5 py-3.5 font-semibold hidden md:table-cell">Notif.</th>
                        <th class="text-center px-5 py-3.5 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($citas as $cita)
                        @php
                            $estadoConfig = match($cita->estado ?? 'pendiente') {
                                'pendiente'  => ['class' => 'bg-amber-100 text-amber-700',   'label' => 'Pendiente',  'dot' => 'bg-amber-500'],
                                'confirmada' => ['class' => 'bg-sky-100 text-sky-700',       'label' => 'Confirmada', 'dot' => 'bg-sky-500'],
                                'completada' => ['class' => 'bg-emerald-100 text-emerald-700','label' => 'Completada','dot' => 'bg-emerald-500'],
                                'cancelada'  => ['class' => 'bg-rose-100 text-rose-700',     'label' => 'Cancelada',  'dot' => 'bg-rose-500'],
                                default      => ['class' => 'bg-slate-100 text-slate-600',   'label' => ucfirst($cita->estado ?? ''), 'dot' => 'bg-slate-400'],
                            };
                            $esHoy    = $cita->fecha->isToday();
                            $esMañana = $cita->fecha->isTomorrow();
                            $esPasada = $cita->fecha->isPast() && !$esHoy;

                            $especieIcono = match($cita->mascota?->especie ?? '') {
                                'Perro'  => '🐶', 'Gato'   => '🐱',
                                'Ave'    => '🦜', 'Conejo' => '🐰',
                                'Reptil' => '🦎', default  => '🐾',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $esHoy ? 'bg-violet-50/40' : '' }}">

                            {{-- Cliente --}}
                            <td class="px-5 py-3.5">
                                @if($cita->cliente)
                                    <a href="{{ route('clientes.show', $cita->cliente) }}"
                                       class="font-medium text-slate-800 hover:text-violet-600 transition-colors hover:underline text-sm">
                                        {{ $cita->cliente->nombre }}
                                        {{ $cita->cliente->apellido_paterno ?? $cita->cliente->apellido }}
                                    </a>
                                    @if($cita->cliente->telefono)
                                        <p class="text-xs text-slate-400 mt-0.5">{{ $cita->cliente->telefono }}</p>
                                    @endif
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Mascota --}}
                            <td class="px-5 py-3.5">
                                @if($cita->mascota)
                                    <div class="flex items-center gap-2">
                                        <span class="text-base">{{ $especieIcono }}</span>
                                        <div>
                                            <p class="font-medium text-slate-800 text-sm">{{ $cita->mascota->nombre }}</p>
                                            <p class="text-xs text-slate-400">{{ $cita->mascota->especie }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>

                            {{-- Fecha --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-1.5">
                                    @if($esHoy)
                                        <span class="w-1.5 h-1.5 rounded-full bg-violet-500 flex-shrink-0 animate-pulse"></span>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-sm {{ $esPasada ? 'text-slate-400' : 'text-slate-800' }}">
                                            {{ $cita->fecha->format('d/m/Y') }}
                                        </p>
                                        @if($esHoy)
                                            <span class="text-xs bg-violet-100 text-violet-700 font-bold px-1.5 py-0.5 rounded">HOY</span>
                                        @elseif($esMañana)
                                            <span class="text-xs bg-sky-100 text-sky-700 font-bold px-1.5 py-0.5 rounded">MAÑANA</span>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Hora --}}
                            <td class="px-5 py-3.5 hidden sm:table-cell">
                                <span class="text-sm font-mono text-slate-700">{{ $cita->hora }}</span>
                            </td>

                            {{-- Servicio --}}
                            <td class="px-5 py-3.5 hidden lg:table-cell">
                                <p class="text-sm text-slate-700 font-medium">{{ $cita->tipo_servicio ?? 'Consulta general' }}</p>
                                @if($cita->motivo)
                                    <p class="text-xs text-slate-400 mt-0.5 truncate max-w-[160px]" title="{{ $cita->motivo }}">
                                        {{ $cita->motivo }}
                                    </p>
                                @endif
                            </td>

                            {{-- Precio --}}
                            <td class="px-5 py-3.5">
                                <span class="text-sm font-semibold text-emerald-600">
                                    {{ $cita->precio ? '$' . number_format($cita->precio, 2) : '—' }}
                                </span>
                            </td>

                            {{-- Estado --}}
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $estadoConfig['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $estadoConfig['dot'] }}"></span>
                                    {{ $estadoConfig['label'] }}
                                </span>
                            </td>

                            {{-- Notificaciones --}}
                            <td class="px-5 py-3.5 text-center hidden md:table-cell">
                                <div class="flex items-center justify-center gap-2">
                                    <span title="{{ $cita->enviado_email ? 'Email enviado' : 'Email no enviado' }}"
                                          class="text-base {{ $cita->enviado_email ? 'opacity-100' : 'opacity-20 grayscale' }}">📧</span>
                                    <span title="{{ $cita->enviado_whatsapp ? 'WhatsApp enviado' : 'WhatsApp no enviado' }}"
                                          class="text-base {{ $cita->enviado_whatsapp ? 'opacity-100' : 'opacity-20 grayscale' }}">📱</span>
                                </div>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1">

                                    {{-- Ver detalle --}}
                                    <button type="button"
                                            title="Ver detalle"
                                            @click="$dispatch('ver-cita', { id: {{ $cita->id }} })"
                                            class="p-1.5 text-slate-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>

                                    {{-- Confirmar (solo si pendiente) --}}
                                    @if($cita->estado === 'pendiente')
                                        <form method="POST" action="{{ route('citas.confirmar', $cita) }}">
                                            @csrf
                                            <button type="submit"
                                                    title="Confirmar cita"
                                                    class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Completar (pendiente o confirmada) --}}
                                    @if(in_array($cita->estado, ['pendiente','confirmada']))
                                        <form method="POST" action="{{ route('citas.completar', $cita) }}">
                                            @csrf
                                            <button type="submit"
                                                    title="Marcar como completada"
                                                    class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Cancelar (no canceladas ni completadas) --}}
                                    @if(!in_array($cita->estado, ['cancelada','completada']))
                                        <button type="button"
                                                title="Cancelar cita"
                                                @click="$dispatch('cancelar-cita', { id: {{ $cita->id }} })"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors group relative">
                                            <svg class="w-4 h-4 overflow-visible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M18 6l-12 12" class="origin-center transition-transform duration-200 ease-out group-hover:rotate-[15deg] group-hover:scale-110" />
                                                <path d="M6 6l12 12" class="origin-center transition-transform duration-200 ease-out group-hover:-rotate-[15deg] group-hover:scale-110" />
                                            </svg>
                                        </button>
                                    @endif

                                    {{-- Email --}}
                                    <form method="POST" action="{{ route('citas.enviar-email', $cita) }}">
                                        @csrf
                                        <button type="submit"
                                                title="{{ $cita->enviado_email ? 'Reenviar email' : 'Enviar recordatorio por email' }}"
                                                class="p-1.5 rounded-lg transition-colors
                                                       {{ $cita->enviado_email
                                                           ? 'text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50'
                                                           : 'text-slate-300 hover:text-slate-500 hover:bg-slate-50' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- WhatsApp --}}
                                    <form method="POST" action="{{ route('citas.enviar-whatsapp', $cita) }}">
                                        @csrf
                                        <button type="submit"
                                                title="{{ $cita->enviado_whatsapp ? 'Reenviar WhatsApp' : 'Enviar recordatorio por WhatsApp' }}"
                                                class="p-1.5 rounded-lg transition-colors
                                                       {{ $cita->enviado_whatsapp
                                                           ? 'text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50'
                                                           : 'text-slate-300 hover:text-slate-500 hover:bg-slate-50' }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- Editar --}}
                                    <a href="{{ route('citas.edit', $cita) }}"
                                       title="Editar cita"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-6xl mb-4">📅</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request()->hasAny(['buscar','estado','fecha_filtro','fecha_desde','fecha_hasta'])
                                            ? 'No se encontraron citas'
                                            : 'No hay citas agendadas' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request()->hasAny(['buscar','estado','fecha_filtro','fecha_desde','fecha_hasta'])
                                            ? 'Intenta con otros filtros o limpia la búsqueda.'
                                            : 'Agenda la primera cita de tu clínica.' }}
                                    </p>
                                    @if(!request()->hasAny(['buscar','estado','fecha_filtro','fecha_desde','fecha_hasta']))
                                        <button type="button"
                                                @click="$dispatch('agendar-cita')"
                                                class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700
                                                       text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Agendar Primera Cita
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($citas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $citas->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
