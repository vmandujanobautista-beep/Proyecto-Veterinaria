{{-- ═══════════════════════════════════════════════════════════
     MODAL — SOLICITAR REABASTECIMIENTO
     Solo visible cuando el producto tiene stock < 10.
     Evento: @solicitar-reabastecimiento.window="abrirModal($event.detail)"
═══════════════════════════════════════════════════════════ --}}
<div
    x-cloak
    x-show="reabastecimientoOpen"
    x-data="reabastecimientoComponent()"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[70] flex items-center justify-center p-4"
    @keydown.escape.window="reabastecimientoOpen = false"
    @solicitar-reabastecimiento.window="abrirModal($event.detail)"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);"
         @click="!enviando && (reabastecimientoOpen = false)"></div>

    {{-- Panel --}}
    <div
        x-show="reabastecimientoOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="relative w-full bg-white rounded-2xl shadow-2xl overflow-hidden"
        style="max-width:480px;"
        @click.stop
    >
        {{-- Barra decorativa top (color según urgencia) --}}
        <div class="h-1.5 w-full rounded-t-2xl"
             :class="producto.stock === 0
                 ? 'bg-gradient-to-r from-red-600 to-rose-500'
                 : (producto.stock <= 4
                     ? 'bg-gradient-to-r from-orange-600 to-amber-500'
                     : 'bg-gradient-to-r from-amber-500 to-yellow-400')">
        </div>

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-slate-800 font-bold text-lg flex items-center gap-2">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-sm"
                      :class="producto.stock === 0 ? 'bg-red-100 text-red-600' : (producto.stock <= 4 ? 'bg-orange-100 text-orange-600' : 'bg-amber-100 text-amber-600')">
                    <span x-text="producto.stock === 0 ? '🔴' : (producto.stock <= 4 ? '⚠️' : '📦')"></span>
                </span>
                Solicitar Reabastecimiento
            </h2>
            <button type="button" @click="reabastecimientoOpen = false" :disabled="enviando"
                    class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors disabled:opacity-40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- BODY --}}
        <div class="px-6 py-5 space-y-4">

            {{-- Info del producto --}}
            <div class="rounded-xl border p-4"
                 :class="producto.stock === 0
                     ? 'bg-red-50 border-red-200'
                     : (producto.stock <= 4
                         ? 'bg-orange-50 border-orange-200'
                         : 'bg-amber-50 border-amber-200')">
                <p class="text-xs font-semibold uppercase tracking-wider mb-2"
                   :class="producto.stock === 0 ? 'text-red-600' : (producto.stock <= 4 ? 'text-orange-600' : 'text-amber-600')">
                    Producto a reabastecer
                </p>
                <p class="font-bold text-slate-800 text-base leading-tight" x-text="producto.nombre"></p>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <code class="text-xs bg-white/80 text-slate-500 px-2 py-0.5 rounded font-mono border border-slate-200"
                          x-text="producto.codigo || 'Sin código'"></code>
                    <span class="text-xs text-slate-400 capitalize" x-text="producto.categoria"></span>
                </div>
            </div>

            {{-- Stock actual prominente --}}
            <div class="flex items-center gap-4 px-4 py-3 rounded-xl bg-slate-50 border border-slate-100">
                <div class="text-center flex-shrink-0">
                    <p class="text-3xl font-black"
                       :class="producto.stock === 0 ? 'text-red-600' : (producto.stock <= 4 ? 'text-orange-500' : 'text-amber-500')"
                       x-text="producto.stock"></p>
                    <p class="text-xs text-slate-400 font-medium">unidades</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-700">Stock actual</p>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        <span x-text="producto.stock === 0
                            ? 'El producto está completamente agotado.'
                            : (producto.stock <= 4
                                ? 'Quedan muy pocas unidades. Reabastecimiento urgente.'
                                : 'El stock está por debajo del nivel recomendado.')">
                        </span>
                    </p>
                </div>
            </div>

            {{-- Mensaje informativo --}}
            <div class="flex items-start gap-2.5 bg-sky-50 border border-sky-100 rounded-xl px-4 py-3">
                <svg class="w-4 h-4 text-sky-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-sky-700 leading-relaxed">
                    Se enviará una notificación por email al administrador con los datos del producto y tu nombre como solicitante.
                </p>
            </div>

            {{-- Mensaje de éxito --}}
            <template x-if="mensajeExito">
                <div class="flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-sm font-semibold text-emerald-700" x-text="mensajeExito"></p>
                </div>
            </template>

            {{-- Mensaje de error --}}
            <template x-if="mensajeError">
                <div class="flex items-center gap-2.5 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <p class="text-sm font-semibold text-rose-700" x-text="mensajeError"></p>
                </div>
            </template>
        </div>

        {{-- FOOTER / BOTONES --}}
        <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-3 bg-slate-50/50">
            <button type="button"
                    @click="reabastecimientoOpen = false"
                    :disabled="enviando"
                    x-show="!mensajeExito"
                    class="px-4 py-2.5 text-sm font-medium text-slate-600 border border-slate-200
                           rounded-xl hover:bg-slate-100 transition-colors disabled:opacity-40">
                Cancelar
            </button>

            {{-- Botón cerrar tras éxito --}}
            <button type="button"
                    @click="reabastecimientoOpen = false"
                    x-show="mensajeExito"
                    class="px-5 py-2.5 text-sm font-semibold text-white bg-emerald-500
                           hover:bg-emerald-600 rounded-xl transition-colors">
                ✓ Cerrar
            </button>

            {{-- Botón enviar --}}
            <button type="button"
                    @click="enviarSolicitud()"
                    :disabled="enviando"
                    x-show="!mensajeExito"
                    :class="producto.stock === 0
                        ? 'bg-red-600 hover:bg-red-700'
                        : (producto.stock <= 4
                            ? 'bg-orange-500 hover:bg-orange-600'
                            : 'bg-amber-500 hover:bg-amber-600')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white
                           rounded-xl transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                <template x-if="enviando">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </template>
                <template x-if="!enviando">
                    <span x-text="producto.stock === 0 ? '🔴' : (producto.stock <= 4 ? '⚠️' : '📦')"></span>
                </template>
                <span x-text="enviando
                    ? 'Enviando...'
                    : (producto.stock === 0
                        ? 'Reabastecer urgente'
                        : (producto.stock <= 4
                            ? 'Solicitar urgente'
                            : 'Enviar solicitud'))">
                </span>
            </button>
        </div>
    </div>
</div>

<script>
function reabastecimientoComponent() {
    return {
        reabastecimientoOpen: false,
        enviando: false,
        mensajeExito: '',
        mensajeError: '',
        producto: {},

        abrirModal(detalle) {
            this.producto      = detalle;
            this.mensajeExito  = '';
            this.mensajeError  = '';
            this.enviando      = false;
            this.reabastecimientoOpen = true;
        },

        async enviarSolicitud() {
            this.enviando     = true;
            this.mensajeError = '';
            this.$dispatch('show-loader');

            try {
                const res = await fetch(`/productos/${this.producto.id}/solicitar-reabastecimiento`, {
                    method: 'POST',
                    headers: {
                        'Accept':           'application/json',
                        'Content-Type':     'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                    },
                });

                const data = await res.json();

                if (res.ok && data.success) {
                    this.mensajeExito = data.message;
                } else {
                    this.mensajeError = data.message || 'No se pudo enviar la solicitud.';
                }
            } catch (e) {
                this.mensajeError = 'Error de red. Intenta de nuevo.';
            } finally {
                this.enviando = false;
                this.$dispatch('hide-loader');
            }
        },
    }
}
</script>
