{{-- ═══════════════════════════════════════════════════════════
     MODAL — VER DETALLE DE CITA
     Abierto con: $dispatch('ver-cita', { id: citaId })
     Carga datos de la cita via fetch JSON
════════════════════════════════════════════════════════════ --}}

<div
    x-cloak
    x-data="verCitaModal()"
    x-show="open"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    @keydown.escape.window="cerrar()"
    @ver-cita.window="abrir($event.detail.id)"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="cerrar()"></div>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="relative w-full bg-white rounded-2xl shadow-2xl overflow-y-auto"
        style="max-width:700px; max-height:92vh;"
        @click.stop
    >
        {{-- Barra decorativa top morada --}}
        <div class="h-1.5 w-full rounded-t-2xl bg-gradient-to-r from-violet-600 to-fuchsia-600"></div>

        {{-- Header --}}
        <div class="flex items-center justify-between px-7 py-4 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 bg-violet-100 text-violet-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-slate-800 font-bold text-lg leading-tight">Detalle de Cita</h3>
                    <p class="text-slate-400 text-xs" x-text="cita ? 'Cita #' + cita.id : 'Cargando...'"></p>
                </div>
            </div>
            <button @click="cerrar()"
                    class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Loader --}}
        <div x-show="cargando" class="p-12 flex flex-col items-center gap-3">
            <svg class="w-10 h-10 text-violet-500 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <p class="text-sm text-slate-400">Cargando información...</p>
        </div>

        {{-- Content --}}
        <div x-show="!cargando && cita" class="p-6 space-y-5">

            {{-- Badge Estado --}}
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center gap-2 text-sm font-bold px-4 py-1.5 rounded-full"
                      :class="estadoBadgeClass">
                    <span class="w-2 h-2 rounded-full" :class="estadoDotClass"></span>
                    <span x-text="estadoLabel"></span>
                </span>
                <div class="flex items-center gap-2 text-sm text-slate-500">
                    <span :class="cita?.enviado_email ? 'text-emerald-400' : 'text-slate-300'"
                          :title="cita?.enviado_email ? 'Email enviado' : 'Email no enviado'"
                          class="text-lg cursor-default">📧</span>
                    <span :class="cita?.enviado_whatsapp ? 'text-emerald-400' : 'text-slate-300'"
                          :title="cita?.enviado_whatsapp ? 'WhatsApp enviado' : 'WhatsApp no enviado'"
                          class="text-lg cursor-default">📱</span>
                </div>
            </div>

            {{-- Grid de datos --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Datos del cliente --}}
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Propietario
                    </p>
                    <p class="text-sm font-bold text-slate-800" x-text="clienteNombre"></p>
                    <p x-show="cliente?.telefono"
                       class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                        <span>📞</span> <span x-text="cliente?.telefono"></span>
                    </p>
                    <p x-show="cliente?.email"
                       class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
                        <span>✉️</span> <span x-text="cliente?.email"></span>
                    </p>
                </div>

                {{-- Datos de la mascota --}}
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <span>🐾</span> Mascota
                    </p>
                    <p class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span x-text="mascotaIcono" class="text-base"></span>
                        <span x-text="mascota?.nombre"></span>
                    </p>
                    <p class="text-xs text-slate-500 mt-1" x-text="mascota?.especie + (mascota?.raza ? ' · ' + mascota?.raza : '')"></p>
                    <p x-show="mascotaEdad" class="text-xs text-slate-500 mt-0.5" x-text="'Edad: ' + mascotaEdad"></p>
                </div>
            </div>

            {{-- Detalles de la cita --}}
            <div class="bg-violet-50 rounded-xl p-4 border border-violet-100">
                <p class="text-xs font-semibold text-violet-700 uppercase tracking-wider mb-3">Detalles de la Cita</p>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    <div>
                        <p class="text-xs text-slate-500">Fecha</p>
                        <p class="text-sm font-semibold text-slate-800" x-text="fechaFormateada"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Hora</p>
                        <p class="text-sm font-semibold text-slate-800" x-text="cita?.hora"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Servicio</p>
                        <p class="text-sm font-semibold text-slate-800" x-text="cita?.tipo_servicio"></p>
                    </div>
                </div>
                <div x-show="cita?.motivo" class="mt-3 pt-3 border-t border-violet-200">
                    <p class="text-xs text-slate-500 mb-1">Motivo / Descripción</p>
                    <p class="text-sm text-slate-700 leading-relaxed" x-text="cita?.motivo"></p>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="border-t border-slate-100 pt-4">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Acciones</p>
                <div class="flex flex-wrap gap-2">

                    {{-- Completar --}}
                    <template x-if="cita && (cita.estado === 'confirmada' || cita.estado === 'pendiente')">
                        <form :action="`/citas/${cita.id}/completar`" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg transition-colors">
                                ✔ Completar
                            </button>
                        </form>
                    </template>

                    {{-- Enviar Email --}}
                    <template x-if="cita && !cita.enviado_email && cliente?.email">
                        <form :action="`/citas/${cita.id}/enviar-email`" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                📧 Enviar Email
                            </button>
                        </form>
                    </template>

                    {{-- Enviar WhatsApp --}}
                    <template x-if="cita && !cita.enviado_whatsapp && cliente?.telefono">
                        <form :action="`/citas/${cita.id}/enviar-whatsapp`" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg transition-colors">
                                📱 Enviar WhatsApp
                            </button>
                        </form>
                    </template>
                </div>
            </div>


        </div>
    </div>
