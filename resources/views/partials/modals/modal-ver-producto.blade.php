{{-- ═══════════════════════════════════════════════════════════
     MODAL — VER PRODUCTO
     Solo lectura. Abierto/cerrado por Alpine.js
     Evento: @ver-producto.window="cargarProducto($event.detail.id)"
═══════════════════════════════════════════════════════════ --}}
<div
    x-cloak
    x-show="verProductoModalOpen"
    x-data="verProductoComponent()"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    @keydown.escape.window="verProductoModalOpen = false"
    @ver-producto.window="cargarProducto($event.detail.id)"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="verProductoModalOpen = false"></div>

    {{-- Panel --}}
    <div
        x-show="verProductoModalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="relative w-full bg-white rounded-2xl shadow-2xl overflow-y-auto"
        style="max-width:640px; max-height:92vh;"
        @click.stop
    >
        {{-- Barra decorativa top (color según categoría) --}}
        <div class="h-1.5 w-full rounded-t-2xl flex-shrink-0"
             :style="getCategoryGradient(producto.categoria)"></div>

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-7 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-slate-800 font-bold text-xl flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm"
                      :style="getCategoryIconBg(producto.categoria)"
                      x-text="getCategoryEmoji(producto.categoria)"></span>
                Ficha del Producto
            </h2>
            <button type="button" @click="verProductoModalOpen = false"
                    class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- LOADER INTERNO --}}
        <template x-if="loading">
            <div class="flex flex-col items-center justify-center py-20 gap-4">
                <div class="w-10 h-10 border-4 border-slate-200 border-t-blue-500 rounded-full animate-spin"></div>
                <p class="text-sm text-slate-400">Cargando producto…</p>
            </div>
        </template>

        {{-- CONTENIDO --}}
        <template x-if="!loading && producto.id">
            <div class="px-7 py-6 space-y-6">

                {{-- Cabecera del producto --}}
                <div class="flex items-start gap-5">
                    {{-- Icono grande --}}
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-4xl flex-shrink-0 shadow-sm"
                         :style="getCategoryIconBg(producto.categoria)">
                        <span x-text="getCategoryEmoji(producto.categoria)"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-2xl font-bold text-slate-800 leading-tight" x-text="producto.nombre"></h3>
                        <div class="flex items-center gap-3 mt-2 flex-wrap">
                            {{-- Badge categoría --}}
                            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full"
                                  :class="getCategoryBadgeClass(producto.categoria)">
                                <span x-text="getCategoryEmoji(producto.categoria)"></span>
                                <span x-text="capitalize(producto.categoria)"></span>
                            </span>
                            {{-- Badge stock --}}
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1 rounded-full"
                                  :class="getStockBadgeClass(producto.stock)">
                                <span x-text="getStockDot(producto.stock)" class="text-[8px]">●</span>
                                <span x-text="getStockLabel(producto.stock)"></span>
                            </span>
                        </div>
                        {{-- Código --}}
                        <code class="mt-2 inline-block text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-mono"
                              x-text="producto.codigo || 'Sin código'"></code>
                    </div>
                </div>

                {{-- Grid de datos principales --}}
                <div class="grid grid-cols-2 gap-4">
                    {{-- Precio --}}
                    <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
                        <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-1">Precio de venta</p>
                        <p class="text-2xl font-black text-emerald-700"
                           x-text="'$' + parseFloat(producto.precio).toFixed(2)"></p>
                    </div>
                    {{-- Stock --}}
                    <div class="rounded-2xl p-4 border"
                         :class="getStockCardClass(producto.stock)">
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1"
                           :class="getStockLabelColor(producto.stock)">Unidades en stock</p>
                        <p class="text-2xl font-black"
                           :class="getStockNumColor(producto.stock)"
                           x-text="producto.stock + ' uds'"></p>
                    </div>
                </div>

                {{-- Descripción --}}
                <template x-if="producto.descripcion">
                    <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Descripción</p>
                        <p class="text-sm text-slate-700 leading-relaxed" x-text="producto.descripcion"></p>
                    </div>
                </template>

                {{-- Metadatos --}}
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">Registrado</span>
                        <span class="text-slate-700 font-semibold" x-text="producto.created_at || '—'"></span>
                    </div>
                    <div class="flex flex-col gap-0.5">
                        <span class="text-xs text-slate-400 font-medium uppercase tracking-wider">Última actualización</span>
                        <span class="text-slate-700 font-semibold" x-text="producto.updated_at || '—'"></span>
                    </div>
                </div>

                {{-- Footer info recepcionista --}}
                <div class="bg-sky-50 border border-sky-100 rounded-xl px-4 py-3 flex items-center gap-3">
                    <svg class="w-4 h-4 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-xs text-sky-700">Esta es una vista de consulta. Para modificar el inventario, comunícate con el administrador.</p>
                </div>
            </div>
        </template>

    </div>
