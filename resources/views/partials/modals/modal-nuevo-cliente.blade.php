{{-- ═══════════════════════════════════════════════════════════
     MODAL — NUEVO / EDITAR CLIENTE
     Abierto/cerrado por Alpine.js: clienteModalOpen
     Envía datos vía fetch (FormData) a /clientes/modal-store
     Incluye sub-modal para agregar mascotas inline
═══════════════════════════════════════════════════════════ --}}

<div
    x-cloak
    x-show="clienteModalOpen"
    x-data="nuevoClienteComponent()"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    @keydown.escape.window="if(subModalOpen){ resetSubModal(); } else { clienteModalOpen = false; }"
    @nuevo-cliente.window="modo = 'crear'; clienteId = null; clienteModalOpen = true;"
    @editar-cliente.window="cargarCliente($event.detail.id)"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter:blur(4px);" @click="if(subModalOpen){ resetSubModal(); } else { clienteModalOpen = false; }"></div>

    {{-- Panel --}}
    <div
        x-show="clienteModalOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-8 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-8 scale-95"
        class="relative w-full bg-white rounded-2xl shadow-2xl overflow-y-auto"
        style="max-width:800px; max-height:92vh;"
        @click.stop
    >
        {{-- ── Barra decorativa top ── --}}
        <div class="h-1.5 w-full rounded-t-2xl flex-shrink-0"
             style="background:linear-gradient(90deg,#10b981,#059669,#0d9488);"></div>

        {{-- ══════════════════════════════════════════════════
             HEADER DEL MODAL
        ══════════════════════════════════════════════════ --}}
        <div class="flex items-center justify-between px-7 py-3 border-b border-slate-100 bg-gradient-to-r from-emerald-600 to-teal-600">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg leading-tight" x-text="modo === 'editar' ? 'Editar Cliente' : 'Nuevo Cliente'">Nuevo Cliente</h2>
                    <p class="text-emerald-100 text-xs" x-text="modo === 'editar' ? 'Modifica los datos del propietario' : 'Completa los datos del propietario'">Completa los datos del propietario</p>
                </div>
            </div>
            <button type="button" @click="clienteModalOpen = false"
                    class="w-9 h-9 rounded-xl flex items-center justify-center bg-white/20 hover:bg-white/30 text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════
             CONTENIDO SCROLLABLE
        ══════════════════════════════════════════════════ --}}
        <div class="px-7 pt-4 pb-6 space-y-5">

            {{-- ── Alerta de éxito ── --}}
            <div x-cloak x-show="success"
                 class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold"
                 style="background:#ecfdf5;border:1px solid #6ee7b7;color:#065f46;">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                Cliente agregado correctamente
            </div>

            {{-- ── Alerta de error general ── --}}
            <div x-cloak x-show="errorMsg"
                 class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold"
                 style="background:#fef2f2;border:1px solid #fca5a5;color:#991b1b;">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <span x-text="errorMsg"></span>
            </div>

            {{-- ════════════════════════════════
                 SECCIÓN: FOTO + DATOS PRINCIPALES
            ════════════════════════════════ --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- Nombre --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nombre <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               x-model="nombre"
                               @input="filtrarLetras('nombre'); validarNombre()"
                               @blur="validarNombre()"
                               placeholder="Ej: María"
                               autocomplete="off"
                               class="w-full px-4 py-2.5 text-sm rounded-xl transition-all outline-none"
                               :style="errNombre
                                   ? 'border:1.5px solid #f87171;background:#fef2f2;color:#1e293b;'
                                   : 'border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;'"
                               onfocus="this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                               onblur="this.style.boxShadow='none'">
                        <p x-show="errNombre" x-text="errNombre" class="mt-1 text-xs font-medium text-rose-600"></p>
                        <template x-if="fieldErrors.nombre">
                            <p class="mt-1 text-xs font-medium text-rose-600" x-text="fieldErrors.nombre[0]"></p>
                        </template>
                    </div>

                    {{-- Apellido Paterno --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Apellido Paterno <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               x-model="apellidoPaterno"
                               @input="filtrarLetras('apellidoPaterno'); validarApellidoPaterno()"
                               @blur="validarApellidoPaterno()"
                               placeholder="Ej: García"
                               autocomplete="off"
                               class="w-full px-4 py-2.5 text-sm rounded-xl transition-all outline-none"
                               :style="errApellidoPaterno
                                   ? 'border:1.5px solid #f87171;background:#fef2f2;color:#1e293b;'
                                   : 'border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;'"
                               onfocus="this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                               onblur="this.style.boxShadow='none'">
                        <p x-show="errApellidoPaterno" x-text="errApellidoPaterno" class="mt-1 text-xs font-medium text-rose-600"></p>
                        <template x-if="fieldErrors.apellido_paterno">
                            <p class="mt-1 text-xs font-medium text-rose-600" x-text="fieldErrors.apellido_paterno[0]"></p>
                        </template>
                    </div>

                    {{-- Apellido Materno --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Apellido Materno
                            <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                        </label>
                        <input type="text"
                               x-model="apellidoMaterno"
                               @input="filtrarLetras('apellidoMaterno'); validarApellidoMaterno()"
                               @blur="validarApellidoMaterno()"
                               placeholder="Ej: López"
                               autocomplete="off"
                               class="w-full px-4 py-2.5 text-sm rounded-xl transition-all outline-none"
                               :style="errApellidoMaterno
                                   ? 'border:1.5px solid #f87171;background:#fef2f2;color:#1e293b;'
                                   : 'border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;'"
                               onfocus="this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                               onblur="this.style.boxShadow='none'">
                        <p x-show="errApellidoMaterno" x-text="errApellidoMaterno" class="mt-1 text-xs font-medium text-rose-600"></p>
                    </div>
                </div>

            {{-- ════════════════════════════════
                 EMAIL
            ════════════════════════════════ --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Correo Electrónico
                    <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                </label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input type="text"
                           x-model="email"
                           @input="filtrarEmail(); validarEmail()"
                           @blur="validarEmail()"
                           placeholder="correo@ejemplo.com"
                           autocomplete="off"
                           class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl transition-all outline-none"
                           :style="errEmail
                               ? 'border:1.5px solid #f87171;background:#fef2f2;color:#1e293b;'
                               : 'border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;'"
                           onfocus="this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                           onblur="this.style.boxShadow='none'">
                </div>
                <p x-show="errEmail" x-text="errEmail" class="mt-1 text-xs font-medium text-rose-600"></p>
                <template x-if="fieldErrors.email">
                    <p class="mt-1 text-xs font-medium text-rose-600" x-text="fieldErrors.email[0]"></p>
                </template>
            </div>

            {{-- ════════════════════════════════
                 TELÉFONO CON PREFIJO DE PAÍS
            ════════════════════════════════ --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Teléfono <span class="text-rose-500">*</span>
                </label>
                <div class="flex gap-2">
                    <select x-model="codigoPais"
                            class="px-3 py-2.5 rounded-xl text-sm outline-none transition-all flex-shrink-0"
                            style="border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;width:140px;"
                            onfocus="this.style.borderColor='#10b981';this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        <option value="+52">🇲🇽 +52 México</option>
                        <option value="+1">🇺🇸 +1 USA</option>
                        <option value="+1">🇨🇦 +1 Canadá</option>
                        <option value="+57">🇨🇴 +57 Colombia</option>
                        <option value="+58">🇻🇪 +58 Venezuela</option>
                        <option value="+34">🇪🇸 +34 España</option>
                        <option value="+44">🇬🇧 +44 Reino Unido</option>
                        <option value="+54">🇦🇷 +54 Argentina</option>
                        <option value="+56">🇨🇱 +56 Chile</option>
                        <option value="+51">🇵🇪 +51 Perú</option>
                        <option value="+593">🇪🇨 +593 Ecuador</option>
                        <option value="+506">🇨🇷 +506 Costa Rica</option>
                        <option value="+507">🇵🇦 +507 Panamá</option>
                        <option value="+502">🇬🇹 +502 Guatemala</option>
                        <option value="+1809">🇩🇴 +1809 R.Dominicana</option>
                        <option value="+503">🇸🇻 +503 El Salvador</option>
                        <option value="+504">🇭🇳 +504 Honduras</option>
                        <option value="+505">🇳🇮 +505 Nicaragua</option>
                        <option value="+591">🇧🇴 +591 Bolivia</option>
                        <option value="+595">🇵🇾 +595 Paraguay</option>
                        <option value="+598">🇺🇾 +598 Uruguay</option>
                    </select>
                    <input type="tel"
                           x-model="telefono"
                           @input="telefono = telefono.replace(/[^0-9\s\-]/g,''); errTelefono=''"
                           @blur="validarTelefono()"
                           placeholder="Ej: 55 1234 5678"
                           autocomplete="off"
                           class="flex-1 px-4 py-2.5 text-sm rounded-xl transition-all outline-none"
                           :style="errTelefono
                               ? 'border:1.5px solid #f87171;background:#fef2f2;color:#1e293b;'
                               : 'border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;'"
                           onfocus="this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                           onblur="this.style.boxShadow='none'">
                </div>
                <p x-show="errTelefono" x-text="errTelefono" class="mt-1 text-xs font-medium text-rose-600"></p>
                <template x-if="fieldErrors.telefono">
                    <p class="mt-1 text-xs font-medium text-rose-600" x-text="fieldErrors.telefono[0]"></p>
                </template>
            </div>

            {{-- ════════════════════════════════
                 DIRECCIÓN Y CÓDIGO POSTAL
            ════════════════════════════════ --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Dirección
                        <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                    </label>
                    <input type="text"
                           x-model="direccion"
                           placeholder="Calle, número, colonia, ciudad..."
                           autocomplete="off"
                           class="w-full px-4 py-2.5 text-sm rounded-xl transition-all outline-none"
                           style="border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;"
                           onfocus="this.style.borderColor='#10b981';this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                           onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Código Postal
                        <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                    </label>
                    <input type="text"
                           x-model="codigoPostal"
                           @input="codigoPostal = codigoPostal.replace(/\D/g,'')"
                           placeholder="Ej: 06600"
                           maxlength="10"
                           autocomplete="off"
                           class="w-full px-4 py-2.5 text-sm rounded-xl transition-all outline-none"
                           style="border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;"
                           onfocus="this.style.borderColor='#10b981';this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                           onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                </div>
            </div>

            {{-- ════════════════════════════════
                 ESTADO: Activo / Inactivo
            ════════════════════════════════ --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Estado</label>
                <div class="flex flex-col sm:flex-row gap-4">
                    {{-- Activo --}}
                    <label class="flex items-start gap-2.5 cursor-pointer group flex-1">
                        <div class="relative mt-0.5">
                            <input type="radio" x-model="estado" value="activo" class="sr-only peer">
                            <div class="w-5 h-5 rounded-full border-2 transition-all
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-500
                                        border-slate-300 bg-white group-hover:border-emerald-400
                                        flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold transition-colors"
                                  :class="estado === 'activo' ? 'text-emerald-700' : 'text-slate-700'">
                                Activo
                            </span>
                            <span class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                                El cliente podrá agendar citas, recibir notificaciones y acceder a todos los servicios de la veterinaria.
                            </span>
                        </div>
                    </label>

                    {{-- Inactivo --}}
                    <label class="flex items-start gap-2.5 cursor-pointer group flex-1">
                        <div class="relative mt-0.5">
                            <input type="radio" x-model="estado" value="inactivo" class="sr-only peer">
                            <div class="w-5 h-5 rounded-full border-2 transition-all
                                        peer-checked:border-slate-500 peer-checked:bg-slate-500
                                        border-slate-300 bg-white group-hover:border-slate-400
                                        flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-white"></div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm font-bold transition-colors"
                                  :class="estado === 'inactivo' ? 'text-slate-700' : 'text-slate-500'">
                                Inactivo
                            </span>
                            <span class="text-xs text-slate-500 mt-0.5 leading-relaxed">
                                Guardar el historial del cliente para referencia futura. No podrá agendar citas ni recibir notificaciones.
                            </span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- ════════════════════════════════════════════════════
                 SEPARADOR — SECCIÓN MASCOTAS
            ════════════════════════════════════════════════════ --}}
            <div class="border-t border-slate-200 mt-6 pt-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🐾</span>
                        <h3 class="text-base font-bold text-slate-700">Mascotas</h3>
                        <span class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-0.5 rounded-full"
                              x-text="'Mascotas — ' + mascotas.length"></span>
                    </div>
                    <button type="button"
                            @click="abrirSubModal()"
                            class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95
                                   text-white text-xs font-semibold px-3.5 py-2 rounded-xl transition-all shadow-sm hover:shadow-md">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Mascota
                    </button>
                </div>

                {{-- ── Lista de mascotas agregadas ── --}}
                <template x-if="mascotas.length === 0">
                    <div class="flex flex-col items-center py-8 text-center rounded-xl"
                         style="background:#f8fafc;border:2px dashed #e2e8f0;">
                        <span class="text-4xl mb-2">🐕</span>
                        <p class="text-sm font-semibold text-slate-500">Sin mascotas registradas</p>
                        <p class="text-xs text-slate-400 mt-1">Haz clic en "+ Agregar Mascota" para añadir una</p>
                    </div>
                </template>

                <template x-if="mascotas.length > 0">
                    <div class="space-y-2 mb-4">
                        <template x-for="(m, i) in mascotas" :key="i">
                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 bg-slate-50 hover:bg-white transition-colors">
                                {{-- Ícono de especie --}}
                                <div class="w-10 h-10 rounded-xl flex-shrink-0 flex items-center justify-center text-lg shadow-sm"
                                     style="background:linear-gradient(135deg,#d1fae5,#a7f3d0); border: 1px solid #6ee7b7;">
                                    <span x-text="getEspecieIcon(m.especie)"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 truncate" x-text="m.nombre"></p>
                                    <p class="text-xs text-slate-500 truncate">
                                        <span class="capitalize" x-text="m.especie"></span>
                                        <template x-if="m.raza"><span x-text="' · ' + m.raza"></span></template>
                                        <template x-if="m.sexo"><span class="capitalize" x-text="' · ' + m.sexo"></span></template>
                                    </p>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    {{-- Botón Editar (lápiz) --}}
                                    <button type="button" @click="editarMascota(i)"
                                            class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                            title="Editar mascota">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </button>
                                    {{-- Botón Eliminar (X pequeña) --}}
                                    <button type="button" @click="mascotas.splice(i, 1); if(editIndex === i){ resetSubModal(); }"
                                            class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Quitar mascota">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

        </div>{{-- /px-7 py-6 --}}

        {{-- ════════════════════════════════════════════════════════
             FOOTER — BOTONES CANCELAR / GUARDAR CLIENTE
        ════════════════════════════════════════════════════════ --}}
        <div class="sticky bottom-0 flex gap-3 justify-end px-7 py-4 border-t border-slate-100 bg-white rounded-b-2xl">
            <button type="button"
                    @click="clienteModalOpen = false"
                    class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Cancelar
            </button>

            {{-- Botón Guardar con loader de perro corriendo --}}
            <button type="button"
                    id="btn-guardar-cliente-modal"
                    @click="guardarCliente()"
                    :disabled="sending || success"
                    :class="{'opacity-60 cursor-not-allowed': sending || success}"
                    class="inline-flex items-center gap-2.5 px-6 py-2.5 text-sm font-semibold text-white rounded-xl transition-all"
                    style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 14px rgba(16,185,129,0.35);"
                    x-on:mouseover="if(!sending && !success){this.style.background='linear-gradient(135deg,#34d399,#10b981)';}"
                    x-on:mouseout="if(!sending && !success){this.style.background='linear-gradient(135deg,#10b981,#059669)';}">

                {{-- Estado normal --}}
                <span x-show="!sending && !success" class="flex items-center gap-2 text-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="modo === 'editar' ? 'Actualizar Cliente' : 'Agregar Cliente'"></span>
                </span>

                {{-- Loader — perro corriendo (SVG animado) --}}
                <template x-if="sending && !success">
                    <span class="flex items-center gap-2.5">
                        <svg class="w-5 h-5" viewBox="0 0 120 40" xmlns="http://www.w3.org/2000/svg">
                            <style>
                                @keyframes dogRun {
                                    0%   { transform: translateX(0px);  }
                                    50%  { transform: translateX(8px);  }
                                    100% { transform: translateX(0px);  }
                                }
                                @keyframes legSwing {
                                    0%, 100% { transform: rotate(0deg);  }
                                    25%       { transform: rotate(30deg); }
                                    75%       { transform: rotate(-20deg);}
                                }
                                @keyframes tailWag {
                                    0%, 100% { transform: rotate(-10deg); }
                                    50%       { transform: rotate(20deg);  }
                                }
                                .dog-body { animation: dogRun 0.4s ease-in-out infinite; transform-origin: center; }
                                .leg-front { animation: legSwing 0.4s ease-in-out infinite; transform-origin: top; }
                                .leg-back  { animation: legSwing 0.4s ease-in-out infinite reverse; transform-origin: top; }
                                .dog-tail  { animation: tailWag 0.3s ease-in-out infinite; transform-origin: bottom left; }
                            </style>
                            <g class="dog-body" transform="translate(55,18)">
                                {{-- Cuerpo --}}
                                <ellipse cx="0" cy="0" rx="16" ry="9" fill="white" opacity="0.9"/>
                                {{-- Cabeza --}}
                                <circle cx="17" cy="-4" r="8" fill="white" opacity="0.9"/>
                                {{-- Orejas --}}
                                <ellipse cx="20" cy="-10" rx="4" ry="3" fill="white" opacity="0.7" transform="rotate(20,20,-10)"/>
                                {{-- Ojos --}}
                                <circle cx="20" cy="-5" r="1.5" fill="#065f46"/>
                                {{-- Hocico --}}
                                <ellipse cx="24" cy="-3" rx="3" ry="2" fill="white" opacity="0.7"/>
                                {{-- Cola --}}
                                <path class="dog-tail" d="M-16,0 Q-26,-10 -20,-18" stroke="white" stroke-width="3" fill="none" stroke-linecap="round"/>
                                {{-- Patas --}}
                                <rect class="leg-front" x="5"  y="7" width="4" height="10" rx="2" fill="white" opacity="0.85"/>
                                <rect class="leg-front" x="11" y="7" width="4" height="10" rx="2" fill="white" opacity="0.85"/>
                                <rect class="leg-back"  x="-13" y="7" width="4" height="10" rx="2" fill="white" opacity="0.85"/>
                                <rect class="leg-back"  x="-7"  y="7" width="4" height="10" rx="2" fill="white" opacity="0.85"/>
                            </g>
                        </svg>
                        Guardando...
                    </span>
                </template>

                {{-- Éxito --}}
                <template x-if="success">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        ¡Guardado! 🎉
                    </span>
                </template>

            </button>
        </div>

    </div>{{-- /panel --}}

    {{-- ══════════════════════════════════════════════════════════
         SUB-MODAL FLOTANTE / VENTANA FLOTANTE SUPERPUESTA: NUEVA MASCOTA
    ══════════════════════════════════════════════════════════ --}}
    <div x-show="subModalOpen" x-cloak
         class="fixed inset-0 z-[70] flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Backdrop oscuro para la ventana flotante de Mascota --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="resetSubModal()"></div>

        {{-- Ventana flotante de Mascota --}}
        <div class="relative w-full max-w-lg bg-white rounded-3xl shadow-2xl border border-emerald-200 overflow-hidden z-10"
             style="background:linear-gradient(180deg,#f0fdf4,#ffffff);"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             @click.stop>

            {{-- Cabecera del sub-panel --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-emerald-100"
                 style="background:linear-gradient(90deg,#ecfdf5,#d1fae5);">
                <div class="flex items-center gap-2.5">
                    <span class="text-xl">🐾</span>
                    <span class="text-base font-bold text-emerald-900" x-text="editIndex !== null ? 'Editar Mascota' : 'Nueva Mascota'"></span>
                </div>
                <button type="button" @click="resetSubModal()"
                        class="w-7 h-7 rounded-xl flex items-center justify-center text-emerald-600 hover:bg-emerald-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                {{-- Nombre de mascota --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nombre <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           x-model="mNombre"
                           @input="mNombre = mNombre.replace(/[^a-zA-Z\u00C0-\u024F\u1E00-\u1EFF\s]/g,''); mErrNombre=''"
                           @blur="if(!mNombre.trim()){mErrNombre='El nombre es obligatorio.'}"
                           placeholder="Ej: Firulais"
                           autocomplete="off"
                           class="w-full px-4 py-2.5 text-sm rounded-xl outline-none transition-all"
                           :style="mErrNombre ? 'border:1.5px solid #f87171;background:#fef2f2;' : 'border:1.5px solid #e2e8f0;background:#f8fafc;'"
                           onfocus="this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                           onblur="this.style.boxShadow='none'">
                    <p x-show="mErrNombre" x-text="mErrNombre" class="mt-1 text-xs text-rose-600 font-medium"></p>
                </div>

                {{-- Especie y Sexo --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Especie <span class="text-rose-500">*</span>
                        </label>
                        <select x-model="mEspecie"
                                @change="mErrEspecie=''"
                                class="w-full px-4 py-2.5 text-sm rounded-xl outline-none transition-all"
                                :style="mErrEspecie ? 'border:1.5px solid #f87171;background:#fef2f2;color:#1e293b;' : 'border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;'"
                                onfocus="this.style.borderColor='#10b981'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="perro">🐶 Perro</option>
                            <option value="gato">🐱 Gato</option>
                            <option value="conejo">🐰 Conejo</option>
                            <option value="aves">🐦 Aves</option>
                            <option value="reptil">🦎 Reptil</option>
                            <option value="otro">🐾 Otro</option>
                        </select>
                        <p x-show="mErrEspecie" x-text="mErrEspecie" class="mt-1 text-xs text-rose-600 font-medium"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Sexo <span class="text-rose-500">*</span>
                        </label>
                        <select x-model="mSexo"
                                @change="mErrSexo=''"
                                class="w-full px-4 py-2.5 text-sm rounded-xl outline-none transition-all"
                                :style="mErrSexo ? 'border:1.5px solid #f87171;background:#fef2f2;color:#1e293b;' : 'border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;'"
                                onfocus="this.style.borderColor='#10b981'"
                                onblur="this.style.borderColor='#e2e8f0'">
                            <option value="">Seleccionar sexo...</option>
                            <option value="macho">♂ Macho</option>
                            <option value="hembra">♀ Hembra</option>
                        </select>
                        <p x-show="mErrSexo" x-text="mErrSexo" class="mt-1 text-xs text-rose-600 font-medium"></p>
                    </div>
                </div>

                {{-- Raza y Color/Pelaje --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Raza <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                        </label>
                        <input type="text" x-model="mRaza" placeholder="Ej: Labrador" autocomplete="off"
                               class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                               style="border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;"
                               onfocus="this.style.borderColor='#10b981'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Color / Pelaje <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                        </label>
                        <input type="text" x-model="mColorPelaje" placeholder="Ej: Blanco con manchas negras" autocomplete="off"
                               class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                               style="border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;"
                               onfocus="this.style.borderColor='#10b981'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                {{-- Peso y Fecha de nacimiento --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Peso (kg) <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                        </label>
                        <div class="relative">
                            <input type="number" x-model="mPeso" min="0" max="999" step="0.1"
                                   placeholder="Ej: 12.50" autocomplete="off"
                                   class="w-full pl-4 pr-10 py-2.5 text-sm rounded-xl outline-none"
                                   style="border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;"
                                   onfocus="this.style.borderColor='#10b981'"
                                   onblur="this.style.borderColor='#e2e8f0'">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">kg</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Fecha de Nacimiento <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                        </label>
                        <input type="date" x-model="mFechaNacimiento"
                               :max="new Date().toISOString().split('T')[0]"
                               class="w-full px-4 py-2.5 text-sm rounded-xl outline-none"
                               style="border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;"
                               onfocus="this.style.borderColor='#10b981'"
                               onblur="this.style.borderColor='#e2e8f0'">
                    </div>
                </div>

                {{-- Nota médica --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nota Médica <span class="ml-1 text-xs font-normal text-slate-400">(opcional)</span>
                    </label>
                    <textarea x-model="mNotaMedica" rows="2"
                              placeholder="Alergias, condiciones especiales, medicamentos..."
                              class="w-full px-4 py-2.5 text-sm rounded-xl outline-none resize-none"
                              style="border:1.5px solid #e2e8f0;background:#f8fafc;color:#1e293b;"
                              onfocus="this.style.borderColor='#10b981'"
                              onblur="this.style.borderColor='#e2e8f0'"></textarea>
                </div>

                {{-- Botón guardar mascota --}}
                <button type="button"
                        @click="guardarMascota()"
                        class="w-full py-3 rounded-xl font-semibold text-white text-sm transition-all flex items-center justify-center gap-2"
                        style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 14px rgba(16,185,129,0.30);"
                        x-on:mouseover="this.style.background='linear-gradient(135deg,#34d399,#10b981)'"
                        x-on:mouseout="this.style.background='linear-gradient(135deg,#10b981,#059669)'">
                    <span>🐾 <span x-text="editIndex !== null ? 'Actualizar Mascota' : 'Agregar Mascota'"></span></span>
                </button>
            </div>
        </div>
    </div>
</div>{{-- /wrapper --}}

<script>
function nuevoClienteComponent() {
    return {
            /* ── Estado general ── */
            modo: 'crear',
            clienteId: null,
            sending: false,
            success: false,
            errorMsg: '',
            fieldErrors: {},

            /* ── Campos del cliente ── */
            nombre: '',
            apellidoPaterno: '',
            apellidoMaterno: '',
            email: '',
            codigoPais: '+52',
            telefono: '',
            direccion: '',
            codigoPostal: '',
            estado: 'activo',

            /* ── Errores inline por campo ── */
            errNombre: '',
            errApellidoPaterno: '',
            errApellidoMaterno: '',
            errEmail: '',
            errTelefono: '',

            /* ── Mascotas añadidas durante esta sesión ── */
            mascotas: [],
            subModalOpen: false,
            editIndex: null,

            /* ── Campos del sub-modal mascota ── */
            mNombre: '',
            mEspecie: 'perro',
            mRaza: '',
            mSexo: '',
            mPeso: '',
            mFechaNacimiento: '',
            mColorPelaje: '',
            mNotaMedica: '',

            mErrNombre: '',
            mErrEspecie: '',
            mErrSexo: '',

            /* ── Resetear todo al abrir ── */
            init() {
                this.$watch('clienteModalOpen', (val) => {
                    if (val && this.modo === 'crear') { this.resetForm(); }
                });
            },

            resetForm() {
                this.nombre = ''; this.apellidoPaterno = ''; this.apellidoMaterno = '';
                this.email = ''; this.codigoPais = '+52'; this.telefono = '';
                this.direccion = ''; this.codigoPostal = ''; this.estado = 'activo';
                this.mascotas = [];
                this.errNombre = ''; this.errApellidoPaterno = ''; this.errApellidoMaterno = '';
                this.errEmail = ''; this.errTelefono = '';
                this.sending = false; this.success = false; this.errorMsg = ''; this.fieldErrors = {};
                this.resetSubModal(false);
            },

            async cargarCliente(id) {
                this.modo = 'editar';
                this.clienteId = id;
                this.resetForm();
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
                        const c = data.cliente;
                        this.nombre = c.nombre || '';
                        this.apellidoPaterno = c.apellido_paterno || '';
                        this.apellidoMaterno = c.apellido_materno || '';
                        this.email = c.email || '';
                        this.codigoPais = c.codigo_pais || '+52';
                        this.telefono = c.telefono || '';
                        this.direccion = c.direccion || '';
                        this.codigoPostal = c.codigo_postal || '';
                        this.estado = c.estado || 'activo';
                        this.mascotas = c.mascotas || [];
                        
                        this.clienteModalOpen = true;
                    } else {
                        alert('No se pudo cargar la información del cliente.');
                    }
                } catch (e) {
                    alert('Error de red al cargar el cliente.');
                } finally {
                    this.$dispatch('hide-loader');
                }
            },

            abrirSubModal() {
                this.resetSubModal(false);
                this.subModalOpen = true;
            },

            resetSubModal(closeModal = true) {
                this.mNombre = ''; this.mEspecie = 'perro'; this.mRaza = ''; this.mSexo = '';
                this.mPeso = ''; this.mFechaNacimiento = ''; this.mColorPelaje = ''; this.mNotaMedica = '';
                this.mErrNombre = ''; this.mErrEspecie = ''; this.mErrSexo = '';
                this.editIndex = null;
                if (closeModal) {
                    this.subModalOpen = false;
                }
            },

            getEspecieIcon(especie) {
                const map = {
                    'perro': '🐕',
                    'gato': '🐱',
                    'conejo': '🐰',
                    'aves': '🐦',
                    'ave': '🐦',
                    'reptil': '🦎',
                    'otro': '🐾'
                };
                return map[especie] || '🐾';
            },

            /* ── Validaciones inline de texto (solo letras y espacios) ── */
            soloLetras(val) { return /^[\u00C0-\u024F\u1E00-\u1EFFa-zA-Z\s]+$/.test(val.trim()); },

            validarNombre() {
                if (!this.nombre.trim()) { this.errNombre = 'El nombre es obligatorio.'; return false; }
                if (!this.soloLetras(this.nombre)) { this.errNombre = 'Solo se permiten letras y espacios (sin números ni caracteres especiales).'; return false; }
                this.errNombre = ''; return true;
            },
            validarApellidoPaterno() {
                if (!this.apellidoPaterno.trim()) { this.errApellidoPaterno = 'El apellido paterno es obligatorio.'; return false; }
                if (!this.soloLetras(this.apellidoPaterno)) { this.errApellidoPaterno = 'Solo se permiten letras y espacios.'; return false; }
                this.errApellidoPaterno = ''; return true;
            },
            validarApellidoMaterno() {
                if (this.apellidoMaterno.trim() && !this.soloLetras(this.apellidoMaterno)) {
                    this.errApellidoMaterno = 'Solo se permiten letras y espacios.'; return false;
                }
                this.errApellidoMaterno = ''; return true;
            },
            validarTelefono() {
                const val = this.telefono.trim();
                if (!val) { this.errTelefono = 'El teléfono es obligatorio.'; return false; }
                if (!/^[0-9\s\-]+$/.test(val) || !/\d/.test(val)) {
                    this.errTelefono = 'Ingresa un número de teléfono válido.'; return false;
                }
                this.errTelefono = ''; return true;
            },

            filtrarLetras(campo) {
                this[campo] = this[campo].replace(/[^a-zA-Z\u00C0-\u024F\u1E00-\u1EFF\s]/g, '');
            },

            /* ── Validación email estricta ── */
            validarEmail() {
                const val = this.email.trim();
                if (!val) { this.errEmail = ''; return true; } // opcional
                if (/\s/.test(val)) { this.errEmail = 'El correo no puede tener espacios.'; return false; }
                if (val !== val.toLowerCase()) { this.errEmail = 'El correo debe estar en minúsculas.'; return false; }
                if (/[ñáéíóúÁÉÍÓÚÑ]/u.test(val)) { this.errEmail = 'No se permiten eñes ni acentos.'; return false; }
                if (/[()[\]{}<>\"':;\\]/.test(val)) { this.errEmail = 'Contiene caracteres especiales no permitidos.'; return false; }
                if ((val.match(/@/g) || []).length !== 1) { this.errEmail = 'Debe contener exactamente un símbolo @.'; return false; }
                const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
                if (!emailRe.test(val)) { this.errEmail = 'El formato del correo o dominio no es válido.'; return false; }
                this.errEmail = ''; return true;
            },
            filtrarEmail() {
                this.email = this.email.toLowerCase().replace(/[ñáéíóúÁÉÍÓÚÑ\[\]{}<>\"':;\\]/g, '');
            },

            /* ── GUARDAR MASCOTA en memoria ── */
            guardarMascota() {
                let ok = true;
                if (!this.mNombre.trim()) {
                    this.mErrNombre = 'El nombre es obligatorio.';
                    ok = false;
                } else if (!this.soloLetras(this.mNombre)) {
                    this.mErrNombre = 'Solo se permiten letras y espacios (sin números ni caracteres especiales).';
                    ok = false;
                } else {
                    this.mErrNombre = '';
                }

                if (!this.mEspecie) {
                    this.mErrEspecie = 'Selecciona una especie.';
                    ok = false;
                } else {
                    this.mErrEspecie = '';
                }

                if (!this.mSexo) {
                    this.mErrSexo = 'Selecciona el sexo.';
                    ok = false;
                } else {
                    this.mErrSexo = '';
                }

                if (!ok) return;

                const mascotaObj = {
                    nombre: this.mNombre.trim(),
                    especie: this.mEspecie,
                    raza: this.mRaza.trim(),
                    sexo: this.mSexo,
                    peso: this.mPeso ? parseFloat(this.mPeso) : '',
                    fecha_nacimiento: this.mFechaNacimiento || '',
                    color_pelaje: this.mColorPelaje.trim(),
                    nota_medica: this.mNotaMedica.trim()
                };

                if (this.editIndex !== null && this.editIndex !== undefined) {
                    this.mascotas[this.editIndex] = mascotaObj;
                } else {
                    this.mascotas.push(mascotaObj);
                }

                this.resetSubModal(true);
            },

            editarMascota(index) {
                const m = this.mascotas[index];
                if (!m) return;
                this.mNombre = m.nombre || '';
                this.mEspecie = m.especie || 'perro';
                this.mRaza = m.raza || '';
                this.mSexo = m.sexo || '';
                this.mPeso = m.peso || '';
                this.mFechaNacimiento = m.fecha_nacimiento || '';
                this.mColorPelaje = m.color_pelaje || '';
                this.mNotaMedica = m.nota_medica || '';
                this.editIndex = index;
                this.subModalOpen = true;
            },

            /* ── GUARDAR TODO (CLIENTE + MASCOTAS) EN UN SOLO PASO ── */
            async guardarCliente() {
                const v1 = this.validarNombre();
                const v2 = this.validarApellidoPaterno();
                const v3 = this.validarApellidoMaterno();
                const v4 = this.validarEmail();
                const v5 = this.validarTelefono();
                if (!v1 || !v2 || !v3 || !v4 || !v5) {
                    this.errorMsg = 'Por favor corrige los errores indicados.';
                    return;
                }

                this.sending = true;
                this.errorMsg = '';
                this.fieldErrors = {};
                this.$dispatch('show-loader');

                const fd = new FormData();
                fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
                if (this.modo === 'editar') {
                    fd.append('_method', 'PUT');
                }
                fd.append('nombre', this.nombre.trim());
                fd.append('apellido_paterno', this.apellidoPaterno.trim());
                fd.append('apellido_materno', this.apellidoMaterno.trim());
                fd.append('email', this.email.trim());
                fd.append('codigo_pais', this.codigoPais);
                fd.append('telefono', this.telefono.trim());
                fd.append('direccion', this.direccion.trim());
                fd.append('codigo_postal', this.codigoPostal.trim());
                fd.append('estado', this.estado);
                fd.append('mascotas', JSON.stringify(this.mascotas));

                const url = this.modo === 'editar' ? `/clientes/${this.clienteId}` : '{{ route('clientes.store') }}';

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: fd,
                    });
                    const data = await res.json();

                    if (res.ok && data.success) {
                        this.success = true;
                        setTimeout(() => {
                            this.success = false;
                            clienteModalOpen = false;
                            window.location.reload();
                        }, 1500);
                    } else if (res.status === 422 && data.errors) {
                        this.fieldErrors = data.errors;
                        this.errorMsg = 'Por favor corrige los errores indicados.';
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
    };
}
</script>
