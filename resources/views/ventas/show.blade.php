<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('ventas.index') }}"
               class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Detalle de Venta</h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Venta <span class="font-semibold text-slate-700">#{{ $venta->id }}</span>
                    · {{ $venta->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </x-slot>

    @php
        $estadoConfig = match($venta->estado ?? 'pagada') {
            'pagada' => ['class' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'label' => 'Pagada', 'emoji' => '✅'],
            'pendiente'  => ['class' => 'bg-amber-100 text-amber-700 border-amber-200',       'label' => 'Pendiente',  'emoji' => '⏳'],
            'cancelada'  => ['class' => 'bg-rose-100 text-rose-700 border-rose-200',          'label' => 'Cancelada',  'emoji' => '❌'],
            default      => ['class' => 'bg-slate-100 text-slate-600 border-slate-200',       'label' => ucfirst($venta->estado ?? ''), 'emoji' => '🧾'],
        };
        $metodoPagoEmoji = match(strtolower($venta->metodo_pago ?? '')) {
            'efectivo' => '💵', 'tarjeta' => '💳', 'transferencia' => '🏦', default => '💰',
        };
    @endphp

    <div class="max-w-3xl mx-auto space-y-5">

        <!-- Receipt Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <!-- Receipt Header -->
            <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-6 py-6">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-3xl">
                            🧾
                        </div>
                        <div>
                            <p class="text-slate-400 text-xs uppercase tracking-wider font-semibold">Comprobante de Venta</p>
                            <h3 class="text-white font-bold text-2xl">#{{ str_pad($venta->id, 5, '0', STR_PAD_LEFT) }}</h3>
                            <p class="text-slate-400 text-xs mt-0.5">{{ $venta->created_at->isoFormat('dddd, D [de] MMMM [de] YYYY [·] H:mm') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-slate-400 text-xs uppercase tracking-wider">Total</p>
                        <p class="text-white font-bold text-3xl">${{ number_format($venta->total, 2) }}</p>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full border mt-1 {{ $estadoConfig['class'] }}">
                            {{ $estadoConfig['emoji'] }} {{ $estadoConfig['label'] }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Info Row -->
            <div class="grid grid-cols-1 sm:grid-cols-3 divide-y sm:divide-y-0 sm:divide-x divide-slate-100 border-b border-slate-100">
                <!-- Cliente -->
                <div class="px-6 py-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-2">Cliente</p>
                    @if($venta->cliente)
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                {{ strtoupper(substr($venta->cliente->nombre, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $venta->cliente->nombre }} {{ $venta->cliente->apellido }}
                                </p>
                                <p class="text-xs text-slate-500">{{ $venta->cliente->telefono ?? $venta->cliente->email }}</p>
                            </div>
                        </div>
                    @else
                        <span class="text-sm text-slate-400">—</span>
                    @endif
                </div>

                <!-- Mascota -->
                <div class="px-6 py-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-2">Mascota</p>
                    @if($venta->mascota)
                        <div class="flex items-center gap-2">
                            <span class="text-xl">
                                {{ match($venta->mascota->especie) {
                                    'Perro' => '🐶', 'Gato' => '🐱', 'Ave' => '🦜',
                                    'Conejo' => '🐰', 'Reptil' => '🦎', default => '🐾'
                                } }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $venta->mascota->nombre }}</p>
                                <p class="text-xs text-slate-500">{{ $venta->mascota->especie }}{{ $venta->mascota->raza ? ' · '.$venta->mascota->raza : '' }}</p>
                            </div>
                        </div>
                    @else
                        <span class="text-sm text-slate-400">Sin mascota asociada</span>
                    @endif
                </div>

                <!-- Pago -->
                <div class="px-6 py-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-2">Método de Pago</p>
                    <div class="flex items-center gap-2">
                        <span class="text-xl">{{ $metodoPagoEmoji }}</span>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">{{ ucfirst($venta->metodo_pago ?? 'No especificado') }}</p>
                            @if($venta->user)
                                <p class="text-xs text-slate-500">Por: {{ $venta->user->name }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Table -->
            <div class="px-6 pt-4 pb-2">
                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold mb-3">Detalle de Productos</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider bg-slate-50">
                            <th class="text-left px-6 py-3 font-semibold">Producto</th>
                            <th class="text-center px-6 py-3 font-semibold">Cantidad</th>
                            <th class="text-right px-6 py-3 font-semibold">Precio Unit.</th>
                            <th class="text-right px-6 py-3 font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($venta->ventaProductos as $vp)
                            @php
                                $catEmoji = '📦';
                                if ($vp->producto) {
                                    $catEmoji = match($vp->producto->categoria) {
                                        'Medicamento' => '💊', 'Alimento' => '🥗', 'Accesorio' => '🎾',
                                        'Higiene' => '🧴', 'Vacuna' => '💉', 'Suplemento' => '🌿', default => '📦',
                                    };
                                }
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">{{ $catEmoji }}</span>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $vp->producto->nombre ?? 'Producto eliminado' }}</p>
                                            @if($vp->producto?->codigo)
                                                <code class="text-xs text-slate-400 font-mono">{{ $vp->producto->codigo }}</code>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-sm">
                                        {{ $vp->cantidad }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-right text-slate-600">
                                    ${{ number_format($vp->precio_unitario, 2) }}
                                </td>
                                <td class="px-6 py-3 text-right font-bold text-slate-800">
                                    ${{ number_format($vp->subtotal, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-200 bg-slate-50">
                            <td colspan="3" class="px-6 py-4 text-right text-sm font-semibold text-slate-700 uppercase tracking-wide">
                                Total
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-xl font-bold text-slate-900">${{ number_format($venta->total, 2) }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Actions Footer -->
            <div class="px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row gap-3 justify-between items-center">
                <form method="POST" action="{{ route('ventas.destroy', $venta) }}"
                      onsubmit="return confirm('⚠️ ¿Eliminar la venta #{{ $venta->id }}? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            id="btn-eliminar-venta"
                            class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors border border-rose-200 hover:border-rose-300">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar Venta
                    </button>
                </form>

                <div class="flex gap-3">
                    <a href="{{ route('ventas.index') }}"
                       class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                        Volver al listado
                    </a>
                    <button onclick="window.print()"
                            class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Imprimir Ticket
                    </button>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
