{{-- ═══════════════════════════════════════════════════════════
     MODAL — EDITAR CITA
     Abierto con: $dispatch('editar-cita', { id: citaId })
     Carga datos de la cita via fetch JSON y permite editarla
════════════════════════════════════════════════════════════ --}}

<div
    x-cloak
    x-data="editarCitaModal()"
    x-show="open"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    @editar-cita.window="abrir($event.detail.id)"
>
    <style>
        .btn-custom-animated {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #7c3aed, #d946ef);
            border-radius: 20px;
            background-size: 100% auto;
            font-family: inherit;
            font-size: 14px; /* Ajustado para que encaje mejor con el resto de botones */
            padding: 0.6em 1.5em;
            transition: all 0.3s ease;
        }

        .btn-custom-animated:hover {
            background-position: right center;
            background-size: 200% auto;
            -webkit-animation: pulse512 1.5s infinite;
            animation: pulse512 1.5s infinite;
        }

        @keyframes pulse512 {
            0% {
                box-shadow: 0 0 0 0 rgba(124, 58, 237, 0.5);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(124, 58, 237, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(124, 58, 237, 0);
            }
        }
    </style>

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
        style="max-width:680px; max-height:92vh;"
        @click.stop
    >
        {{-- Barra decorativa top --}}
        <div class="h-1.5 w-full rounded-t-2xl flex-shrink-0"
             style="background:linear-gradient(90deg,#7c3aed,#6d28d9,#4f46e5);"></div>

        {{-- Header --}}
        <div class="flex items-center justify-between px-7 py-4 border-b border-slate-100 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-slate-800 font-bold text-lg leading-tight">Editar Cita</h3>
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
            <svg class="w-10 h-10 text-violet-600 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
            </svg>
            <p class="text-sm text-slate-400">Cargando información...</p>
        </div>

        {{-- Form --}}
        <form
            x-show="!cargando && cita"
            method="POST"
            :action="`/citas/${cita?.id}`"
            id="form-editar-cita"
            class="p-6 space-y-5"
            @submit="enviando = true"
        >
            @csrf
            @method('PUT')

            {{-- Fila 1: Cliente + Mascota --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Cliente --}}
                <div>
                    <label for="edit-cliente_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Propietario <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <select id="edit-cliente_id"
                                name="cliente_id"
                                required
                                x-model="clienteSeleccionado"
                                @change="cargarMascotas()"
                                :disabled="cargandoClientes"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                       hover:border-slate-300 appearance-none transition-all
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="" x-text="cargandoClientes ? 'Cargando clientes...' : '— Selecciona un propietario —'"></option>
                            <template x-for="c in clientes" :key="c.id">
                                <option :value="c.id"
                                        x-text="c.nombre + ' ' + (c.apellido_paterno ?? c.apellido ?? '') + (c.telefono ? ' · ' + c.telefono : '')"
                                        :selected="String(c.id) === String(clienteSeleccionado)"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Mascota --}}
                <div>
                    <label for="edit-mascota_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Mascota <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-base pointer-events-none">🐾</span>
                        <select id="edit-mascota_id"
                                name="mascota_id"
                                required
                                x-model="mascotaSeleccionada"
                                :disabled="cargandoMascotas || mascotas.length === 0"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                       hover:border-slate-300 appearance-none transition-all
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="" x-text="mascotaPlaceholder"></option>
                            <template x-for="m in mascotas" :key="m.id">
                                <option :value="m.id"
                                        x-text="m.nombre + ' (' + m.especie + (m.raza ? ' · ' + m.raza : '') + ')'"
                                        :selected="String(m.id) === String(mascotaSeleccionada)"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Fila 2: Fecha + Hora --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-fecha" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Fecha <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="date"
                               id="edit-fecha"
                               name="fecha"
                               required
                               x-model="form.fecha"
                               @change="actualizarHorario()"
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                      focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                      hover:border-slate-300 transition-all">
                    </div>
                </div>
                <div>
                    <label for="edit-hora" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Hora <span class="text-rose-500">*</span>
                        <span class="text-slate-400 font-normal" x-text="`(${minHora} – ${maxHora})`"></span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <select id="edit-hora"
                               name="hora"
                               required
                               x-model="form.hora"
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                      focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                      hover:border-slate-300 appearance-none transition-all">
                            <template x-for="opcion in opcionesHora" :key="opcion">
                                <option :value="opcion" x-text="opcion"></option>
                            </template>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Fila 3: Tipo de servicio + Estado --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-tipo_servicio" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tipo de Servicio <span class="text-rose-500">*</span>
                    </label>
                    <select id="edit-tipo_servicio"
                            name="tipo_servicio"
                            required
                            x-model="form.tipo_servicio"
                            class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                   focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                   hover:border-slate-300 appearance-none transition-all">
                        <option value="">— Selecciona servicio —</option>
                        @php
                            $configServicios = \App\Models\Configuracion::instancia()->servicios;
                            if(is_string($configServicios)) $configServicios = json_decode($configServicios, true);
                            $servicios = $configServicios ?: [['nombre' => 'Consulta General', 'precio' => 500]];
                        @endphp
                        @foreach($servicios as $s)
                            <option value="{{ $s['nombre'] }}">{{ $s['nombre'] }} - ${{ number_format($s['precio'], 2) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit-estado" class="block text-sm font-semibold text-slate-700 mb-1.5">Estado</label>
                    <select id="edit-estado"
                            name="estado"
                            x-model="form.estado"
                            class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                   focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                   hover:border-slate-300 appearance-none transition-all">
                        <option value="pendiente">⏳ Pendiente</option>
                        <option value="confirmada">✅ Confirmada</option>
                        <option value="completada">🏁 Completada</option>
                        <option value="cancelada">❌ Cancelada</option>
                    </select>
                </div>
            </div>

            {{-- Motivo --}}
            <div>
                <label for="edit-motivo" class="block text-sm font-semibold text-slate-700 mb-1.5">Motivo / Descripción</label>
                <div class="relative">
                    <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    <textarea id="edit-motivo"
                              name="motivo"
                              rows="3"
                              x-model="form.motivo"
                              placeholder="Describe el motivo de la consulta, síntomas observados..."
                              class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                     focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                     hover:border-slate-300 transition-all resize-none"></textarea>
                </div>
            </div>

            {{-- Notificaciones --}}
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Notificaciones al Propietario
                </p>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                               id="edit-enviado_email"
                               name="enviado_email"
                               value="1"
                               x-model="form.enviado_email"
                               class="w-4 h-4 rounded text-violet-600 border-slate-300 focus:ring-violet-500">
                        <span class="text-sm text-slate-700">
                            📧 Recordatorio por <strong>Email</strong>
                            <span x-show="form.enviado_email" class="text-xs text-emerald-600 font-medium ml-1">✓ Marcado</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox"
                               id="edit-enviado_whatsapp"
                               name="enviado_whatsapp"
                               value="1"
                               x-model="form.enviado_whatsapp"
                               class="w-4 h-4 rounded text-violet-600 border-slate-300 focus:ring-violet-500">
                        <span class="text-sm text-slate-700">
                            📱 Recordatorio por <strong>WhatsApp</strong>
                            <span x-show="form.enviado_whatsapp" class="text-xs text-emerald-600 font-medium ml-1">✓ Marcado</span>
                        </span>
                    </label>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row gap-3 justify-between items-center">
                {{-- Eliminar --}}
                <button type="button"
                        id="btn-eliminar-desde-modal"
                        @click="eliminar()"
                        :disabled="enviando"
                        class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50
                               text-sm font-medium px-4 py-2.5 rounded-xl transition-colors border border-rose-200
                               hover:border-rose-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Eliminar Cita
                </button>

                <div class="flex gap-3">
                    <button type="submit"
                            id="btn-actualizar-desde-modal"
                            :disabled="enviando"
                            class="btn-custom-animated inline-flex items-center gap-2
                                   shadow-sm disabled:opacity-70 disabled:cursor-not-allowed disabled:animate-none">
                        <template x-if="!enviando">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </template>
                        <template x-if="enviando">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                        </template>
                        <span x-text="enviando ? 'Guardando...' : 'Guardar Cambios'"></span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function editarCitaModal() {
    return {
        open: false,
        cargando: false,
        enviando: false,
        cargandoClientes: false,
        cargandoMascotas: false,
        cita: null,
        clientes: [],
        mascotas: [],
        clienteSeleccionado: '',
        mascotaSeleccionada: '',
        mascotaPlaceholder: '— Primero selecciona un cliente —',
        minHora: '08:00',
        maxHora: '20:00',
        opcionesHora: [],
        horariosBase: @json(
            is_string(\App\Models\Configuracion::instancia()->horarios) 
            ? json_decode(\App\Models\Configuracion::instancia()->horarios, true) 
            : (\App\Models\Configuracion::instancia()->horarios ?: [])
        ),
        form: {
            fecha: '',
            hora: '',
            tipo_servicio: '',
            estado: 'pendiente',
            motivo: '',
            enviado_email: false,
            enviado_whatsapp: false,
        },

        async abrir(id) {
            this.open = true;
            this.cargando = true;
            this.cita = null;
            this.enviando = false;
            this.resetForm();

            // Cargar clientes si no están cargados
            if (this.clientes.length === 0) {
                await this.cargarClientes();
            }

            // Cargar datos de la cita
            try {
                const r = await fetch(`/citas/${id}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await r.json();
                this.cita = data.cita;

                // Rellenar formulario
                const fecha = data.cita.fecha ? data.cita.fecha.split('T')[0] : '';
                this.form.fecha           = fecha;
                this.form.hora            = data.cita.hora ? data.cita.hora.substring(0,5) : '';
                
                this.actualizarHorario();

                this.form.tipo_servicio   = data.cita.tipo_servicio || '';
                this.form.estado          = data.cita.estado || 'pendiente';
                this.form.motivo          = data.cita.motivo || '';
                this.form.enviado_email   = !!data.cita.enviado_email;
                this.form.enviado_whatsapp = !!data.cita.enviado_whatsapp;

                // Seleccionar cliente y cargar mascotas
                this.clienteSeleccionado = String(data.cita.cliente_id || '');
                await this.cargarMascotas();

                // Seleccionar mascota después de cargar
                this.mascotaSeleccionada = String(data.cita.mascota_id || '');

            } catch(e) {
                console.error('Error al cargar cita:', e);
                this.cerrar();
            } finally {
                this.cargando = false;
            }
        },

        cerrar() {
            this.open = false;
            setTimeout(() => this.resetForm(), 300);
        },

        resetForm() {
            this.cita = null;
            this.clienteSeleccionado = '';
            this.mascotaSeleccionada = '';
            this.mascotas = [];
            this.mascotaPlaceholder = '— Primero selecciona un cliente —';
            this.minHora = '08:00';
            this.maxHora = '20:00';
            this.form = {
                fecha: '', hora: '', tipo_servicio: '',
                estado: 'pendiente', motivo: '',
                enviado_email: false, enviado_whatsapp: false
            };
            this.generarOpcionesHora();
        },

        async cargarClientes() {
            this.cargandoClientes = true;
            try {
                const r = await fetch('/api/clientes', {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.clientes = await r.json();
            } catch(e) {
                console.error('Error al cargar clientes:', e);
            } finally {
                this.cargandoClientes = false;
            }
        },

        async cargarMascotas() {
            if (!this.clienteSeleccionado) {
                this.mascotas = [];
                this.mascotaPlaceholder = '— Primero selecciona un cliente —';
                return;
            }

            this.cargandoMascotas = true;
            this.mascotaPlaceholder = 'Cargando mascotas...';
            this.mascotas = [];

            try {
                const r = await fetch(`/api/clientes/${this.clienteSeleccionado}/mascotas`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await r.json();
                this.mascotas = data;
                this.mascotaPlaceholder = data.length
                    ? '— Selecciona una mascota —'
                    : '— Sin mascotas registradas —';
            } catch(e) {
                this.mascotaPlaceholder = 'Error al cargar mascotas';
            } finally {
                this.cargandoMascotas = false;
            }
        },

        eliminar() {
            if (!this.cita) return;
            const fecha = this.form.fecha ? this.form.fecha : '';
            if (!confirm(`⚠️ ¿Eliminar esta cita? Esta acción no se puede deshacer.`)) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/citas/${this.cita.id}`;

            const csrf = document.createElement('input');
            csrf.type = 'hidden'; csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden'; method.name = '_method'; method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        },

        actualizarHorario() {
            if (!this.form.fecha) {
                this.minHora = '08:00';
                this.maxHora = '20:00';
                this.generarOpcionesHora();
                return;
            }
            const parts = this.form.fecha.split('-');
            if (parts.length !== 3) return;
            const date = new Date(parts[0], parts[1] - 1, parts[2]);
            const dayIndex = date.getDay(); // 0 = Domingo
            
            const diasSemana = ['domingo', 'lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado'];
            const nombreDiaSeleccionado = diasSemana[dayIndex];
            
            let horarioEncontrado = null;
            for (let h of this.horariosBase) {
                let nombreRango = h.dia.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                if (nombreRango.includes(nombreDiaSeleccionado) || (nombreDiaSeleccionado === 'lunes' && nombreRango.includes('lun'))) {
                    horarioEncontrado = h;
                    break;
                }
                if (nombreRango.includes('viernes') && dayIndex >= 1 && dayIndex <= 5 && nombreRango.includes('lunes')) {
                    horarioEncontrado = h;
                    break;
                }
            }
            
            if (horarioEncontrado && !horarioEncontrado.cerrado) {
                this.minHora = horarioEncontrado.apertura || '08:00';
                this.maxHora = horarioEncontrado.cierre || '20:00';
            } else {
                // Fallback si está cerrado o no se encuentra el día, mostramos algunas horas por defecto
                this.minHora = '08:00';
                this.maxHora = '20:00';
            }
            
            this.generarOpcionesHora();
        },

        generarOpcionesHora() {
            this.opcionesHora = [];
            let [minH, minM] = this.minHora.split(':').map(Number);
            let [maxH, maxM] = this.maxHora.split(':').map(Number);
            
            let current = new Date(2000, 0, 1, minH, minM, 0);
            let end = new Date(2000, 0, 1, maxH, maxM, 0);
            
            while (current <= end) {
                let h = current.getHours().toString().padStart(2, '0');
                let m = current.getMinutes().toString().padStart(2, '0');
                this.opcionesHora.push(`${h}:${m}`);
                current.setMinutes(current.getMinutes() + 30);
            }
            if (!this.opcionesHora.includes(this.form.hora)) {
                this.form.hora = this.opcionesHora.length > 0 ? this.opcionesHora[0] : '';
            }
        }
    };
}
</script>
