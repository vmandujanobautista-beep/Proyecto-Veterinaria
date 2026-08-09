<x-app-layout>
    <style>
        .btn-fuego-animated {
            border: none; color: #fff;
            background-image: linear-gradient(30deg, #dc2626, #f97316);
            border-radius: 0.75rem; background-size: 100% auto;
            font-family: inherit; transition: all 0.3s ease;
        }
        .btn-fuego-animated:hover {
            background-position: right center; background-size: 200% auto;
            animation: pulseFuego 1.5s infinite;
        }
        @keyframes pulseFuego {
            0%   { box-shadow: 0 0 0 0 rgba(220,38,38,.6); }
            70%  { box-shadow: 0 0 0 10px rgba(220,38,38,0); }
            100% { box-shadow: 0 0 0 0 rgba(220,38,38,0); }
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Ventas</h2>
                <p class="text-sm text-slate-500 mt-0.5">Historial de transacciones de la clínica</p>
            </div>
            <a href="{{ route('ventas.create') }}"
               id="btn-nueva-venta"
               class="btn-fuego-animated ml-4 mt-2 inline-flex items-center gap-2 text-white text-sm font-semibold px-4 py-2.5 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Venta
            </a>
        </div>
    </x-slot>

    {{-- ═══ MODALS ═══ --}}

    {{-- Modal VER DETALLE --}}
    <div x-data="verVentaModal()"
         x-cloak
         x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[80] flex items-center justify-center p-4"
         @keydown.escape.window="cerrar()"
         @ver-venta.window="abrir($event.detail.id)">

        <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="cerrar()"></div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative w-full bg-white rounded-2xl shadow-2xl overflow-y-auto"
             style="max-width:640px; max-height:92vh;"
             @click.stop>

            <div class="h-1.5 w-full rounded-t-2xl bg-gradient-to-r from-emerald-500 to-teal-500"></div>

            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                        <span class="text-lg">🧾</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800" x-text="venta ? 'Venta ' + venta.folio : 'Cargando...'"></h3>
                        <p class="text-xs text-slate-400" x-text="venta ? venta.created_at_formatted : ''"></p>
                    </div>
                </div>
                <button @click="cerrar()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Loader --}}
            <div x-show="cargando" class="p-12 flex flex-col items-center gap-3">
                <svg class="w-8 h-8 text-emerald-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                </svg>
            </div>

            {{-- Content --}}
            <div x-show="!cargando && venta" class="p-6 space-y-5">

                {{-- Cliente + Mascota + Cajero --}}
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Cliente</p>
                        <p class="text-sm font-bold text-slate-800" x-text="venta?.cliente_nombre || '—'"></p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Mascota</p>
                        <p class="text-sm font-bold text-slate-800" x-text="venta?.mascota_nombre || '—'"></p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Cajero</p>
                        <p class="text-sm font-bold text-slate-800" x-text="venta?.cajero || '—'"></p>
                    </div>
                </div>

                {{-- Método de pago + Estado --}}
                <div class="flex gap-3">
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 flex-1">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Método de Pago</p>
                        <p class="text-sm font-bold text-slate-800" x-text="venta?.metodo_pago || '—'"></p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 border border-slate-100 flex-1">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-1">Estado</p>
                        <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full"
                              :class="venta?.estado === 'pagada' ? 'bg-emerald-100 text-emerald-700' :
                                      venta?.estado === 'cancelada'  ? 'bg-rose-100 text-rose-700' :
                                      'bg-amber-100 text-amber-700'"
                              x-text="venta?.estado ? venta.estado.charAt(0).toUpperCase() + venta.estado.slice(1) : '—'"></span>
                    </div>
                </div>

                {{-- Productos --}}
                <div>
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-2">Productos</p>
                    <div class="border border-slate-100 rounded-xl overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                                <tr>
                                    <th class="text-left px-4 py-2.5 font-semibold">Producto</th>
                                    <th class="text-center px-3 py-2.5 font-semibold">Cant.</th>
                                    <th class="text-right px-3 py-2.5 font-semibold">Precio</th>
                                    <th class="text-right px-4 py-2.5 font-semibold">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <template x-for="p in venta?.productos" :key="p.nombre">
                                    <tr>
                                        <td class="px-4 py-2.5 font-medium text-slate-800" x-text="p.nombre"></td>
                                        <td class="px-3 py-2.5 text-center text-slate-600" x-text="p.cantidad"></td>
                                        <td class="px-3 py-2.5 text-right text-slate-600" x-text="'$' + fmt(p.precio_unitario)"></td>
                                        <td class="px-4 py-2.5 text-right font-semibold text-slate-800" x-text="'$' + fmt(p.subtotal)"></td>
                                    </tr>
                                </template>
                            </tbody>
                            <tfoot class="bg-emerald-50 border-t border-emerald-100">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm font-bold text-emerald-800">TOTAL</td>
                                    <td class="px-4 py-3 text-right text-lg font-black text-emerald-700"
                                        x-text="'$' + fmt(venta?.total)"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal CANCELAR VENTA --}}
    <div x-data="cancelarVentaModal()"
         x-cloak
         x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[90] flex items-center justify-center p-4"
         @keydown.escape.window="cerrar()"
         @cancelar-venta.window="abrir($event.detail.id, $event.detail.folio)">

        <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="cerrar()"></div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl p-6"
             @click.stop>

            <div class="text-center mb-5">
                <div class="w-16 h-16 bg-rose-100 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-3">⚠️</div>
                <h3 class="text-lg font-bold text-slate-800">¿Cancelar esta venta?</h3>
                <p class="text-sm text-slate-500 mt-1">Venta <strong x-text="folio"></strong></p>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 mb-5 text-sm text-amber-700">
                📦 El stock de todos los productos incluidos en esta venta será <strong>restaurado automáticamente</strong>.
            </div>

            <div class="flex gap-3">
                <button @click="cerrar()"
                        class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    No cancelar
                </button>
                <form :action="`/ventas/${ventaId}/cancelar`" method="POST" class="flex-1">
                    @csrf
                    <button type="submit"
                            class="w-full px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl transition-colors">
                        ✅ Confirmar cancelación
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══ SUMMARY CARDS ═══ --}}
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

    {{-- ═══ FILTERS ═══ --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('ventas.index') }}" class="flex flex-col sm:flex-row gap-3 flex-wrap">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="buscar-venta" name="buscar" value="{{ request('buscar') }}"
                       placeholder="Buscar por cliente o mascota..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent bg-slate-50 transition-all">
            </div>

            <select id="filtro-metodo" name="metodo_pago"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 bg-slate-50 text-slate-700 w-full sm:w-52 transition-all">
                <option value="">Método de pago</option>
                <option value="Efectivo"      {{ request('metodo_pago') === 'Efectivo'      ? 'selected' : '' }}>💵 Efectivo</option>
                <option value="Tarjeta"       {{ request('metodo_pago') === 'Tarjeta'       ? 'selected' : '' }}>💳 Tarjeta</option>
                <option value="Transferencia" {{ request('metodo_pago') === 'Transferencia' ? 'selected' : '' }}>🏦 Transferencia</option>
            </select>

            <input type="date" id="filtro-fecha-venta" name="fecha" value="{{ request('fecha') }}"
                   class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 bg-slate-50 text-slate-700 transition-all">

            <button type="submit" class="btn-fuego-animated text-white text-sm font-medium w-32 shadow-sm px-5 py-2.5">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar','metodo_pago','fecha']))
                <a href="{{ route('ventas.index') }}"
                   class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors w-32 text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- ═══ TABLE ═══ --}}
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
                        <th class="text-left px-6 py-3.5 font-semibold">Folio</th>
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
                            $folio = 'VNT-' . str_pad($venta->id, 3, '0', STR_PAD_LEFT);
                            $metodoPagoEmoji = match(strtolower($venta->metodo_pago ?? '')) {
                                'efectivo' => '💵', 'tarjeta' => '💳', 'transferencia' => '🏦', default => '💰',
                            };
                            $estadoConfig = match($venta->estado ?? 'pagada') {
                                'pagada' => ['class' => 'bg-emerald-100 text-emerald-700', 'label' => 'Pagada'],
                                'pendiente'  => ['class' => 'bg-amber-100 text-amber-700',    'label' => 'Pendiente'],
                                'cancelada'  => ['class' => 'bg-rose-100 text-rose-700',      'label' => 'Cancelada'],
                                default      => ['class' => 'bg-slate-100 text-slate-600',    'label' => ucfirst($venta->estado ?? '')],
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="text-xs font-mono font-bold text-slate-600">{{ $folio }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-400 to-pink-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($venta->cliente->nombre ?? 'V', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">
                                            {{ $venta->cliente->nombre ?? '—' }}
                                            {{ $venta->cliente->apellido_paterno ?? $venta->cliente->apellido ?? '' }}
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
                                <div class="flex items-center gap-1.5">
                                    <span class="text-base leading-none">{{ $metodoPagoEmoji }}</span>
                                    <span class="text-sm text-slate-600 font-medium">{{ ucfirst($venta->metodo_pago ?? '—') }}</span>
                                </div>
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
                                    {{-- Ver Detalle --}}
                                    <button type="button"
                                            title="Ver detalle"
                                            @click="$dispatch('ver-venta', { id: {{ $venta->id }} })"
                                            class="group/btn-eye p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <!-- Pupil -->
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"
                                                class="transition-transform duration-150 ease-out origin-center group-hover/btn-eye:scale-75" />
                                            <!-- Eye shape -->
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"
                                                class="transition-transform duration-150 ease-out origin-center group-hover/btn-eye:scale-y-90" />
                                        </svg>
                                    </button>


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
                                           class="btn-fuego-animated inline-flex items-center gap-2 text-white text-sm font-semibold px-5 py-2.5">
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
            <div class="px-6 py-4 border-t border-slate-100 min-h-[72px]">
                {{ $ventas->links('vendor.pagination.uiverse-fuego') }}
            </div>
        @endif
    </div>

    <script>
    function verVentaModal() {
        return {
            open: false, cargando: false, venta: null,

            async abrir(id) {
                this.open = true; this.cargando = true; this.venta = null;
                try {
                    const r = await fetch(`/ventas/${id}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const data = await r.json();
                    const clienteNombre = data.cliente
                        ? (data.cliente.nombre + ' ' + (data.cliente.apellido_paterno ?? data.cliente.apellido ?? '')).trim()
                        : '—';
                    const fecha = new Date(data.venta.created_at);
                    this.venta = {
                        ...data.venta,
                        folio:             data.folio,
                        cliente_nombre:    clienteNombre,
                        mascota_nombre:    data.mascota?.nombre ?? '—',
                        cajero:            data.user?.name ?? '—',
                        productos:         data.productos,
                        created_at_formatted: fecha.toLocaleDateString('es-MX', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' }),
                    };
                } catch(e) { this.cerrar(); }
                finally { this.cargando = false; }
            },

            cerrar() { this.open = false; },

            fmt(n) {
                return Number(n).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },
        };
    }

    function cancelarVentaModal() {
        return {
            open: false, ventaId: null, folio: '',
            abrir(id, folio) { this.open = true; this.ventaId = id; this.folio = folio; },
            cerrar() { this.open = false; },
        };
    }
    </script>

</x-app-layout>
