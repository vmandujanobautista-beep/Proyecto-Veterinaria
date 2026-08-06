<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Clientes</h2>
                <p class="text-sm text-slate-500 mt-0.5">Gestión de propietarios y sus mascotas</p>
            </div>
            <button type="button" @click="$dispatch('nuevo-cliente')" id="btn-nuevo-cliente"
                class="btn-emerald-pulse ml-4 mt-2 inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Cliente
            </button>
        </div>
    </x-slot>

    <!-- Search & Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('clientes.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" id="buscar-cliente" name="buscar" value="{{ request('buscar') }}"
                    placeholder="Buscar por nombre, email o teléfono..."
                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-slate-50 transition-all">
            </div>
            <button type="submit"
                class="btn-emerald-pulse px-5 py-2.5 text-sm font-medium w-32 shadow-sm active:scale-95">
                Buscar
            </button>
            @if(request('buscar'))
                <a href="{{ route('clientes.index') }}"
                    class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        @if($clientes->isNotEmpty())
            <!-- Table Header with count -->
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando <span class="text-slate-800 font-bold">{{ $clientes->count() }}</span> de
                    <span class="text-slate-800 font-bold">{{ $clientes->total() }}</span> clientes
                </p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                        <th class="text-left px-6 py-3.5 font-semibold">Cliente</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden md:table-cell">Teléfono</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden lg:table-cell">Dirección</th>
                        <th class="text-center px-6 py-3.5 font-semibold hidden md:table-cell">Mascotas</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden xl:table-cell">Registro</th>
                        <th class="px-6 py-3.5 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($clientes as $cliente)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                        style="background: linear-gradient(135deg, #10b981, #059669)">
                                        {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800"
                                            title="{{ $cliente->nombre }} {{ $cliente->apellido }}">
                                            {{ Str::limit($cliente->nombre . ' ' . $cliente->apellido, 25) }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5" title="{{ $cliente->email }}">
                                            {{ Str::limit($cliente->email, 25) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 hidden md:table-cell">
                                {{ $cliente->telefono ?? '—' }}
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <span class="text-slate-600 truncate max-w-xs block" title="{{ $cliente->direccion }}">
                                    {{ Str::limit($cliente->direccion ?? '—', 30) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center hidden md:table-cell">
                                <a href="{{ route('mascotas.index', ['cliente' => $cliente->id]) }}"
                                    class="inline-flex items-center gap-1 bg-sky-50 hover:bg-sky-100 text-sky-700 text-xs font-semibold px-3 py-1 rounded-full transition-colors">
                                    🐾 {{ $cliente->mascotas_count ?? $cliente->mascotas->count() }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs hidden xl:table-cell">
                                {{ $cliente->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Ver -->
                                    <button type="button" @click="$dispatch('ver-cliente', { id: {{ $cliente->id }} })"
                                        title="Ver detalle"
                                        class="group/btn p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <!-- Pupil -->
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"
                                                class="transition-transform duration-150 ease-out origin-center group-hover/btn:scale-75" />
                                            <!-- Eye shape -->
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"
                                                class="transition-transform duration-150 ease-out origin-center group-hover/btn:scale-y-90" />
                                        </svg>
                                    </button>
                                    <!-- Editar -->
                                    <button type="button" @click="$dispatch('editar-cliente', { id: {{ $cliente->id }} })"
                                        title="Editar"
                                        class="group/edit p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors relative overflow-visible">
                                        <svg class="w-4 h-4 overflow-visible" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="square" stroke-miterlimit="10">
                                            <g class="pen-group" style="transform-origin: 50% 50%;">
                                                <path
                                                    class="pen-slash opacity-0 transition-opacity duration-300 group-hover/edit:opacity-100"
                                                    d="M20 6 L26 12" />
                                                <path class="pen-body"
                                                    d="m10.5,27.5l-8,2 2-8L22.257,3.743c1.657-1.657,4.343-1.657,6,0s1.657,4.343,0,6L10.5,27.5Z" />
                                            </g>
                                        </svg>
                                    </button>
                                    <!-- Eliminar -->
                                    <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                                        onsubmit="return confirm('¿Estás seguro de eliminar a {{ $cliente->nombre }} {{ $cliente->apellido }}? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Eliminar"
                                            class="group/del p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                                <!-- Lower lid -->
                                                <path d="M4 7l16 0"
                                                    class="transition-transform duration-200 ease-out origin-bottom group-hover/del:-rotate-[25deg] group-hover/del:-translate-y-1" />

                                                <!-- Bin body -->
                                                <path d="M10 11l0 6" />
                                                <path d="M14 11l0 6" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />

                                                <!-- Upper lid (handle) -->
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"
                                                    class="transition-transform duration-200 ease-out origin-bottom group-hover/del:-rotate-[35deg] group-hover/del:-translate-y-1.5 group-hover/del:-translate-x-0.5" />
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
                                    <span class="text-6xl mb-4">🐾</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request('buscar') ? 'No se encontraron resultados' : 'No hay clientes registrados' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request('buscar') ? 'Intenta con otra búsqueda.' : 'Comienza registrando tu primer cliente.' }}
                                    </p>
                                    @if(!request('buscar'))
                                        <button type="button" @click="$dispatch('nuevo-cliente')"
                                            class="btn-emerald-pulse inline-flex items-center gap-2 text-sm font-semibold px-5 py-2.5 shadow-sm active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Registrar Primer Cliente
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <!-- Contenedor principal (asumiendo que el div huérfano cerraba esto) -->
        <div class="w-full">

            @if($clientes->hasPages())
                <div class="px-6 py-8 border-t border-slate-100 flex justify-center">
                    {{ $clientes->links('vendor.pagination.uiverse-emerald') }}
                </div>
            @endif

        </div>

        <!-- Estilos corregidos y agrupados al final -->
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Escuchar cuando se crea un nuevo cliente
                Echo.channel('clientes')
                    .listen('ClienteCreado', (e) => {
                        mostrarNotificacion('Nuevo cliente registrado: ' + e.nombre + ' ' + (e.apellido || ''), 'success');
                        setTimeout(() => location.reload(), 1500);
                    })
                    .listen('ClienteEditado', (e) => {
                        mostrarNotificacion('Cliente actualizado: ' + e.nombre + ' ' + (e.apellido || ''), 'info');
                        setTimeout(() => location.reload(), 1500);
                    })
                    .listen('ClienteEliminado', (e) => {
                        mostrarNotificacion('Cliente eliminado', 'warning');
                        setTimeout(() => location.reload(), 1500);
                    });

                // Escuchar mascotas
                Echo.channel('mascotas')
                    .listen('MascotaCreada', (e) => {
                        mostrarNotificacion('Nueva mascota registrada: ' + e.nombre, 'success');
                        setTimeout(() => location.reload(), 1500);
                    })
                    .listen('MascotaEliminada', (e) => {
                        mostrarNotificacion('Mascota eliminada', 'warning');
                        setTimeout(() => location.reload(), 1500);
                    });

                // Escuchar citas
                Echo.channel('citas')
                    .listen('CitaCreada', (e) => {
                        mostrarNotificacion('Nueva cita agendada', 'success');
                        setTimeout(() => location.reload(), 1500);
                    })
                    .listen('CitaEditada', (e) => {
                        mostrarNotificacion('Cita actualizada', 'info');
                        setTimeout(() => location.reload(), 1500);
                    });
            });

            function mostrarNotificacion(mensaje, tipo) {
                // Crear una notificación flotante
                const notif = document.createElement('div');
                notif.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg text-white text-sm font-semibold transition-opacity duration-500 ease-in-out`;
                notif.style.backgroundColor = tipo === 'success' ? '#10b981' : tipo === 'warning' ? '#f43f5e' : '#3b82f6';
                notif.textContent = mensaje;
                document.body.appendChild(notif);

                setTimeout(() => {
                    notif.style.opacity = '0';
                    setTimeout(() => notif.remove(), 500);
                }, 3000);
            }
        </script>
</x-app-layout>