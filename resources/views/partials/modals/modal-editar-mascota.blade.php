{{-- ═══════════════════════════════════════════════════════════
     MODAL — EDITAR MASCOTA
     Abierto/cerrado por Alpine.js
═══════════════════════════════════════════════════════════ --}}
<div
    x-cloak
    x-show="editarMascotaModalOpen"
    x-data="editarMascotaComponent()"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    @keydown.escape.window="editarMascotaModalOpen = false"
    @editar-mascota.window="cargarMascota($event.detail.id); redirectUrl = $event.detail?.redirect || null;"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="editarMascotaModalOpen = false"></div>

    {{-- Panel --}}
    <div
        x-show="editarMascotaModalOpen"
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
        <div class="h-1.5 w-full rounded-t-2xl flex-shrink-0 bg-gradient-to-r from-amber-500 to-orange-500"></div>

        {{-- HEADER --}}
        <div class="flex items-center justify-between px-7 py-4 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-slate-800 font-bold text-xl flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </span>
                Editar Mascota
            </h2>
            <div class="flex gap-2">
                <button type="button" @click="editarMascotaModalOpen = false"
                        class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- FORMULARIO --}}
        <form @submit.prevent="guardarMascota" class="px-6 py-4 space-y-4">
            
            {{-- Info Principal --}}
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white font-bold text-3xl shadow-sm bg-gradient-to-r from-amber-500 to-orange-500">
                    <span x-text="getEspecieIcon(formData.especie)"></span>
                </div>
                <div class="flex-1">
                    <label for="nombre_mascota" class="block text-sm font-semibold text-slate-700 mb-1">
                        Nombre de la Mascota <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nombre_mascota" x-model="formData.nombre" required
                           class="w-full px-3 py-1.5 text-sm border border-slate-200 bg-slate-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            {{-- Grid de Datos --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100">
                
                <div>
                    <label for="especie_mascota" class="block text-xs font-semibold text-slate-700 mb-1">
                        Especie <span class="text-rose-500">*</span>
                    </label>
                    <select id="especie_mascota" x-model="formData.especie" required
                            class="w-full px-3 py-1.5 text-sm border border-slate-200 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">— Selecciona —</option>
                        <option value="Perro">🐶 Perro</option>
                        <option value="Gato">🐱 Gato</option>
                        <option value="Ave">🦜 Ave</option>
                        <option value="Conejo">🐰 Conejo</option>
                        <option value="Reptil">🦎 Reptil</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div>
                    <label for="raza_mascota" class="block text-xs font-semibold text-slate-700 mb-1">Raza</label>
                    <input type="text" id="raza_mascota" x-model="formData.raza"
                           class="w-full px-3 py-1.5 text-sm border border-slate-200 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
                
                <div>
                    <label for="sexo_mascota" class="block text-xs font-semibold text-slate-700 mb-1">Sexo</label>
                    <select id="sexo_mascota" x-model="formData.sexo"
                            class="w-full px-3 py-1.5 text-sm border border-slate-200 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                        <option value="">— Sexo —</option>
                        <option value="Macho">♂ Macho</option>
                        <option value="Hembra">♀ Hembra</option>
                    </select>
                </div>

                <div>
                    <label for="fecha_nacimiento_mascota" class="block text-xs font-semibold text-slate-700 mb-1">F. Nacimiento</label>
                    <input type="date" id="fecha_nacimiento_mascota" x-model="formData.fecha_nacimiento" max="{{ date('Y-m-d') }}"
                           class="w-full px-3 py-1.5 text-sm border border-slate-200 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label for="peso_mascota" class="block text-xs font-semibold text-slate-700 mb-1">Peso (kg)</label>
                    <input type="number" step="0.01" min="0" id="peso_mascota" x-model="formData.peso"
                           class="w-full px-3 py-1.5 text-sm border border-slate-200 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>

                <div>
                    <label for="color_pelaje_mascota" class="block text-xs font-semibold text-slate-700 mb-1">Color / Pelaje</label>
                    <input type="text" id="color_pelaje_mascota" x-model="formData.color_pelaje"
                           class="w-full px-3 py-1.5 text-sm border border-slate-200 bg-white rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>

            <!-- Nota Médica -->
            <div>
                <label for="nota_medica_mascota" class="block text-sm font-semibold text-slate-700 mb-1">Nota Médica / Observaciones</label>
                <textarea id="nota_medica_mascota" x-model="formData.nota_medica" rows="2"
                          class="w-full px-3 py-2 text-sm border border-slate-200 bg-slate-50 rounded-lg resize-none focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
            </div>

            <template x-if="globalError">
                <div class="p-3 bg-rose-50 text-rose-600 rounded-lg text-sm flex items-start gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span x-text="globalError"></span>
                </div>
            </template>

            <!-- Actions -->
            <div class="border-t border-slate-100 pt-4 flex gap-3 justify-end items-center">
                <button type="button" @click="editarMascotaModalOpen = false"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md"
                        :disabled="isSubmitting"
                        :class="{ 'opacity-70 cursor-not-allowed': isSubmitting }">
                    <svg x-show="isSubmitting" class="animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg x-show="!isSubmitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    <span x-text="isSubmitting ? 'Guardando...' : 'Actualizar Mascota'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editarMascotaComponent() {
    return {
        editarMascotaModalOpen: false,
        redirectUrl: null,
        isSubmitting: false,
        globalError: null,
        mascotaId: null,
        formData: {
            nombre: '',
            especie: '',
            raza: '',
            sexo: '',
            peso: '',
            fecha_nacimiento: '',
            color_pelaje: '',
            nota_medica: '',
            cliente_id: ''
        },

        async cargarMascota(id) {
            this.$dispatch('show-loader');
            this.globalError = null;
            try {
                const res = await fetch(`/mascotas/${id}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    this.mascotaId = data.mascota.id;
                    this.formData = {
                        nombre: data.mascota.nombre || '',
                        especie: data.mascota.especie ? data.mascota.especie.charAt(0).toUpperCase() + data.mascota.especie.slice(1).toLowerCase() : '',
                        raza: data.mascota.raza || '',
                        sexo: data.mascota.sexo ? data.mascota.sexo.charAt(0).toUpperCase() + data.mascota.sexo.slice(1).toLowerCase() : '',
                        peso: data.mascota.peso || '',
                        // Formatear a YYYY-MM-DD
                        fecha_nacimiento: this.formatDateForInput(data.mascota.fecha_nacimiento),
                        color_pelaje: data.mascota.color_pelaje || '',
                        nota_medica: data.mascota.nota_medica || '',
                        cliente_id: data.mascota.cliente ? data.mascota.cliente.id : ''
                    };
                    this.editarMascotaModalOpen = true;
                } else {
                    alert('No se pudo cargar la información de la mascota.');
                }
            } catch (e) {
                alert('Error de red al cargar.');
            } finally {
                this.$dispatch('hide-loader');
            }
        },

        formatDateForInput(dateStr) {
            // Backend ya devuelve formato YYYY-MM-DD compatible con <input type="date">
            return dateStr || '';
        },

        async guardarMascota() {
            if (!this.mascotaId) return;
            this.isSubmitting = true;
            this.globalError = null;
            
            try {
                const url = `/mascotas/${this.mascotaId}`;
                const payload = { ...this.formData, _method: 'PUT' };

                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch(url, {
                    method: 'POST', // POST with _method=PUT
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.editarMascotaModalOpen = false;
                    if (this.redirectUrl) {
                        window.location.href = this.redirectUrl;
                    } else {
                        // Recargar la página para reflejar cambios en tiempo real
                        window.location.reload();
                    }
                } else {
                    this.globalError = data.message || 'Error al guardar los cambios.';
                    if(data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join(' ');
                        this.globalError = errorMessages;
                    }
                }
            } catch (error) {
                this.globalError = 'Error de conexión. Intente nuevamente.';
            } finally {
                this.isSubmitting = false;
            }
        },

        getEspecieIcon(especie) {
            if(!especie) return '🐾';
            const map = {
                'perro': '🐶', 'gato': '🐱', 'conejo': '🐰',
                'aves': '🦜', 'ave': '🦜', 'reptil': '🦎', 'otro': '🐾'
            };
            return map[especie.toLowerCase()] || '🐾';
        }
    }
}
</script>
