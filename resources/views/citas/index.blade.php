<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Citas</h2>
                <p class="text-sm text-slate-500 mt-0.5">Agenda y gestión de consultas veterinarias</p>
            </div>
            <a href="{{ route('citas.create') }}"
               id="btn-nueva-cita"
               class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Cita
            </a>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('citas.index') }}" class="flex flex-col sm:flex-row gap-3 flex-wrap">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="buscar-cita"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por mascota o cliente..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent bg-slate-50 transition-all">
            </div>

            <!-- Filtro estado -->
            <select id="filtro-estado"
                    name="estado"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 bg-slate-50 text-slate-700 transition-all">
                <option value="">Todos los estados</option>
                <option value="pendiente"  {{ request('estado') === 'pendiente'  ? 'selected' : '' }}>⏳ Pendiente</option>
                <option value="confirmada" {{ request('estado') === 'confirmada' ? 'selected' : '' }}>✅ Confirmada</option>
                <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>🏁 Completada</option>
                <option value="cancelada"  {{ request('estado') === 'cancelada'  ? 'selected' : '' }}>❌ Cancelada</option>
            </select>

            <!-- Filtro fecha -->
            <input type="date"
                   id="filtro-fecha"
                   name="fecha"
                   value="{{ request('fecha') }}"
                   class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 bg-slate-50 text-slate-700 transition-all">

            <button type="submit"
                    class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl transition-colors">
                Filtrar
            </button>
            @if(request('buscar') || request('estado') || request('fecha'))
                <a href="{{ route('citas.index') }}"
                   class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
        @php
            $estadoStats = [
                ['label' => 'Pendientes',  'key' => 'pendiente',  'color' => 'text-amber-600 bg-amber-50 border-amber-200',   'emoji' => '⏳'],
                ['label' => 'Confirmadas', 'key' => 'confirmada', 'color' => 'text-emerald-600 bg-emerald-50 border-emerald-200', 'emoji' => '✅'],
                ['label' => 'Completadas', 'key' => 'completada', 'color' => 'text-sky-600 bg-sky-50 border-sky-200',         'emoji' => '🏁'],
                ['label' => 'Canceladas',  'key' => 'cancelada',  'color' => 'text-rose-600 bg-rose-50 border-rose-200',       'emoji' => '❌'],
            ];
        @endphp
        @foreach($estadoStats as $stat)
            <a href="{{ route('citas.index', ['estado' => $stat['key']]) }}"
               class="flex items-center gap-3 p-3 rounded-xl border {{ $stat['color'] }} transition-all hover:shadow-sm {{ request('estado') === $stat['key'] ? 'ring-2 ring-offset-1 ring-violet-400' : '' }}">
                <span class="text-xl">{{ $stat['emoji'] }}</span>
                <div>
                    <p class="text-xs font-medium opacity-70">{{ $stat['label'] }}</p>
                    <p class="text-lg font-bold">{{ $conteoEstados[$stat['key']] ?? 0 }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Citas Table -->
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
                        <th class="text-left px-6 py-3.5 font-semibold">Fecha y Hora</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Mascota</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden md:table-cell">Propietario</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden lg:table-cell">Servicio / Motivo</th>
                        <th class="text-center px-6 py-3.5 font-semibold">Estado</th>
                        <th class="px-6 py-3.5 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($citas as $cita)
                        @php
                            $estadoConfig = match($cita->estado ?? 'pendiente') {
                                'pendiente'  => ['class' => 'bg-amber-100 text-amber-700',   'label' => 'Pendiente',  'dot' => 'bg-amber-500'],
                                'confirmada' => ['class' => 'bg-emerald-100 text-emerald-700', 'label' => 'Confirmada', 'dot' => 'bg-emerald-500'],
                                'completada' => ['class' => 'bg-sky-100 text-sky-700',       'label' => 'Completada', 'dot' => 'bg-sky-500'],
                                'cancelada'  => ['class' => 'bg-rose-100 text-rose-700',     'label' => 'Cancelada',  'dot' => 'bg-rose-500'],
                                default      => ['class' => 'bg-slate-100 text-slate-600',   'label' => ucfirst($cita->estado ?? ''), 'dot' => 'bg-slate-400'],
                            };
                            $esHoy = $cita->fecha->isToday();
                            $esPasada = $cita->fecha->isPast() && !$esHoy;
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors {{ $esHoy ? 'bg-violet-50/40' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    @if($esHoy)
                                        <span class="w-2 h-2 rounded-full bg-violet-500 flex-shrink-0 animate-pulse"></span>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-slate-800 {{ $esPasada ? 'text-slate-400' : '' }}">
                                            {{ $cita->fecha->format('d/m/Y') }}
                                        </p>
                                        <p class="text-xs {{ $esHoy ? 'text-violet-600 font-semibold' : 'text-slate-400' }}">
                                            {{ $esHoy ? '🕐 HOY · ' : '' }}{{ $cita->hora }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($cita->mascota)
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg">
                                            {{ match($cita->mascota->especie) {
                                                'Perro' => '🐶', 'Gato' => '🐱', 'Ave' => '🦜',
                                                'Conejo' => '🐰', 'Reptil' => '🦎', default => '🐾'
                                            } }}
                                        </span>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $cita->mascota->nombre }}</p>
                                            <p class="text-xs text-slate-500">{{ $cita->mascota->especie }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                @if($cita->cliente)
                                    <p class="text-slate-700 font-medium">{{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $cita->cliente->telefono ?? '' }}</p>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <p class="text-slate-700 font-medium">{{ $cita->tipo_servicio ?? 'Consulta general' }}</p>
                                @if($cita->motivo)
                                    <p class="text-xs text-slate-500 mt-0.5 truncate max-w-[180px]" title="{{ $cita->motivo }}">
                                        {{ $cita->motivo }}
                                    </p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $estadoConfig['class'] }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $estadoConfig['dot'] }}"></span>
                                    {{ $estadoConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Editar -->
                                    <a href="{{ route('citas.edit', $cita) }}"
                                       title="Editar cita"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <!-- Eliminar -->
                                    <form method="POST" action="{{ route('citas.destroy', $cita) }}"
                                          onsubmit="return confirm('¿Cancelar y eliminar esta cita del {{ $cita->fecha->format('d/m/Y') }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Eliminar"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-6xl mb-4">📅</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request()->hasAny(['buscar','estado','fecha']) ? 'No se encontraron citas' : 'No hay citas registradas' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request()->hasAny(['buscar','estado','fecha']) ? 'Intenta con otros filtros.' : 'Agenda la primera cita de tu clínica.' }}
                                    </p>
                                    @if(!request()->hasAny(['buscar','estado','fecha']))
                                        <a href="{{ route('citas.create') }}"
                                           class="inline-flex items-center gap-2 bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Agendar Primera Cita
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($citas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $citas->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
