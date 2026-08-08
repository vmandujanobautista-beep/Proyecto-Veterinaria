<x-app-layout>
    <style>
        .btn-citas-purple {
            border: none;
            color: #fff !important;
            background-image: linear-gradient(30deg, #7e22ce, #c026d3);
            border-radius: 12px !important;
            background-size: 100% auto;
            transition: background-position 0.3s ease;
            cursor: pointer;
        }

        .btn-citas-purple:hover {
            background-position: right center;
            background-size: 200% auto;
            animation: pulse-purple 1.5s infinite;
        }

        @keyframes pulse-purple {
            0% { box-shadow: 0 0 0 0 rgba(168, 85, 247, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(168, 85, 247, 0); }
            100% { box-shadow: 0 0 0 0 rgba(168, 85, 247, 0); }
        }

        /* Eye Icon Animation */
        @keyframes blink-pupil {
            0% { transform: scale(1); }
            50% { transform: scale(0.2); }
            100% { transform: scale(1); }
        }
        @keyframes blink-eye {
            0% { transform: scaleY(1); }
            50% { transform: scaleY(0.6); }
            100% { transform: scaleY(1); }
        }
        .group\/btn-eye:hover .eye-pupil { animation: blink-pupil 0.4s ease-in-out; }
        .group\/btn-eye:hover .eye-shape { animation: blink-eye 0.4s ease-in-out; }
        .eye-pupil, .eye-shape { transform-origin: center; }

        /* User Check Icon Animation */
        @keyframes user-avatar-hover {
            from { transform: scale(1) translateY(0); }
            to { transform: scale(1.05) translateY(-1px); }
        }
        @keyframes check-mark-draw {
            0% { stroke-dashoffset: 20; transform: scale(1); }
            100% { stroke-dashoffset: 0; transform: scale(1.1); }
        }
        .group\/btn-check:hover .user-avatar { animation: user-avatar-hover 0.25s ease-out forwards; }
        .group\/btn-check:hover .check-mark { animation: check-mark-draw 0.4s ease-out forwards; }
        .user-avatar { transform-origin: center; transition: transform 0.2s; }
        .check-mark {
            stroke-dasharray: 20;
            stroke-dashoffset: 0;
            transform-origin: 18px 19px;
            transition: transform 0.25s;
        }

        /* Simple Checked Icon Animation */
        @keyframes check-path-redraw {
            0% { stroke-dashoffset: 0; }
            20% { stroke-dashoffset: 24; }
            100% { stroke-dashoffset: 0; }
        }
        .group\/btn-complete:hover .check-path { animation: check-path-redraw 0.5s ease-in-out; }
        .check-path {
            stroke-dasharray: 24;
            stroke-dashoffset: 0;
        }

        /* Whatsapp Icon Animation */
        @keyframes phone-ring {
            0% { transform: rotate(0deg); }
            16% { transform: rotate(-15deg); }
            33% { transform: rotate(15deg); }
            50% { transform: rotate(-10deg); }
            66% { transform: rotate(10deg); }
            100% { transform: rotate(0deg); }
        }
        .group\/btn-whatsapp:not(:disabled):hover .phone-icon { animation: phone-ring 0.4s ease-in-out; }
        .phone-icon { transform-origin: 50% 50%; }

        /* Send Icon Animation */
        @keyframes send-fly {
            0% { transform: translate(0, 0); opacity: 1; }
            49.9% { transform: translate(24px, -24px); opacity: 0; }
            50% { transform: translate(-24px, 24px); opacity: 0; }
            100% { transform: translate(0, 0); opacity: 1; }
        }
        .group\/btn-send:not(:disabled):hover .send-icon { animation: send-fly 0.5s ease-in-out forwards; }
        .send-icon { transform-origin: center; }

        /* Pen Icon Animation */
        @keyframes pen-wiggle {
            0% { transform: translate(0, 0) rotate(0deg); }
            10% { transform: translate(1px, -2px) rotate(-6deg); }
            20% { transform: translate(-1px, -4px) rotate(-4deg); }
            30% { transform: translate(1px, -6px) rotate(-6deg); }
            40% { transform: translate(-1px, -8px) rotate(-4deg); }
            51.6% { transform: translate(0, -10px) rotate(0deg); }
            83.9% { transform: translate(0, -10px) rotate(0deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }
        @keyframes pen-slash-draw {
            0%, 51.6% { stroke-dashoffset: 10; opacity: 0; }
            71% { stroke-dashoffset: 0; opacity: 1; }
            83.9%, 100% { stroke-dashoffset: 10; opacity: 0; }
        }
        .group\/btn-edit:hover .pen-group { animation: pen-wiggle 1.55s ease-in-out infinite; }
        .group\/btn-edit:hover .pen-slash { animation: pen-slash-draw 1.55s ease-out infinite; }
        .pen-group { transform-origin: 50% 50%; transform-box: fill-box; }
        .pen-slash { stroke-dasharray: 10; stroke-dashoffset: 10; opacity: 0; }
    </style>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Citas</h2>
                <p class="text-sm text-slate-500 mt-0.5">Agenda y gestión de consultas veterinarias</p>
            </div>
            <button type="button"
                    id="btn-agendar-cita"
                    @click="$dispatch('agendar-cita')"
                    class="ml-4 mt-2 inline-flex items-center gap-2 btn-citas-purple px-5 py-2.5 text-sm font-semibold shadow-sm active:scale-95">
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
                            class="px-5 py-2.5 btn-citas-purple text-sm font-medium w-32 shadow-sm active:scale-95">
                        Filtrar
                    </button>
                    @if(request()->hasAny(['buscar','estado','fecha_filtro','fecha_desde','fecha_hasta']))
                        <a href="{{ route('citas.index') }}"
                           class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center no-underline" style="height: fit-content;">
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

                            {{-- Acciones --}}
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1">

                                    {{-- Ver detalle --}}
                                    <button type="button"
                                            title="Ver detalle"
                                            @click="$dispatch('ver-cita', { id: {{ $cita->id }} })"
                                            class="group/btn-eye p-1.5 text-slate-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <!-- Pupil -->
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" class="eye-pupil" />
                                            <!-- Eye shape -->
                                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" class="eye-shape" />
                                        </svg>
                                    </button>

                                    {{-- Confirmar (solo si pendiente) --}}
                                    @if($cita->estado === 'pendiente')
                                        <form method="POST" action="{{ route('citas.confirmar', $cita) }}">
                                            @csrf
                                            <button type="submit"
                                                    title="Confirmar cita"
                                                    class="group/btn-check p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <!-- User avatar -->
                                                    <g class="user-avatar">
                                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                                    </g>
                                                    <!-- Checkmark -->
                                                    <path d="M15 19l2 2l4 -4" class="check-mark" />
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
                                                    class="group/btn-complete p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                     fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M5 12l5 5l10 -10" class="check-path" />
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
                                                title="{{ $cita->estado === 'completada' ? 'Cita completada' : ($cita->enviado_email ? 'Reenviar email' : 'No seleccionado') }}"
                                                {{ (!$cita->enviado_email || $cita->estado === 'completada') ? 'disabled' : '' }}
                                                class="group/btn-send p-1.5 rounded-lg transition-colors
                                                       {{ ($cita->enviado_email && $cita->estado !== 'completada')
                                                           ? 'text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50'
                                                           : 'text-slate-300 opacity-50 cursor-not-allowed' }}">
                                            <svg class="w-4 h-4 overflow-visible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <g class="send-icon">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 14l11 -11" />
                                                    <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />
                                                </g>
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- WhatsApp --}}
                                    <form method="POST" action="{{ route('citas.enviar-whatsapp', $cita) }}">
                                        @csrf
                                        <button type="submit"
                                                title="{{ $cita->estado === 'completada' ? 'Cita completada' : ($cita->enviado_whatsapp ? 'Reenviar WhatsApp' : 'No seleccionado') }}"
                                                {{ (!$cita->enviado_whatsapp || $cita->estado === 'completada') ? 'disabled' : '' }}
                                                class="group/btn-whatsapp p-1.5 rounded-lg transition-colors
                                                       {{ ($cita->enviado_whatsapp && $cita->estado !== 'completada')
                                                           ? 'text-emerald-500 hover:text-emerald-700 hover:bg-emerald-50'
                                                           : 'text-slate-300 opacity-50 cursor-not-allowed' }}">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" />
                                                <path class="phone-icon" d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" />
                                            </svg>
                                        </button>
                                    </form>

                                    {{-- Editar --}}
                                    @if(in_array($cita->estado, ['completada', 'confirmada', 'cancelada']))
                                        <span title="Acción no disponible ({{ $cita->estado }})"
                                              class="p-1.5 text-slate-300 opacity-50 cursor-not-allowed rounded-lg">
                                            <svg class="w-4 h-4" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10">
                                                <g class="pen-group">
                                                    <path class="pen-body" d="m10.5,27.5l-8,2 2-8L22.257,3.743c1.657-1.657,4.343-1.657,6,0s1.657,4.343,0,6L10.5,27.5Z" />
                                                </g>
                                            </svg>
                                        </span>
                                    @else
                                        <button type="button"
                                                title="Editar cita"
                                                @click="$dispatch('editar-cita', { id: {{ $cita->id }} })"
                                                class="group/btn-edit p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4 overflow-visible" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10">
                                                <g class="pen-group">
                                                    <path class="pen-slash" d="M20 6 L26 12" />
                                                    <path class="pen-body" d="m10.5,27.5l-8,2 2-8L22.257,3.743c1.657-1.657,4.343-1.657,6,0s1.657,4.343,0,6L10.5,27.5Z" />
                                                </g>
                                            </svg>
                                        </button>
                                    @endif

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
                                                class="inline-flex items-center gap-2 btn-citas-purple px-5 py-2.5 text-sm font-semibold shadow-sm active:scale-95">
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
                {{ $citas->links('vendor.pagination.uiverse-purple') }}
            </div>
        @endif
    </div>

</x-app-layout>
