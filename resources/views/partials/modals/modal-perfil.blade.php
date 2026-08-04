{{-- ═══════════════════════════════════════════════════════════
     MODAL — MI PERFIL
     Abierto/cerrado por Alpine.js: profileOpen
     Controlado por ProfileController@actualizarPerfil (JSON)
     Dos secciones: Información Personal + Cambiar Contraseña
═══════════════════════════════════════════════════════════ --}}

@php
    $u = Auth::user();
    $fnBloqueada = (bool) $u->fecha_nacimiento_bloqueada;
    $fnFormato   = $u->fecha_nacimiento
                    ? \Carbon\Carbon::parse($u->fecha_nacimiento)->format('Y-m-d')
                    : '';
@endphp

{{-- Wrapper del modal, controlado por la variable Alpine `profileOpen` del layout --}}
<div
    x-cloak
    x-show="profileOpen"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="profileOpen = false"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="profileOpen = false"></div>

    {{-- Panel --}}
    <div
        x-show="profileOpen"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 translate-y-6 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-6 scale-95"
        class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-y-auto"
        style="max-height:90vh;"
        @click.stop

        {{-- ═══════════════ ALPINE DATA ═══════════════ --}}
        x-data="{
            {{-- ── Edición de nombre/email ── --}}
            editando: false,
            name:     @js($u->name),
            email:    @js($u->email),
            nameOrig: @js($u->name),
            emailOrig:@js($u->email),

            {{-- ── Campos siempre editables ── --}}
            codigoPais:       @js($u->codigo_pais ?? '+1'),
            telefono:         @js($u->telefono ?? ''),
            direccion:        @js($u->direccion ?? ''),
            fechaNacimiento:  @js($fnFormato),
            fnBloqueada:      @js($fnBloqueada),

            {{-- ── Contraseñas ── --}}
            currentPassword: '',
            password: '',
            passwordConfirmation: '',
            showCurrent: false,
            showNew: false,
            showConfirm: false,

            {{-- ── Estado del envío ── --}}
            sending:   false,
            success:   false,
            errorMsg:  '',
            fieldErrors: {},

            init() {
                this.$watch('profileOpen', (val) => {
                    if (!val) {
                        // Limpiar errores al cerrar
                        this.errorMsg = '';
                        this.fieldErrors = {};
                        // Revertir estado de edición si quedó abierto
                        if (this.editando) {
                            this.name  = this.nameOrig;
                            this.email = this.emailOrig;
                            this.editando = false;
                        }
                    }
                });
            },

            toggleEditar() {
                if (this.editando) {
                    this.name  = this.nameOrig;
                    this.email = this.emailOrig;
                }
                this.editando = !this.editando;
            },

            async enviar() {
                this.sending    = true;
                this.errorMsg   = '';
                this.fieldErrors = {};
                this.$dispatch('show-loader');

                const payload = {
                    name:             this.name,
                    email:            this.email,
                    codigo_pais:      this.codigoPais,
                    telefono:         this.telefono,
                    direccion:        this.direccion,
                    fecha_nacimiento: this.fnBloqueada ? null : this.fechaNacimiento,
                    current_password: this.currentPassword,
                    password:         this.password,
                    password_confirmation: this.passwordConfirmation
                };

                try {
                    const res = await fetch('{{ route('perfil.actualizar') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type':  'application/json',
                            'Accept':        'application/json',
                            'X-CSRF-TOKEN':  document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await res.json();

                    if (res.ok && data.success) {
                        {{-- Éxito: actualizar valores originales, mostrar mensaje y cerrar --}}
                        this.nameOrig  = this.name;
                        this.emailOrig = this.email;
                        this.editando  = false;

                        {{-- Si se desbloqueó la fecha, ahora bloqueamos también en frontend --}}
                        if (!this.fnBloqueada && this.fechaNacimiento) {
                            this.fnBloqueada = true;
                        }

                        this.success = true;
                        setTimeout(() => {
                            this.success    = false;
                            profileOpen     = false;
                            window.location.reload();
                        }, 2200);
                    } else if (res.status === 422 && data.errors) {
                        this.fieldErrors = data.errors;
                        this.errorMsg    = 'Por favor corrige los errores indicados.';
                    } else {
                        this.errorMsg = data.message || 'Ocurrió un error inesperado.';
                    }
                } catch (err) {
                    this.errorMsg = 'Error de red. Verifica tu conexión e intenta de nuevo.';
                } finally {
                    this.sending = false;
                    this.$dispatch('hide-loader');
                }
            }
        }"
    >
        {{-- ── Barra decorativa superior ── --}}
        <div class="h-1.5 w-full rounded-t-2xl" style="background:linear-gradient(90deg,#0ea5e9,#3b82f6,#059669);"></div>

        <div class="px-7 py-6">

            {{-- ── Encabezado ── --}}
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    {{-- Avatar inicial --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg"
                         style="background:linear-gradient(135deg,#0ea5e9,#3b82f6);">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold" style="color:#0c4a6e;">Mi Perfil</h2>
                        <p class="text-xs" style="color:#64748b;">{{ $u->role === 'admin' ? 'Administrador' : 'Recepcionista' }} · VetCare</p>
                    </div>
                </div>
                <button type="button" @click="profileOpen = false"
                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-colors"
                        style="color:#94a3b8;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- ════════════════════════════════
                 MENSAJE DE ÉXITO
            ════════════════════════════════ --}}
            <div x-cloak x-show="success"
                 class="mb-5 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2"
                 style="background:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                ¡Perfil actualizado correctamente! Cerrando...
            </div>

            {{-- ════════════════════════════════
                 MENSAJE DE ERROR GENERAL
            ════════════════════════════════ --}}
            <div x-cloak x-show="errorMsg"
                 class="mb-5 px-4 py-3 rounded-xl text-sm font-semibold flex items-center gap-2"
                 style="background:#fef2f2; border:1px solid #fca5a5; color:#991b1b;">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span x-text="errorMsg"></span>
            </div>

            {{-- ════════════════════════════════
                 SECCIÓN 1: INFORMACIÓN PERSONAL
            ════════════════════════════════ --}}
            <div class="mb-7">
                {{-- Cabecera de sección con botón Editar/Cancelar --}}
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-bold uppercase tracking-wider" style="color:#475569;">
                        Información Personal
                    </h3>
                    <button type="button" @click="toggleEditar()"
                            class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-all"
                            :style="editando
                                ? 'background:#fef2f2; color:#dc2626; border:1px solid #fca5a5;'
                                : 'background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;'">
                        <span x-show="!editando">✏️ Editar nombre / email</span>
                        <span x-show="editando">✕ Cancelar</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-4">

                    {{-- Nombre --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">
                            Nombre Completo
                            <span x-show="!editando" class="ml-1 text-xs font-normal" style="color:#94a3b8;">🔒</span>
                        </label>
                        <input
                            type="text"
                            x-model="name"
                            @input="name = name.replace(/[^a-zA-Z\s]/g, '')"
                            :readonly="!editando"
                            :style="editando
                                ? 'border:1.5px solid #3b82f6; background:#f8fafc; color:#1e293b;'
                                : 'border:1.5px solid #e2e8f0; background:#f3f4f6; color:#6b7280; cursor:not-allowed;'"
                            class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-all"
                            autocomplete="off"
                            onpaste="return false"
                            oncopy="return false"
                            onfocus="if(this.readOnly) return; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.15)';"
                            onblur="this.style.boxShadow='none';"
                        >
                        <template x-if="fieldErrors.name">
                            <p class="mt-1 text-xs font-semibold" style="color:#dc2626;" x-text="fieldErrors.name[0]"></p>
                        </template>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">
                            Correo Electrónico
                            <span x-show="!editando" class="ml-1 text-xs font-normal" style="color:#94a3b8;">🔒</span>
                        </label>
                        <input
                            type="text"
                            x-model="email"
                            @input="email = email.toLowerCase().replace(/[\sñáéíóúÁÉÍÓÚÑ\[\]{}<>\"':;\\]/g, '')"
                            :readonly="!editando"
                            :style="editando
                                ? 'border:1.5px solid #3b82f6; background:#f8fafc; color:#1e293b;'
                                : 'border:1.5px solid #e2e8f0; background:#f3f4f6; color:#6b7280; cursor:not-allowed;'"
                            class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-all"
                            autocomplete="off"
                            onpaste="return false"
                            oncopy="return false"
                            onfocus="if(this.readOnly) return; this.style.boxShadow='0 0 0 3px rgba(59,130,246,0.15)';"
                            onblur="this.style.boxShadow='none';"
                        >
                        <template x-if="fieldErrors.email">
                            <p class="mt-1 text-xs font-semibold" style="color:#dc2626;" x-text="fieldErrors.email[0]"></p>
                        </template>
                    </div>

                    {{-- Fecha de Nacimiento --}}
                    <div>
                        <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">
                            Fecha de Nacimiento
                            @if ($fnBloqueada)
                                <span class="ml-1 text-xs font-normal" style="color:#94a3b8;">🔒 Bloqueada permanentemente</span>
                            @else
                                <span class="ml-1 text-xs font-normal" style="color:#059669;">• Puedes ingresarla una sola vez</span>
                            @endif
                        </label>
                        <input
                            type="date"
                            x-model="fechaNacimiento"
                            {{ $fnBloqueada ? 'readonly' : '' }}
                            :max="new Date().toISOString().split('T')[0]"
                            :style="fnBloqueada
                                ? 'border:1.5px solid #e2e8f0; background:#f3f4f6; color:#6b7280; cursor:not-allowed;'
                                : 'border:1.5px solid #e2e8f0; background:#f8fafc; color:#1e293b;'"
                            class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-all"
                            onfocus="if(!this.readOnly){ this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.12)'; }"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                        >
                        <template x-if="fieldErrors.fecha_nacimiento">
                            <p class="mt-1 text-xs font-semibold" style="color:#dc2626;" x-text="fieldErrors.fecha_nacimiento[0]"></p>
                        </template>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">Teléfono</label>
                            <div class="flex">
                                <select x-model="codigoPais"
                                        class="px-3 py-2.5 rounded-l-xl text-sm outline-none transition-all border-r-0"
                                        style="border:1.5px solid #e2e8f0; background:#f8fafc; color:#1e293b; width: 110px;"
                                        onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.12)';"
                                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                                    <option value="+1">🇺🇸/🇨🇦 +1</option>
                                    <option value="+52">🇲🇽 +52</option>
                                    <option value="+57">🇨🇴 +57</option>
                                    <option value="+54">🇦🇷 +54</option>
                                    <option value="+56">🇨🇱 +56</option>
                                    <option value="+51">🇵🇪 +51</option>
                                    <option value="+58">🇻🇪 +58</option>
                                    <option value="+593">🇪🇨 +593</option>
                                    <option value="+502">🇬🇹 +502</option>
                                    <option value="+53">🇨🇺 +53</option>
                                    <option value="+591">🇧🇴 +591</option>
                                    <option value="+504">🇭🇳 +504</option>
                                    <option value="+595">🇵🇾 +595</option>
                                    <option value="+503">🇸🇻 +503</option>
                                    <option value="+505">🇳🇮 +505</option>
                                    <option value="+506">🇨🇷 +506</option>
                                    <option value="+507">🇵🇦 +507</option>
                                    <option value="+34">🇪🇸 +34</option>
                                    <option value="+44">🇬🇧 +44</option>
                                </select>
                                <input
                                    type="tel"
                                    x-model="telefono"
                                    placeholder="Ej: 999 000 0000"
                                    class="flex-1 px-4 py-2.5 rounded-r-xl text-sm outline-none transition-all"
                                    style="border:1.5px solid #e2e8f0; background:#f8fafc; color:#1e293b;"
                                    autocomplete="off"
                                    onpaste="return false"
                                    oncopy="return false"
                                    onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.12)';"
                                    onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                                >
                            </div>
                            <template x-if="fieldErrors.telefono || fieldErrors.codigo_pais">
                                <p class="mt-1 text-xs font-semibold" style="color:#dc2626;" x-text="(fieldErrors.telefono || fieldErrors.codigo_pais)[0]"></p>
                            </template>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">Dirección</label>
                            <input
                                type="text"
                                x-model="direccion"
                                placeholder="Calle, Colonia, Ciudad"
                                class="w-full px-4 py-2.5 rounded-xl text-sm outline-none transition-all"
                                style="border:1.5px solid #e2e8f0; background:#f8fafc; color:#1e293b;"
                                autocomplete="off"
                                onpaste="return false"
                                oncopy="return false"
                                onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.12)';"
                                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                            >
                            <template x-if="fieldErrors.direccion">
                                <p class="mt-1 text-xs font-semibold" style="color:#dc2626;" x-text="fieldErrors.direccion[0]"></p>
                            </template>
                        </div>
                    </div>

                </div>
            </div>


            <button
                type="button"
                @click="enviar()"
                :disabled="sending || success"
                :class="{ 'opacity-60 cursor-not-allowed': sending || success }"
                class="w-full py-3.5 rounded-xl font-semibold text-white text-sm transition-all flex items-center justify-center gap-2"
                style="background:linear-gradient(135deg,#0ea5e9,#3b82f6); box-shadow:0 4px 18px rgba(14,165,233,0.28);"
                x-on:mouseover="if(!sending && !success){ this.style.background='linear-gradient(135deg,#38bdf8,#60a5fa)'; this.style.boxShadow='0 6px 22px rgba(14,165,233,0.40)'; }"
                x-on:mouseout="if(!sending && !success){ this.style.background='linear-gradient(135deg,#0ea5e9,#3b82f6)'; this.style.boxShadow='0 4px 18px rgba(14,165,233,0.28)'; }"
            >
                <svg x-show="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span x-show="!sending && !success">Actualizar Perfil</span>
                <span x-show="sending">Guardando...</span>
                <span x-show="success && !sending">✓ ¡Guardado!</span>
            </button>

        </div>{{-- /px-7 py-6 --}}
    </div>{{-- /panel --}}
</div>{{-- /wrapper --}}