</div>

<script>
function verProductoComponent() {
    return {
        verProductoModalOpen: false,
        producto: {},
        loading: false,

        async cargarProducto(id) {
            this.loading = true;
            this.producto = {};
            this.$dispatch('show-loader');
            try {
                const res = await fetch(`/productos/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    this.producto = data.producto;
                    this.verProductoModalOpen = true;
                } else {
                    alert('No se pudo cargar la información del producto.');
                }
            } catch (e) {
                alert('Error de red.');
            } finally {
                this.loading = false;
                this.$dispatch('hide-loader');
            }
        },

        getCategoryEmoji(cat) {
            if (!cat) return '📦';
            const map = {
                'medicamento': '💊', 'vacuna': '💉',
                'alimento': '🍖', 'accesorio': '🎀', 'otro': '📦'
            };
            return map[cat.toLowerCase()] || '📦';
        },

        getCategoryGradient(cat) {
            if (!cat) return 'background: linear-gradient(90deg,#94a3b8,#64748b)';
            const map = {
                'medicamento': 'background: linear-gradient(90deg,#3b82f6,#2563eb)',
                'vacuna':      'background: linear-gradient(90deg,#8b5cf6,#6d28d9)',
                'alimento':    'background: linear-gradient(90deg,#10b981,#059669)',
                'accesorio':   'background: linear-gradient(90deg,#f97316,#ea580c)',
                'otro':        'background: linear-gradient(90deg,#94a3b8,#64748b)',
            };
            return map[cat.toLowerCase()] || 'background: linear-gradient(90deg,#94a3b8,#64748b)';
        },

        getCategoryIconBg(cat) {
            if (!cat) return 'background:#f1f5f9; color:#64748b';
            const map = {
                'medicamento': 'background:#dbeafe; color:#1d4ed8',
                'vacuna':      'background:#ede9fe; color:#6d28d9',
                'alimento':    'background:#d1fae5; color:#059669',
                'accesorio':   'background:#ffedd5; color:#ea580c',
                'otro':        'background:#f1f5f9; color:#64748b',
            };
            return map[cat.toLowerCase()] || 'background:#f1f5f9; color:#64748b';
        },

        getCategoryBadgeClass(cat) {
            if (!cat) return 'bg-slate-100 text-slate-600';
            const map = {
                'medicamento': 'bg-blue-100 text-blue-700',
                'vacuna':      'bg-violet-100 text-violet-700',
                'alimento':    'bg-emerald-100 text-emerald-700',
                'accesorio':   'bg-orange-100 text-orange-700',
                'otro':        'bg-slate-100 text-slate-600',
            };
            return map[cat.toLowerCase()] || 'bg-slate-100 text-slate-600';
        },

        getStockBadgeClass(stock) {
            if (stock > 20)         return 'bg-emerald-100 text-emerald-700';
            if (stock >= 5)         return 'bg-amber-100 text-amber-700';
            if (stock >= 1)         return 'bg-rose-100 text-rose-700';
            return 'bg-slate-200 text-slate-600';
        },

        getStockCardClass(stock) {
            if (stock > 20)  return 'bg-emerald-50 border-emerald-100';
            if (stock >= 5)  return 'bg-amber-50 border-amber-100';
            if (stock >= 1)  return 'bg-rose-50 border-rose-100';
            return 'bg-slate-100 border-slate-200';
        },

        getStockLabelColor(stock) {
            if (stock > 20)  return 'text-emerald-600';
            if (stock >= 5)  return 'text-amber-600';
            if (stock >= 1)  return 'text-rose-600';
            return 'text-slate-500';
        },

        getStockNumColor(stock) {
            if (stock > 20)  return 'text-emerald-700';
            if (stock >= 5)  return 'text-amber-700';
            if (stock >= 1)  return 'text-rose-700';
            return 'text-slate-500';
        },

        getStockLabel(stock) {
            if (stock > 20)  return 'Disponible';
            if (stock >= 5)  return 'Stock bajo';
            if (stock >= 1)  return 'Últimas unidades';
            return 'Agotado';
        },

        getStockDot(stock) {
            return '●';
        },

        capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
        }
    }
}
</script>
