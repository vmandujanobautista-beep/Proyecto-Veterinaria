<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Ventas</h2>
                <p class="text-sm text-slate-500 mt-0.5">Historial de transacciones de la clínica</p>
            </div>
            <a href="{{ route('ventas.create') }}"
               id="btn-nueva-venta"
               class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Venta
            </a>
        </div>
    </x-slot>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">Total Hoy</p>
            <p class="text-2xl font-bold text-slate-800">${{ number_format($totalHoy ?? 0, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $ventasHoy ?? 0 }} transacciones</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">Este Mes</p>
            <p class="text-2xl font-bold text-slate-800">${{ number_format($totalMes ?? 0, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $ventasMes ?? 0 }} transacciones</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wide mb-1">Total General</p>
            <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalGeneral ?? 0, 2) }}</p>
            <p class="text-xs text-slate-400 mt-1">{{ $totalVentas ?? 0 }} ventas registradas</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('ventas.index') }}" class="flex flex-col sm:flex-row gap-3 flex-wrap">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="buscar-venta"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por cliente o mascota..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent bg-slate-50 transition-all">
            </div>

            <select id="filtro-metodo"
                    name="metodo_pago"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 bg-slate-50 text-slate-700 transition-all">
                <option value="">Método de pago</option>
                <option value="Efectivo"       {{ request('metodo_pago') === 'Efectivo'       ? 'selected' : '' }}>💵 Efectivo</option>
                <option value="Tarjeta"        {{ request('metodo_pago') === 'Tarjeta'        ? 'selected' : '' }}>💳 Tarjeta</option>
                <option value="Transferencia"  {{ request('metodo_pago') === 'Transferencia'  ? 'selected' : '' }}>🏦 Transferencia</option>
            </select>

            <input type="date"
                   id="filtro-fecha-venta"
                   name="fecha"
                   value="{{ request('fecha') }}"
                   class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 bg-slate-50 text-slate-700 transition-all">

            <button type="submit"
                    class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl transition-colors">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','metodo_pago','fecha']))
                <a href="{{ route('ventas.index') }}"
                   class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Sales Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        @if($ventas->isNotEmpty())
            <div class="px-6 py-3 border-b border-slate-100 bg-slate-50">
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando <span class="text-slate-800 font-bold">{{ $ventas->count() }}</span> de
                    <span class="text-slate-800 font-bold">{{ $ventas->total() }}</span> ventas
                </p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                        <th class="text-left px-6 py-3.5 font-semibold">#</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Cliente / Mascota</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden lg:table-cell">Productos</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden md:table-cell">Método</th>
                        <th class="text-right px-6 py-3.5 font-semibold">Total</th>
                        <th class="text-center px-6 py-3.5 font-semibold hidden md:table-cell">Estado</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden xl:table-cell">Fecha</th>
                        <th class="px-6 py-3.5 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($ventas as $venta)
                        @php
                            $metodoPagoEmoji = match($venta->metodo_pago) {
                                'Efectivo' => '💵', 'Tarjeta' => '💳', 'Transferencia' => '🏦', default => '💰',
                            };
                            $estadoConfig = match($venta->estado ?? 'completada') {
                                'completada' => ['class' => 'bg-emerald-100 text-emerald-700', 'label' => 'Completada'],
                                'pendiente'  => ['class' => 'bg-amber-100 text-amber-700',    'label' => 'Pendiente'],
                                'cancelada'  => ['class' => 'bg-rose-100 text-rose-700',      'label' => 'Cancelada'],
                                default      => ['class' => 'bg-slate-100 text-slate-600',    'label' => ucfirst($venta->estado ?? '')],
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-mono text-slate-400">#{{ $venta->id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-400 to-pink-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($venta->cliente->nombre ?? 'V', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">
                                            {{ $venta->cliente->nombre ?? '—' }} {{ $venta->cliente->apellido ?? '' }}
                                        </p>
                                        @if($venta->mascota)
                                            <p class="text-xs text-slate-500 mt-0.5">🐾 {{ $venta->mascota->nombre }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($venta->ventaProductos->take(2) as $vp)
                                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">
                                            {{ $vp->producto->nombre ?? '—' }} ×{{ $vp->cantidad }}
                                        </span>
                                    @endforeach
                                    @if($venta->ventaProductos->count() > 2)
                                        <span class="text-xs text-slate-400">+{{ $venta->ventaProductos->count() - 2 }} más</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <span class="text-sm">{{ $metodoPagoEmoji }}</span>
                                <span class="text-xs text-slate-600 ml-1">{{ $venta->metodo_pago ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-slate-800">${{ number_format($venta->total, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center hidden md:table-cell">
                                <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full {{ $estadoConfig['class'] }}">
                                    {{ $estadoConfig['label'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 hidden xl:table-cell">
                                {{ $venta->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('ventas.show', $venta) }}"
                                       title="Ver detalle"
                                       class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('ventas.destroy', $venta) }}"
                                          onsubmit="return confirm('¿Eliminar la venta #{{ $venta->id }}?')">
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
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-6xl mb-4">🧾</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request()->hasAny(['buscar','metodo_pago','fecha']) ? 'No se encontraron ventas' : 'No hay ventas registradas' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request()->hasAny(['buscar','metodo_pago','fecha']) ? 'Intenta con otros filtros.' : 'Registra la primera venta de tu clínica.' }}
                                    </p>
                                    @if(!request()->hasAny(['buscar','metodo_pago','fecha']))
                                        <a href="{{ route('ventas.create') }}"
                                           class="inline-flex items-center gap-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Registrar Primera Venta
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ventas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $ventas->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
