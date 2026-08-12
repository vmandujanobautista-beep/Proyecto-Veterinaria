{{-- ═══════════════════════════════════════════════════════════
     MODAL — AGENDAR NUEVA CITA
     Abierto con: $dispatch('agendar-cita')
     Carga clientes y mascotas via AJAX (funciona en cualquier página)
     Envía form POST a citas.store
════════════════════════════════════════════════════════════ --}}

<div
    x-cloak
    x-data="agendarCitaModal()"
    x-show="open"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[80] flex items-center justify-center p-4"
    @keydown.escape.window="cerrar()"
    @agendar-cita.window="abrir()"
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
        style="max-width:680px; max-height:92vh;"
        @click.stop
    >
        {{-- Barra decorativa top --}}
        <div class="h-1.5 w-full rounded-t-2xl flex-shrink-0"
             style="background:linear-gradient(90deg,#7c3aed,#6d28d9,#4f46e5);"></div>

        {{-- Header --}}
        <div class="flex items-center justify-between px-7 py-4 border-b border-slate-100 bg-gradient-to-r from-violet-600 to-purple-700">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-white font-bold text-lg leading-tight">Agendar Nueva Cita</h3>
                    <p class="text-violet-200 text-xs">Completa los datos de la consulta</p>
                </div>
            </div>
            <button @click="cerrar()"
                    class="p-2 text-white/70 hover:text-white hover:bg-white/15 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <form method="POST" action="{{ route('citas.store') }}" id="form-agendar-cita" class="p-6 space-y-5"
              @submit="enviando = true">
            @csrf

            {{-- Fila 1: Cliente + Mascota --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Cliente (cargado via AJAX) --}}
                <div>
                    <label for="modal-cliente_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Cliente <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <select id="modal-cliente_id"
                                name="cliente_id"
                                required
                                x-model="clienteSeleccionado"
                                @change="cargarMascotas()"
                                :disabled="cargandoClientes"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                       hover:border-slate-300 appearance-none transition-all
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="" x-text="cargandoClientes ? 'Cargando clientes...' : '— Selecciona un cliente —'"></option>
                            <template x-for="c in clientes" :key="c.id">
                                <option :value="c.id"
                                        :data-email="c.email"
                                        :data-telefono="c.telefono"
                                        x-text="c.nombre + (c.telefono ? ' · ' + c.telefono : '')"></option>
                            </template>
                        </select>
                    </div>
                </div>

                {{-- Mascota --}}
                <div>
                    <label for="modal-mascota_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Mascota <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-base pointer-events-none">🐾</span>
                        <select id="modal-mascota_id"
                                name="mascota_id"
                                required
                                x-model="mascotaSeleccionada"
                                :disabled="!clienteSeleccionado || cargandoMascotas"
                                class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                       focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                       hover:border-slate-300 appearance-none transition-all
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            <option value="" x-text="mascotaPlaceholder"></option>
                            <template x-for="m in mascotas" :key="m.id">
                                <option :value="m.id"
                                        x-text="m.nombre + ' (' + m.especie + (m.raza ? ' · ' + m.raza : '') + ')'"></option>
                            </template>
                        </select>
                    </div>
                    {{-- Mensaje sin mascotas --}}
                    <p x-show="sinMascotas" x-cloak
                       class="mt-1.5 text-xs text-amber-600 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Este cliente no tiene mascotas.
                        <a href="{{ route('mascotas.create') }}" target="_blank" class="underline font-semibold">Agregar mascota</a>
                    </p>
                </div>
            </div>

            {{-- Tipo Servicio --}}
            <div>
                <label for="modal-tipo_servicio" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Tipo de Servicio <span class="text-rose-500">*</span>
                </label>
                <select id="modal-tipo_servicio"
                        name="tipo_servicio"
                        required
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                               focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                               hover:border-slate-300 appearance-none transition-all">
                    <option value="">— Selecciona un servicio —</option>
                    @php
                        $configServicios = \App\Models\Configuracion::instancia()->servicios;
                        if(is_string($configServicios)) $configServicios = json_decode($configServicios, true);
                        $servicios = $configServicios ?: [['nombre' => 'Consulta General', 'precio' => 500]];
                    @endphp
                    @foreach($servicios as $s)
                        @php
                            $servicio = $s['nombre'];
                            $precio = $s['precio'];
                            $icono = match($servicio) {
                                'Consulta General' => '🩺',
                                'Vacunación' => '💉',
                                'Desparasitación' => '🦠',
                                'Baño y Corte' => '🛁',
                                'Esterilización/Castración' => '✂️',
                                'Cirugía' => '🔬',
                                'Laboratorio' => '🧪',
                                'Rayos X / Ultrasonido' => '📡',
                                'Chequeo General' => '📋',
                                'Urgencias' => '🚨',
                                default => '🩺'
                            };
                        @endphp
                        <option value="{{ $servicio }}">{{ $icono }} {{ $servicio }} - ${{ number_format($precio, 2) }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Fecha + Hora --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="modal-fecha" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Fecha <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <input type="date"
                               id="modal-fecha"
                               name="fecha"
                               :min="hoy"
                               required
                               x-model="fecha"
                               @change="actualizarHorario()"
                               class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                      focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                      hover:border-slate-300 transition-all">
                    </div>
                </div>
                <div>
                    <label for="modal-hora" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Hora <span class="text-rose-500">*</span>
                        <span class="text-slate-400 font-normal" x-text="`(${minHora} – ${maxHora})`"></span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <select id="modal-hora"
                               name="hora"
                               required
                               x-model="hora"
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

            {{-- Motivo --}}
            <div>
                <label for="modal-motivo" class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Motivo / Descripción
                    <span class="text-slate-400 font-normal">(opcional)</span>
                </label>
                <div class="relative">
                    <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    <textarea id="modal-motivo"
                              name="motivo"
                              rows="3"
                              placeholder="Ej: Vacuna anual, vómitos desde ayer, revisión de rutina..."
                              class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl
                                     focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                     hover:border-slate-300 transition-all resize-none"></textarea>
                </div>
            </div>

            {{-- Notificaciones --}}
            <div class="bg-violet-50 rounded-xl p-4 border border-violet-100">
                <p class="text-sm font-semibold text-violet-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Notificaciones al Propietario
                </p>
                <div class="space-y-2">
                    <label class="flex items-center gap-3 cursor-pointer"
                           :class="clienteEmail ? '' : 'opacity-50 cursor-not-allowed'">
                        <input type="checkbox"
                               id="modal-enviado_email"
                               name="enviado_email"
                               value="1"
                               x-model="enviarEmail"
                               :disabled="!clienteEmail"
                               class="w-4 h-4 rounded text-violet-600 border-slate-300 focus:ring-violet-500">
                        <span class="text-sm text-slate-700">
                            📧 Enviar recordatorio por <strong>Email</strong>
                            <span x-show="clienteSeleccionado && clienteEmail"
                                  x-text="'(' + clienteEmail + ')'"
                                  class="text-slate-400 text-xs ml-1"></span>
                            <span x-show="clienteSeleccionado && !clienteEmail"
                                  class="text-amber-600 text-xs ml-1">(sin email registrado)</span>
                        </span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer"
                           :class="clienteTelefono ? '' : 'opacity-50 cursor-not-allowed'">
                        <input type="checkbox"
                               id="modal-enviado_whatsapp"
                               name="enviado_whatsapp"
                               value="1"
                               x-model="enviarWhatsapp"
                               :disabled="!clienteTelefono"
                               class="w-4 h-4 rounded text-violet-600 border-slate-300 focus:ring-violet-500">
                        <span class="text-sm text-slate-700">
                            📱 Enviar recordatorio por <strong>WhatsApp</strong>
                            <span x-show="clienteSeleccionado && clienteTelefono"
                                  x-text="'(' + clienteTelefono + ')'"
                                  class="text-slate-400 text-xs ml-1"></span>
                            <span x-show="clienteSeleccionado && !clienteTelefono"
                                  class="text-amber-600 text-xs ml-1">(sin teléfono registrado)</span>
                        </span>
                    </label>
                    <p x-show="!enviarEmail && !enviarWhatsapp && clienteSeleccionado" class="text-xs text-rose-500 mt-2 font-medium">
                        * Debes seleccionar al menos un método de notificación.
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-end pt-2 border-t border-slate-100">
                <button type="button"
                        @click="cerrar()"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl
                               hover:bg-slate-50 transition-colors w-full sm:w-auto text-center">
                    Cancelar
                </button>
                <button type="submit"
                        id="btn-submit-agendar"
                        :disabled="enviando || (!enviarEmail && !enviarWhatsapp && clienteSeleccionado)"
                        class="inline-flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700
                               active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl
                               transition-all duration-200 shadow-sm hover:shadow-md w-full sm:w-auto
                               disabled:opacity-70 disabled:cursor-not-allowed">
                    <template x-if="!enviando">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                    <template x-if="enviando">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                    </template>
                    <span x-text="enviando ? 'Agendando...' : 'Agendar Cita'"></span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function agendarCitaModal() {
    return {
        open: false,
        enviando: false,
        cargandoClientes: false,
        cargandoMascotas: false,
        clientes: [],
        mascotas: [],
        clienteSeleccionado: '',
        mascotaSeleccionada: '',
        sinMascotas: false,
        clienteEmail: '',
        clienteTelefono: '',
        enviarEmail: false,
        enviarWhatsapp: false,
        mascotaPlaceholder: '— Primero selecciona un cliente —',
        hoy: new Date().toISOString().split('T')[0],
        fecha: '',
        hora: '09:00',
        minHora: '08:00',
        maxHora: '20:00',
        opcionesHora: [],
        horariosBase: @json(
            is_string(\App\Models\Configuracion::instancia()->horarios) 
            ? json_decode(\App\Models\Configuracion::instancia()->horarios, true) 
            : (\App\Models\Configuracion::instancia()->horarios ?: [])
        ),

        abrir() {
            this.open = true;
            this.resetForm();
            this.cargarClientes();
        },

        cerrar() {
            this.open = false;
            setTimeout(() => this.resetForm(), 300);
        },

        resetForm() {
            this.enviando = false;
            this.clienteSeleccionado = '';
            this.mascotaSeleccionada = '';
            this.mascotas = [];
            this.sinMascotas = false;
            this.clienteEmail = '';
            this.clienteTelefono = '';
            this.enviarEmail = false;
            this.enviarWhatsapp = false;
            this.mascotaPlaceholder = '— Primero selecciona un cliente —';
            this.fecha = '';
            this.hora = '09:00';
            this.minHora = '08:00';
            this.maxHora = '20:00';
            this.generarOpcionesHora();

            const form = document.getElementById('form-agendar-cita');
            if (form) form.reset();
        },

        cargarClientes() {
            if (this.clientes.length > 0) return; // Ya cargados
            this.cargandoClientes = true;
            fetch('/api/clientes', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => { this.clientes = data; })
                .catch(() => { console.error('Error al cargar clientes'); })
                .finally(() => { this.cargandoClientes = false; });
        },

        cargarMascotas() {
            if (!this.clienteSeleccionado) {
                this.mascotas = [];
                this.sinMascotas = false;
                this.mascotaPlaceholder = '— Primero selecciona un cliente —';
                this.clienteEmail = '';
                this.clienteTelefono = '';
                return;
            }

            // Obtener email/teléfono del cliente seleccionado
            const cl = this.clientes.find(c => String(c.id) === String(this.clienteSeleccionado));
            this.clienteEmail    = cl?.email    || '';
            this.clienteTelefono = cl?.telefono || '';

            this.cargandoMascotas = true;
            this.mascotaPlaceholder = 'Cargando mascotas...';
            this.mascotas = [];
            this.sinMascotas = false;
            this.mascotaSeleccionada = '';

            fetch(`/api/clientes/${this.clienteSeleccionado}/mascotas`,
                  { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(data => {
                    this.mascotas = data;
                    this.sinMascotas = data.length === 0;
                    this.mascotaPlaceholder = data.length
                        ? '— Selecciona una mascota —'
                        : '— Sin mascotas registradas —';
                })
                .catch(() => { this.mascotaPlaceholder = 'Error al cargar mascotas'; })
                .finally(() => { this.cargandoMascotas = false; });
        },

        actualizarHorario() {
            if (!this.fecha) {
                this.minHora = '08:00';
                this.maxHora = '20:00';
                this.generarOpcionesHora();
                return;
            }
            const parts = this.fecha.split('-');
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
            if (!this.opcionesHora.includes(this.hora)) {
                this.hora = this.opcionesHora.length > 0 ? this.opcionesHora[0] : '';
            }
        }
    };
}
</script>
