{{-- ═══════════════════════════════════════════════════════════
     MODAL — CANCELAR CITA
     Abierto con: $dispatch('cancelar-cita', { id: citaId })
     Solicita motivo de cancelación (obligatorio)
════════════════════════════════════════════════════════════ --}}

<div
    x-cloak
    x-data="cancelarCitaModal()"
    x-show="open"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[90] flex items-center justify-center p-4"
    @keydown.escape.window="cerrar()"
    @cancelar-cita.window="abrir($event.detail.id)"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/70" style="backdrop-filter:blur(4px);" @click="cerrar()"></div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        class="relative w-full bg-white rounded-2xl shadow-2xl"
        style="max-width:480px;"
        @click.stop
    >
        {{-- Barra roja top --}}
        <div class="h-1.5 w-full rounded-t-2xl"
             style="background:linear-gradient(90deg,#f43f5e,#e11d48,#be123c);"></div>

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-slate-100">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0 group">
                    <svg class="w-6 h-6 text-rose-600 overflow-visible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M18 6l-12 12" class="origin-center transition-transform duration-200 ease-out group-hover:rotate-[15deg] group-hover:scale-110" />
                        <path d="M6 6l12 12" class="origin-center transition-transform duration-200 ease-out group-hover:-rotate-[15deg] group-hover:scale-110" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-slate-800 font-bold text-lg">Cancelar Cita</h3>
                    <p class="text-slate-500 text-sm mt-0.5">Esta acción cambiará el estado de la cita a <strong>Cancelada</strong>. Por favor indica el motivo.</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form :action="`/citas/${citaId}/cancelar`" method="POST" id="form-cancelar-cita"
              @submit="enviando = true">
            @csrf

            <div class="px-6 py-5">
                <label for="motivo_cancelacion"
                       class="block text-sm font-semibold text-slate-700 mb-2">
                    Motivo de cancelación <span class="text-rose-500">*</span>
                </label>
                <textarea id="motivo_cancelacion"
                          name="motivo_cancelacion"
                          rows="4"
                          required
                          minlength="5"
                          placeholder="Ej: El cliente canceló porque su mascota se recuperó, cambio de fecha solicitado, emergencia del propietario..."
                          class="w-full px-4 py-3 text-sm border border-slate-200 bg-slate-50 rounded-xl resize-none
                                 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent
                                 hover:border-slate-300 transition-all"
                          x-ref="motivoTextarea"></textarea>
                <p class="text-xs text-slate-400 mt-1.5">Mínimo 5 caracteres. Este motivo quedará registrado en la cita.</p>
            </div>

            {{-- Footer --}}
            <div class="px-6 pb-6 flex flex-col sm:flex-row gap-3 justify-end">
                <button type="button"
                        @click="cerrar()"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl
                               hover:bg-slate-50 transition-colors w-full sm:w-auto text-center">
                    No cancelar
                </button>
                <button type="submit"
                        :disabled="enviando"
                        class="inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700
                               active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl
                               transition-all duration-200 shadow-sm hover:shadow-md w-full sm:w-auto
                               disabled:opacity-70 disabled:cursor-not-allowed group relative">
                    <template x-if="!enviando">
                        <svg class="w-4 h-4 overflow-visible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 6l-12 12" class="origin-center transition-transform duration-200 ease-out group-hover:rotate-[15deg] group-hover:scale-110" />
                            <path d="M6 6l12 12" class="origin-center transition-transform duration-200 ease-out group-hover:-rotate-[15deg] group-hover:scale-110" />
                        </svg>
                    </template>
                    <template x-if="enviando">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </template>
                    <span x-text="enviando ? 'Cancelando...' : 'Confirmar Cancelación'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function cancelarCitaModal() {
    return {
        open: false,
        citaId: null,
        enviando: false,

        abrir(id) {
            this.citaId = id;
            this.enviando = false;
            this.open = true;

            this.$nextTick(() => {
                if (this.$refs.motivoTextarea) {
                    this.$refs.motivoTextarea.value = '';
                    this.$refs.motivoTextarea.focus();
                }
            });
        },

        cerrar() {
            this.open = false;
            this.citaId = null;
            this.enviando = false;
        }
    };
}
</script>
