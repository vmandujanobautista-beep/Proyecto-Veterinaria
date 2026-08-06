{{-- ═══════════════════════════════════════════════════════════
     MODAL — VER MASCOTA
     Abierto/cerrado por Alpine.js
═══════════════════════════════════════════════════════════ --}}
<div
    x-cloak
    x-show="verMascotaModalOpen"
    x-data="verMascotaComponent()"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    @keydown.escape.window="verMascotaModalOpen = false"
    @ver-mascota.window="cargarMascota($event.detail.id)"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="verMascotaModalOpen = false"></div>

    {{-- Panel --}}
    <div
        x-show="verMascotaModalOpen"
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
             style="background:linear-gradient(90deg,#3b82f6,#2563eb,#1d4ed8);"></div>

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-7 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-slate-800 font-bold text-xl flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm">
                    🐾
                </span>
                Perfil de la Mascota
            </h2>
            <div class="flex gap-2">
                <!-- Se podría agregar el botón Editar mascota aquí si existe un evento, pero de momento redirigimos a route edit si es necesario, o lo dejamos como icono animado -->
                <button type="button" @click="$dispatch('editar-mascota', { id: mascota.id })"
                        class="group/edit px-3 py-1.5 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors flex items-center gap-1.5 overflow-visible">
                    <svg class="w-4 h-4 overflow-visible" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10">
                        <g class="pen-group" style="transform-origin: 50% 50%;">
                            <path class="pen-slash opacity-0 transition-opacity duration-300 group-hover/edit:opacity-100" d="M20 6 L26 12" />
                            <path class="pen-body" d="m10.5,27.5l-8,2 2-8L22.257,3.743c1.657-1.657,4.343-1.657,6,0s1.657,4.343,0,6L10.5,27.5Z" />
                        </g>
                    </svg>
                    Editar
                </button>
                <button type="button" @click="verMascotaModalOpen = false"
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
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-white font-bold text-4xl shadow-sm"
                     style="background: linear-gradient(135deg, #3b82f6, #1d4ed8)">
                    <span x-text="getEspecieIcon(mascota.especie)"></span>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-800" 
                        x-text="truncateStr(mascota.nombre, 25)"
                        :title="mascota.nombre"></h3>
                    <div class="flex items-center gap-3 mt-1.5">
                        <template x-if="mascota.sexo">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold text-white capitalize"
                                  :class="mascota.sexo.toLowerCase() === 'macho' ? 'bg-blue-500' : 'bg-pink-500'">
                                <span x-text="(mascota.sexo.toLowerCase() === 'macho' ? '♂ ' : '♀ ') + mascota.sexo"></span>
                            </span>
                        </template>
                        <span class="text-xs text-slate-400" x-text="'Registrado el ' + (mascota.created_at || '')"></span>
                    </div>
                </div>
            </div>

            {{-- Grid de Datos --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Detalles Físicos</p>
                    <p class="text-slate-800 font-medium flex items-center gap-2 mb-2 text-sm">
                        <span class="text-slate-400">Especie:</span>
                        <span class="capitalize" x-text="mascota.especie || 'No especificada'"></span>
                    </p>
                    <p class="text-slate-800 font-medium flex items-center gap-2 mb-2 text-sm">
                        <span class="text-slate-400">Raza:</span>
                        <span class="capitalize" x-text="truncateStr(mascota.raza || '—', 25)" :title="mascota.raza"></span>
                    </p>
                    <p class="text-slate-800 font-medium flex items-center gap-2 mb-2 text-sm">
                        <span class="text-slate-400">Peso:</span>
                        <span x-text="mascota.peso ? mascota.peso + ' kg' : '—'"></span>
                    </p>
                    <p class="text-slate-800 font-medium flex items-center gap-2 text-sm">
                        <span class="text-slate-400">Color/Pelaje:</span>
                        <span x-text="truncateStr(mascota.color_pelaje || '—', 25)" :title="mascota.color_pelaje"></span>
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Salud y Propietario</p>
                    <p class="text-slate-800 font-medium flex items-start gap-2 mb-2 text-sm">
                        <span class="text-slate-400">Nacimiento:</span>
                        <span x-text="mascota.fecha_nacimiento || '—'"></span>
                    </p>
                    <p class="text-slate-800 font-medium flex items-start gap-2 mb-2 text-sm">
                        <span class="text-slate-400">Propietario:</span>
                        <template x-if="mascota.cliente">
                            <button type="button" @click="$dispatch('ver-cliente', { id: mascota.cliente.id })" 
                                    class="text-emerald-600 hover:underline cursor-pointer"
                                    x-text="truncateStr(mascota.cliente.nombre + ' ' + (mascota.cliente.apellido || ''), 25)"
                                    :title="mascota.cliente.nombre + ' ' + (mascota.cliente.apellido || '')">
                            </button>
                        </template>
                        <template x-if="!mascota.cliente">
                            <span class="text-slate-400">—</span>
                        </template>
                    </p>
                    <div class="mt-3">
                        <span class="text-slate-400 text-sm">Nota Médica:</span>
                        <p class="text-slate-700 text-sm font-medium mt-1 bg-white p-2 rounded-lg border border-slate-100"
                           x-text="truncateStr(mascota.nota_medica || 'Sin notas adicionales.', 100)"
                           :title="mascota.nota_medica">
                        </p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
function verMascotaComponent() {
    return {
        verMascotaModalOpen: false,
        mascota: {},

        async cargarMascota(id) {
            this.$dispatch('show-loader');
            try {
                const res = await fetch(`/mascotas/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    this.mascota = data.mascota;
                    this.verMascotaModalOpen = true;
                } else {
                    alert('No se pudo cargar la información de la mascota.');
                }
            } catch (e) {
                alert('Error de red.');
            } finally {
                this.$dispatch('hide-loader');
            }
        },

        getEspecieIcon(especie) {
            if(!especie) return '🐾';
            const map = {
                'perro': '🐶', 'gato': '🐱', 'conejo': '🐰',
                'aves': '🦜', 'ave': '🦜', 'reptil': '🦎', 'otro': '🐾'
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
