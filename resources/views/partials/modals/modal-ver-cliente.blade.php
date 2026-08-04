{{-- ═══════════════════════════════════════════════════════════
     MODAL — VER CLIENTE
     Abierto/cerrado por Alpine.js
═══════════════════════════════════════════════════════════ --}}
<div
    x-cloak
    x-show="verModalOpen"
    x-data="verClienteComponent()"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    @keydown.escape.window="verModalOpen = false"
    @ver-cliente.window="cargarCliente($event.detail.id)"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="verModalOpen = false"></div>

    {{-- Panel --}}
    <div
        x-show="verModalOpen"
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
        {{-- Barra decorativa top --}}
        <div class="h-1.5 w-full rounded-t-2xl flex-shrink-0"
             style="background:linear-gradient(90deg,#10b981,#059669,#0d9488);"></div>

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-7 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-slate-800 font-bold text-xl flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </span>
                Perfil del Cliente
            </h2>
            <div class="flex gap-2">
                <button type="button" @click="$dispatch('editar-cliente', { id: cliente.id }); verModalOpen = false"
                        class="px-3 py-1.5 text-sm font-semibold text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition-colors flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Editar
                </button>
                <button type="button" @click="verModalOpen = false"
                        class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- CONTENIDO --}}
        <div class="px-7 py-6 space-y-6">
            
            {{-- Info Principal --}}
            <div class="flex items-start gap-6">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-sm"
                     style="background: linear-gradient(135deg, #10b981, #059669)">
                    <span x-text="iniciales()"></span>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800" 
                        x-text="truncateStr(cliente.nombre + ' ' + (cliente.apellido || ''), 25)"
                        :title="cliente.nombre + ' ' + (cliente.apellido || '')"></h3>
                    <div class="flex items-center gap-3 mt-1.5">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                            <span class="w-1.5 h-1.5 rounded-full" :class="cliente.estado === 'activo' ? 'bg-emerald-500' : 'bg-slate-400'"></span>
                            <span class="capitalize" x-text="cliente.estado || 'Activo'"></span>
                        </span>
                        <span class="text-xs text-slate-400" x-text="'Registrado el ' + (cliente.created_at || '')"></span>
                    </div>
                </div>
            </div>

            {{-- Grid de Datos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Contacto</p>
                    <p class="text-slate-800 font-medium flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span x-text="(cliente.codigo_pais || '') + ' ' + (cliente.telefono || '—')"></span>
                    </p>
                    <p class="text-slate-800 font-medium flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span x-text="truncateStr(cliente.email || 'No especificado', 25)" :title="cliente.email || 'No especificado'"></span>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Dirección</p>
                    <p class="text-slate-800 font-medium flex items-start gap-2">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>
                            <span x-text="truncateStr(cliente.direccion || 'No especificada', 30)" :title="cliente.direccion || 'No especificada'"></span>
                            <template x-if="cliente.codigo_postal">
                                <span class="block text-slate-500 text-sm mt-0.5" x-text="'CP: ' + cliente.codigo_postal"></span>
                            </template>
                        </span>
                    </p>
                </div>
            </div>

            {{-- Mascotas --}}
            <div>
                <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                    Mascotas Registradas
                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-0.5 rounded-full" x-text="cliente.mascotas?.length || 0"></span>
                </h4>

                <template x-if="!cliente.mascotas || cliente.mascotas.length === 0">
                    <div class="text-center py-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                        <p class="text-slate-500 text-sm">Este cliente no tiene mascotas.</p>
                    </div>
                </template>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <template x-for="mascota in cliente.mascotas" :key="mascota.id || mascota.nombre">
                        <div class="border border-slate-100 rounded-2xl p-4 flex gap-4 hover:border-emerald-100 hover:shadow-sm transition-all bg-white">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                                 style="background:linear-gradient(135deg,#d1fae5,#a7f3d0); border: 1px solid #6ee7b7;"
                                 x-text="getEspecieIcon(mascota.especie)">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h5 class="font-bold text-slate-800 text-base truncate" x-text="mascota.nombre"></h5>
                                <p class="text-xs text-slate-500 capitalize mb-2">
                                    <span x-text="mascota.especie"></span>
                                    <template x-if="mascota.raza"><span x-text="' • ' + mascota.raza"></span></template>
                                    <template x-if="mascota.sexo"><span x-text="' • ' + mascota.sexo"></span></template>
                                </p>
                                
                                <div class="grid grid-cols-2 gap-1 text-[11px] bg-slate-50 p-2 rounded-lg">
                                    <div class="truncate"><span class="text-slate-400">Peso:</span> <span class="font-medium text-slate-700" x-text="mascota.peso ? mascota.peso + ' kg' : '—'"></span></div>
                                    <div class="truncate"><span class="text-slate-400">Color:</span> <span class="font-medium text-slate-700" x-text="mascota.color_pelaje || '—'"></span></div>
                                    <div class="col-span-2 truncate"><span class="text-slate-400">Nota:</span> <span class="font-medium text-slate-700" x-text="mascota.nota_medica || '—'"></span></div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function verClienteComponent() {
    return {
        verModalOpen: false,
        cliente: {},

        async cargarCliente(id) {
            this.$dispatch('show-loader');
            try {
                const res = await fetch(`/clientes/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    this.cliente = data.cliente;
                    this.verModalOpen = true;
                } else {
                    alert('No se pudo cargar la información del cliente.');
                }
            } catch (e) {
                alert('Error de red.');
            } finally {
                this.$dispatch('hide-loader');
            }
        },

        iniciales() {
            if (!this.cliente.nombre) return '';
            const n = this.cliente.nombre.charAt(0).toUpperCase();
            const a = this.cliente.apellido_paterno ? this.cliente.apellido_paterno.charAt(0).toUpperCase() : '';
            return n + a;
        },

        getEspecieIcon(especie) {
            if(!especie) return '🐾';
            const map = {
                'perro': '🐕', 'gato': '🐱', 'conejo': '🐰',
                'aves': '🐦', 'ave': '🐦', 'reptil': '🦎', 'otro': '🐾'
            };
            return map[especie.toLowerCase()] || '🐾';
        },

        truncateStr(str, limit) {
            if (!str) return '';
            return str.length > limit ? str.substring(0, limit) + '...' : str;
        }
    }
}
</script>
