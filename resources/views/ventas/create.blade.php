<x-app-layout>
    <style>
        /* ── Botón fuego ── */
        .btn-fuego-animated {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #dc2626, #f97316);
            border-radius: 0.75rem;
            background-size: 100% auto;
            font-family: inherit;
            transition: all 0.3s ease;
        }
        .btn-fuego-animated:hover:not(:disabled) {
            background-position: right center;
            background-size: 200% auto;
            animation: pulseFuego 1.5s infinite;
        }
        @keyframes pulseFuego {
            0%   { box-shadow: 0 0 0 0 rgba(220,38,38,.6); }
            70%  { box-shadow: 0 0 0 10px rgba(220,38,38,0); }
            100% { box-shadow: 0 0 0 0 rgba(220,38,38,0); }
        }

        /* ── Botón cobrar ── */
        .btn-cobrar {
            background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
            border-radius: 0.75rem;
            border: none;
            color: #fff;
            font-family: inherit;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16,185,129,.35);
        }
        .btn-cobrar:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(16,185,129,.5);
        }
        .btn-cobrar:disabled { opacity: .6; cursor: not-allowed; }

        /* ── Toast ── */
        #pos-toast { transition: opacity .4s ease, transform .4s ease; }

        /* ── Scroll carrito ── */
        .ticket-scroll::-webkit-scrollbar { width: 4px; }
        .ticket-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .ticket-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>

    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('ventas.index') }}"
               class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Punto de Venta</h2>
                <p class="text-sm text-slate-500 mt-0.5">Selecciona productos y procesa la venta</p>
            </div>
        </div>
    </x-slot>

    {{-- ═══════════════════════════════════════════
         TOAST DE RESULTADO
    ════════════════════════════════════════════ --}}
    <div id="pos-toast"
         class="fixed top-6 right-6 z-[200] hidden px-5 py-4 rounded-2xl shadow-2xl text-white text-sm font-semibold max-w-xs"
         style="opacity:0; transform:translateY(-12px);">
    </div>

    {{-- ═══════════════════════════════════════════
         LAYOUT PRINCIPAL — 2 columnas
    ════════════════════════════════════════════ --}}
    <div x-data="posApp()" class="flex gap-5 items-start">

        {{-- ══════════ COLUMNA IZQUIERDA: Catálogo (60%) ══════════ --}}
        <div class="flex-1 min-w-0">

            {{-- Buscador + Filtro categoría --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-4">
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                               id="pos-buscar"
                               readonly
                               onfocus="this.removeAttribute('readonly')"
                               autocomplete="off"
                               placeholder="Buscar producto por nombre o código..."
                               x-model="buscar"
                               @input.debounce.400ms="buscarProductos()"
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 transition-all">
                    </div>
                    <select id="pos-categoria"
                            x-model="categoria"
                            @change="buscarProductos()"
                            class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none
                                   focus:ring-2 focus:ring-emerald-500 bg-slate-50 text-slate-700 appearance-none
                                   sm:w-48 transition-all">
                        <option value="">Todas las categorías</option>
                        <option value="Medicamento">💊 Medicamento</option>
                        <option value="Alimento">🥗 Alimento</option>
                        <option value="Accesorio">🎾 Accesorio</option>
                        <option value="Higiene">🧴 Higiene</option>
                        <option value="Vacuna">💉 Vacuna</option>
                        <option value="Suplemento">🌿 Suplemento</option>
                        <option value="Otro">📦 Otro</option>
                    </select>
                </div>
            </div>

            {{-- Grid de productos --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

                {{-- Loader --}}
                <div x-show="cargandoProductos" class="p-12 flex flex-col items-center gap-3">
                    <svg class="w-8 h-8 text-emerald-500 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                    <p class="text-sm text-slate-400">Cargando productos...</p>
                </div>

                {{-- Sin resultados --}}
                <div x-show="!cargandoProductos && productos.data && productos.data.length === 0"
                     class="p-12 text-center">
                    <span class="text-5xl">📦</span>
                    <p class="mt-3 text-slate-500 text-sm">No se encontraron productos</p>
                </div>

                {{-- Tabla --}}
                <div x-show="!cargandoProductos && productos.data && productos.data.length > 0" class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider bg-slate-50">
                                <th class="text-left px-5 py-3 font-semibold">Producto</th>
                                <th class="text-center px-4 py-3 font-semibold hidden md:table-cell">Categoría</th>
                                <th class="text-right px-4 py-3 font-semibold">Precio</th>
                                <th class="text-center px-4 py-3 font-semibold">Stock</th>
                                <th class="text-center px-4 py-3 font-semibold">Agregar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="producto in productos.data" :key="producto.id">
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center text-lg flex-shrink-0"
                                                 x-text="categoriaEmoji(producto.categoria)"></div>
                                            <div>
                                                <p class="font-semibold text-slate-800 leading-tight" x-text="producto.nombre"></p>
                                                <p x-show="producto.codigo" class="text-xs text-slate-400 font-mono" x-text="producto.codigo"></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center hidden md:table-cell">
                                        <span class="text-xs bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full font-medium"
                                              x-text="producto.categoria || '—'"></span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right">
                                        <span class="font-bold text-slate-800" x-text="'$' + formatMoney(producto.precio)"></span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="text-xs font-semibold px-2 py-1 rounded-full"
                                              :class="producto.stock === 0 ? 'bg-rose-100 text-rose-600' :
                                                       producto.stock < 10 ? 'bg-amber-100 text-amber-700' :
                                                       'bg-emerald-100 text-emerald-700'"
                                              x-text="producto.stock === 0 ? 'Agotado' : producto.stock + ' uds'"></span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center">
                                        <button type="button"
                                                :disabled="producto.stock === 0"
                                                :title="producto.stock === 0 ? 'Agotado' : 'Agregar al carrito'"
                                                @click="agregarAlCarrito(producto)"
                                                :class="producto.stock === 0
                                                    ? 'bg-slate-100 text-slate-300 cursor-not-allowed'
                                                    : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 active:scale-90'"
                                                class="w-8 h-8 rounded-xl flex items-center justify-center transition-all mx-auto font-bold text-lg">
                                            +
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div x-show="productos.last_page && productos.last_page > 1"
                     class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-slate-50">
                    <p class="text-xs text-slate-500">
                        Página <span class="font-semibold text-slate-700" x-text="productos.current_page"></span>
                        de <span class="font-semibold text-slate-700" x-text="productos.last_page"></span>
                    </p>
                    <div class="flex gap-2">
                        <button @click="cambiarPagina(productos.current_page - 1)"
                                :disabled="productos.current_page <= 1"
                                class="px-3 py-1.5 text-xs border border-slate-200 rounded-lg text-slate-600
                                       hover:bg-white disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            ← Anterior
                        </button>
                        <button @click="cambiarPagina(productos.current_page + 1)"
                                :disabled="productos.current_page >= productos.last_page"
                                class="px-3 py-1.5 text-xs border border-slate-200 rounded-lg text-slate-600
                                       hover:bg-white disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                            Siguiente →
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════ COLUMNA DERECHA: Carrito (40%) ══════════ --}}
        <div class="w-[420px] flex-shrink-0 sticky top-4">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col" style="max-height: calc(100vh - 2rem);">

                {{-- Header carrito --}}
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-4 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-white">
                            <span class="text-xl">🧾</span>
                            <h3 class="font-bold text-base">Venta Actual</h3>
                            <span x-show="carrito.length > 0"
                                  class="bg-white/25 text-white text-xs font-bold px-2 py-0.5 rounded-full"
                                  x-text="carrito.length + (carrito.length === 1 ? ' ítem' : ' ítems')"></span>
                        </div>
                        <button @click="limpiarCarrito()"
                                x-show="carrito.length > 0"
                                title="Limpiar carrito"
                                class="text-white/70 hover:text-white transition-colors text-sm">
                            🗑 Limpiar
                        </button>
                    </div>
                </div>

                <div class="p-4 space-y-4 overflow-y-auto ticket-scroll flex-1">

                    {{-- Aviso stock bajo --}}
                    <div x-show="avisoStockBajo"
                         x-transition
                         class="bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5 text-sm text-amber-700 font-medium flex items-center gap-2">
                        ⚠️ <span x-text="avisoStockBajo"></span>
                    </div>

                    {{-- Venta Rápida Toggle --}}
                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 p-3 rounded-xl border border-slate-100 hover:bg-slate-100 transition-colors">
                        <input type="checkbox" x-model="ventaRapida" class="w-5 h-5 text-emerald-600 rounded-lg focus:ring-emerald-500 border-slate-300">
                        <span class="text-sm font-semibold text-slate-700">⚡ Venta Rápida (Público General)</span>
                    </label>

                    {{-- Selector de Cliente --}}
                    <div x-show="!ventaRapida" x-transition>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">
                            Cliente <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <select id="pos-cliente"
                                    x-model="clienteId"
                                    @change="cargarMascotas()"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500 appearance-none transition-all">
                                <option value="">— Selecciona un cliente —</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}">
                                        {{ $cliente->nombre }} {{ $cliente->apellido_paterno ?? $cliente->apellido ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Selector de Mascota --}}
                    <div x-show="!ventaRapida" x-transition>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">
                            Mascota <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm pointer-events-none">🐾</span>
                            <select id="pos-mascota"
                                    x-model="mascotaId"
                                    :disabled="cargandoMascotas || mascotas.length === 0"
                                    class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500 appearance-none transition-all
                                           disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="" x-text="cargandoMascotas ? 'Cargando...' :
                                    (mascotas.length === 0 && clienteId ? 'Sin mascotas registradas' : '— Selecciona —')"></option>
                                <template x-for="m in mascotas" :key="m.id">
                                    <option :value="m.id"
                                            x-text="m.nombre + ' (' + m.especie + (m.raza ? ' · ' + m.raza : '') + ')'"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    {{-- Lista del carrito --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-2">
                            Productos
                        </label>

                        {{-- Carrito vacío --}}
                        <div x-show="carrito.length === 0" x-transition
                             class="border-2 border-dashed border-slate-200 rounded-xl p-6 text-center">
                            <span class="text-3xl">🛒</span>
                            <p class="text-sm text-slate-400 mt-2">Agrega productos desde el catálogo</p>
                        </div>

                        {{-- Ítems del carrito --}}
                        <div id="carrito-lista" x-show="carrito.length > 0" x-transition class="space-y-2">
                            <template x-for="(item, i) in carrito" :key="item.id">
                                <div class="flex items-center gap-2 bg-slate-50 rounded-xl px-3 py-2.5 border border-slate-100">
                                    {{-- Nombre --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-semibold text-slate-800 truncate" x-text="item.nombre"></p>
                                        <p class="text-xs text-slate-400" x-text="'$' + formatMoney(item.precio)  + ' c/u'"></p>
                                    </div>
                                    {{-- Controles cantidad --}}
                                    <div class="flex items-center gap-1">
                                        <button @click="decrementarCantidad(i)"
                                                class="w-6 h-6 rounded-lg bg-slate-200 hover:bg-slate-300 text-slate-700
                                                       text-sm font-bold flex items-center justify-center transition-colors">−</button>
                                        <span class="w-7 text-center text-sm font-bold text-slate-800" x-text="item.cantidad"></span>
                                        <button @click="incrementarCantidad(i)"
                                                :disabled="item.cantidad >= item.stockDisponible"
                                                class="w-6 h-6 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-700
                                                       text-sm font-bold flex items-center justify-center transition-colors
                                                       disabled:opacity-40 disabled:cursor-not-allowed">+</button>
                                    </div>
                                    {{-- Subtotal --}}
                                    <span class="text-xs font-bold text-slate-700 w-16 text-right"
                                          x-text="'$' + formatMoney(item.precio * item.cantidad)"></span>
                                    {{-- Eliminar --}}
                                    <button @click="quitarDelCarrito(i)"
                                            class="w-6 h-6 flex items-center justify-center text-slate-300
                                                   hover:text-rose-500 transition-colors rounded-lg">
                                        🗑
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div x-show="carrito.length > 0" x-transition
                         class="bg-emerald-50 rounded-xl px-4 py-3 border border-emerald-100">
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-semibold text-emerald-800">TOTAL</span>
                            <span class="text-2xl font-black text-emerald-700"
                                  x-text="'$' + formatMoney(totalCarrito)"></span>
                        </div>
                    </div>

                    {{-- Método de pago --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">
                            Método de Pago
                        </label>
                        <select id="pos-metodo-pago"
                                x-model="metodoPago"
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50
                                       focus:outline-none focus:ring-2 focus:ring-emerald-500 appearance-none transition-all">
                            <option value="">— Selecciona —</option>
                            <option value="Efectivo">💵 Efectivo</option>
                            <option value="Tarjeta">💳 Tarjeta</option>
                            <option value="Transferencia">🏦 Transferencia</option>
                        </select>
                    </div>

                    {{-- Monto recibido + Cambio (solo si Efectivo) --}}
                    <div x-show="metodoPago === 'Efectivo'" x-transition class="space-y-2">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wider mb-1.5">
                                Monto Recibido
                            </label>
                            <input type="number"
                                   id="pos-monto-recibido"
                                   x-model.number="montoRecibido"
                                   placeholder="0.00"
                                   step="0.01"
                                   class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl bg-slate-50
                                          focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all">
                        </div>
                        <div x-show="montoRecibido > 0 && cambio >= 0" class="bg-sky-50 border border-sky-100 rounded-xl px-4 py-2.5">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-sky-700">Cambio a devolver</span>
                                <span class="text-lg font-black text-sky-700"
                                      x-text="'$' + formatMoney(cambio)"></span>
                            </div>
                        </div>
                        <div x-show="montoRecibido > 0 && cambio < 0" class="bg-rose-50 border border-rose-100 rounded-xl px-4 py-2.5">
                            <p class="text-xs font-semibold text-rose-600">⚠️ Monto insuficiente</p>
                        </div>
                    </div>

                    {{-- Botón COBRAR --}}
                    <button type="button"
                            id="btn-cobrar"
                            @click="procesarVenta()"
                            :disabled="cobrando || carrito.length === 0 || (!ventaRapida && (!clienteId || !mascotaId)) || !metodoPago || (metodoPago === 'Efectivo' && cambio < 0)"
                            class="btn-cobrar w-full py-3.5 text-base font-bold flex items-center justify-center gap-2">
                        <template x-if="!cobrando">
                            <span>✅ COBRAR <span x-text="carrito.length > 0 ? '— $' + formatMoney(totalCarrito) : ''"></span></span>
                        </template>
                        <template x-if="cobrando">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Procesando...
                            </span>
                        </template>
                    </button>

                </div>
            </div>
        </div>
        {{-- Modal de Confirmación de Salida --}}
        <div x-cloak x-show="mostrarModalSalida" class="fixed inset-0 z-[300] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm transform transition-all" @click.away="cerrarModalSalida()">
                <h3 class="text-lg font-bold text-slate-800 mb-2">El Sistema dice</h3>
                <p class="text-sm text-slate-600 mb-4">Para salir de la Venta Actual, ingresa la contraseña de Admin:</p>
                <input type="password" id="input-pass-salida" x-model="passSalida" @keydown.enter="confirmarSalida()" class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm mb-4" placeholder="Contraseña">
                <div class="flex justify-end gap-3">
                    <button type="button" @click="cerrarModalSalida()" class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">Cancelar</button>
                    <button type="button" @click="confirmarSalida()" class="px-4 py-2 text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl transition-colors shadow-sm">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <script data-swup-script>
    window.posApp = function() {
        return {
            // Catálogo
            buscar: '',
            categoria: '',
            paginaActual: 1,
            productos: { data: [] },
            cargandoProductos: false,

            // Carrito
            carrito: [],
            avisoStockBajo: '',
            avisoTimer: null,

            // Venta
            ventaRapida: false,
            clienteId: '',
            mascotaId: '',
            mascotas: [],
            cargandoMascotas: false,
            metodoPago: '',
            montoRecibido: '',
            cobrando: false,

            // Modal Salida
            mostrarModalSalida: false,
            targetUrlForExit: null,
            passSalida: '',
            exitInterceptor: null,
            beforeUnloadInterceptor: null,

            get totalCarrito() {
                return this.carrito.reduce((s, i) => s + i.precio * i.cantidad, 0);
            },

            get cambio() {
                return this.montoRecibido - this.totalCarrito;
            },

            init() {
                this.buscarProductos();

                this.exitInterceptor = (e) => {
                    const link = e.target.closest('a');
                    if (link && link.href && !link.href.includes('#') && !link.href.startsWith('javascript:')) {
                        // Ignorar clics dentro del modal
                        if (document.getElementById('modal-salida')?.contains(link)) return;

                        const currentUrl = window.location.origin + window.location.pathname;
                        const linkUrl = link.origin + link.pathname;
                        
                        if (linkUrl !== currentUrl) {
                            e.preventDefault();
                            e.stopPropagation();
                            this.abrirModalSalida(link.href);
                        }
                    }
                };
                document.addEventListener('click', this.exitInterceptor, true);

                this.beforeUnloadInterceptor = (e) => {
                    e.preventDefault();
                    e.returnValue = '';
                };
                window.addEventListener('beforeunload', this.beforeUnloadInterceptor);
            },

            destroy() {
                if (this.exitInterceptor) document.removeEventListener('click', this.exitInterceptor, true);
                if (this.beforeUnloadInterceptor) window.removeEventListener('beforeunload', this.beforeUnloadInterceptor);
            },

            abrirModalSalida(url) {
                this.targetUrlForExit = url;
                this.passSalida = '';
                this.mostrarModalSalida = true;
                setTimeout(() => document.getElementById('input-pass-salida')?.focus(), 50);
            },

            cerrarModalSalida() {
                this.mostrarModalSalida = false;
                this.targetUrlForExit = null;
            },

            confirmarSalida() {
                if (this.passSalida.toLowerCase() === 'password') {
                    this.destroy();
                    if (window.swup) {
                        window.swup.navigate(this.targetUrlForExit);
                    } else {
                        window.location.href = this.targetUrlForExit;
                    }
                } else {
                    alert('Contraseña incorrecta');
                    this.passSalida = '';
                    document.getElementById('input-pass-salida')?.focus();
                }
            },

            async buscarProductos() {
                this.cargandoProductos = true;
                try {
                    const params = new URLSearchParams({
                        page: this.paginaActual,
                        ...(this.buscar    && { buscar:    this.buscar }),
                        ...(this.categoria && { categoria: this.categoria }),
                    });
                    const r = await fetch(`/api/ventas/productos?${params}`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    this.productos = await r.json();
                } catch(e) {
                    console.error(e);
                } finally {
                    this.cargandoProductos = false;
                }
            },

            cambiarPagina(p) {
                if (p < 1 || p > this.productos.last_page) return;
                this.paginaActual = p;
                this.buscarProductos();
            },

            agregarAlCarrito(producto) {
                if (producto.stock === 0) return;

                const existente = this.carrito.find(i => i.id === producto.id);
                if (existente) {
                    if (existente.cantidad >= existente.stockDisponible) return;
                    existente.cantidad++;
                    const restante = existente.stockDisponible - existente.cantidad;
                    if (restante < 10) this.mostrarAvisoStock(producto.nombre, restante);
                } else {
                    this.carrito.push({
                        id:             producto.id,
                        nombre:         producto.nombre,
                        precio:         producto.precio,
                        cantidad:       1,
                        stockDisponible: producto.stock,
                    });
                    const restante = producto.stock - 1;
                    if (restante < 10) this.mostrarAvisoStock(producto.nombre, restante);
                }
            },

            incrementarCantidad(i) {
                const item = this.carrito[i];
                if (item.cantidad < item.stockDisponible) {
                    item.cantidad++;
                    const restante = item.stockDisponible - item.cantidad;
                    if (restante < 10) this.mostrarAvisoStock(item.nombre, restante);
                }
            },

            decrementarCantidad(i) {
                if (this.carrito[i].cantidad > 1) {
                    this.carrito[i].cantidad--;
                } else {
                    this.quitarDelCarrito(i);
                }
            },

            quitarDelCarrito(i) {
                this.carrito.splice(i, 1);
            },

            limpiarCarrito() {
                this.carrito = [];
                this.avisoStockBajo = '';
                this.clienteId = '';
                this.mascotaId = '';
                this.mascotas = [];
                this.metodoPago = '';
                this.montoRecibido = '';
                this.ventaRapida = false;
            },


            mostrarAvisoStock(nombre, restante) {
                clearTimeout(this.avisoTimer);
                this.avisoStockBajo = restante === 0
                    ? `"${nombre}" quedará SIN STOCK tras esta venta`
                    : `"${nombre}" tendrá solo ${restante} unidades disponibles`;
                this.avisoTimer = setTimeout(() => { this.avisoStockBajo = ''; }, 5000);
            },

            async cargarMascotas() {
                this.mascotaId = '';
                this.mascotas = [];
                if (!this.clienteId) return;

                this.cargandoMascotas = true;
                try {
                    const r = await fetch(`/api/ventas/${this.clienteId}/mascotas`, {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    this.mascotas = await r.json();
                } catch(e) {
                    console.error(e);
                } finally {
                    this.cargandoMascotas = false;
                }
            },

            async procesarVenta() {
                if (this.carrito.length === 0) return this.toast('Agrega al menos un producto al carrito.', false);
                if (!this.ventaRapida && !this.clienteId) return this.toast('Selecciona un cliente.', false);
                if (!this.ventaRapida && !this.mascotaId) return this.toast('Selecciona una mascota.', false);
                if (!this.metodoPago) return this.toast('Selecciona el método de pago.', false);
                if (this.metodoPago === 'Efectivo' && this.cambio < 0) return this.toast('El monto recibido es insuficiente.', false);

                this.cobrando = true;

                const payload = {
                    cliente_id:  this.ventaRapida ? null : this.clienteId,
                    mascota_id:  this.ventaRapida ? null : this.mascotaId,
                    metodo_pago: this.metodoPago,
                    total:       this.totalCarrito,
                    productos: this.carrito.map(i => ({
                        producto_id:     i.id,
                        cantidad:        i.cantidad,
                        precio_unitario: i.precio,
                    })),
                };

                try {
                    const r = await fetch('{{ route("ventas.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept':       'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await r.json();

                    if (data.success) {
                        this.toast(`✅ ${data.message} — Folio: ${data.folio}`, true);
                        this.limpiarCarrito();
                        await this.buscarProductos(); // refresca stock
                    } else {
                        this.toast(`❌ ${data.message}`, false);
                    }
                } catch(e) {
                    this.toast('Error al conectar con el servidor.', false);
                } finally {
                    this.cobrando = false;
                }
            },

            toast(msg, exito) {
                const el = document.getElementById('pos-toast');
                el.textContent = msg;
                el.className = `fixed top-6 right-6 z-[200] px-5 py-4 rounded-2xl shadow-2xl text-white text-sm font-semibold max-w-sm
                    ${exito ? 'bg-emerald-600' : 'bg-rose-600'}`;
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';

                setTimeout(() => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-12px)';
                }, 4500);
            },

            formatMoney(n) {
                return Number(n).toLocaleString('es-MX', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            },

            categoriaEmoji(cat) {
                const map = {
                    'Medicamento': '💊',
                    'Vacuna': '💉',
                    'Alimento': '🥩',
                    'Accesorio': '🎀',
                    'Otro': '📦',
                };
                return map[cat] || '📦';
            },
        };
    };
    </script>
</x-app-layout>