</div>

<script>
function verCitaModal() {
    return {
        open: false,
        cargando: false,
        cita: null,
        cliente: null,
        mascota: null,

        get clienteNombre() {
            if (!this.cliente) return '';
            return [this.cliente.nombre, this.cliente.apellido_paterno ?? this.cliente.apellido, this.cliente.apellido_materno].filter(Boolean).join(' ');
        },

        get mascotaIcono() {
            if (!this.mascota) return '🐾';
            const icons = { 'Perro': '🐶', 'Gato': '🐱', 'Ave': '🦜', 'Conejo': '🐰', 'Reptil': '🦎' };
            return icons[this.mascota.especie] || '🐾';
        },

        get mascotaEdad() {
            if (!this.mascota?.fecha_nacimiento) return null;
            const nacimiento = new Date(this.mascota.fecha_nacimiento);
            const hoy = new Date();
            const años = hoy.getFullYear() - nacimiento.getFullYear();
            const meses = hoy.getMonth() - nacimiento.getMonth();
            const totalMeses = años * 12 + meses;
            if (totalMeses < 12) return totalMeses + ' meses';
            const a = Math.floor(totalMeses / 12);
            const m = totalMeses % 12;
            return a + ' año' + (a > 1 ? 's' : '') + (m > 0 ? ' ' + m + ' mes' + (m > 1 ? 'es' : '') : '');
        },

        get fechaFormateada() {
            if (!this.cita?.fecha) return '';
            const [y, m, d] = this.cita.fecha.split('T')[0].split('-');
            return `${d}/${m}/${y}`;
        },

        get estadoLabel() {
            const labels = { pendiente: 'Pendiente', confirmada: 'Confirmada', completada: 'Completada', cancelada: 'Cancelada' };
            return labels[this.cita?.estado] || '';
        },

        get estadoBadgeClass() {
            const classes = {
                pendiente:  'bg-amber-100 text-amber-700',
                confirmada: 'bg-sky-100 text-sky-700',
                completada: 'bg-emerald-100 text-emerald-700',
                cancelada:  'bg-rose-100 text-rose-700',
            };
            return classes[this.cita?.estado] || 'bg-slate-100 text-slate-600';
        },

        get estadoDotClass() {
            const classes = {
                pendiente:  'bg-amber-500',
                confirmada: 'bg-sky-500',
                completada: 'bg-emerald-500',
                cancelada:  'bg-rose-500',
            };
            return classes[this.cita?.estado] || 'bg-slate-400';
        },

        get estadoColor() {
            const colors = { pendiente: '#f59e0b', confirmada: '#0ea5e9', completada: '#10b981', cancelada: '#f43f5e' };
            return colors[this.cita?.estado] || '#94a3b8';
        },

        get estadoGradient() {
            const g = {
                pendiente:  'linear-gradient(90deg,#f59e0b,#fbbf24)',
                confirmada: 'linear-gradient(90deg,#0ea5e9,#38bdf8)',
                completada: 'linear-gradient(90deg,#10b981,#34d399)',
                cancelada:  'linear-gradient(90deg,#f43f5e,#fb7185)',
            };
            return g[this.cita?.estado] || 'linear-gradient(90deg,#94a3b8,#cbd5e1)';
        },

        abrir(id) {
            this.open = true;
            this.cita = null;
            this.cliente = null;
            this.mascota = null;
            this.cargando = true;

            fetch(`/citas/${id}`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    this.cita    = data.cita;
                    this.cliente = data.cliente;
                    this.mascota = data.mascota;
                })
                .catch(() => { this.cerrar(); })
                .finally(() => { this.cargando = false; });
        },

        cerrar() {
            this.open = false;
        }
    };
}
</script>
