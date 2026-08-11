<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Configuración de la Clínica</h2>
            <p class="text-sm text-slate-500 mt-0.5">Datos generales, horarios, servicios y mensajes de confirmación</p>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('admin.configuracion.update') }}" enctype="multipart/form-data"
          x-data="{ tab: 'clinica' }">
        @csrf

        {{-- Tabs de navegación --}}
        <div class="flex gap-1 mb-5 bg-white rounded-2xl p-1.5 shadow-sm border border-slate-100 w-fit">
            @foreach([
                ['clinica',  'Clínica',    'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['horarios', 'Horarios',   'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['servicios','Servicios',  'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['mensajes', 'Mensajes',   'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
            ] as [$key, $label, $icon])
                <button type="button" @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                        class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-medium transition-all duration-150">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                    </svg>
                    {{ $label }}
                </button>
            @endforeach
        </div>

        {{-- Panel: Datos de la Clínica --}}
        <div x-show="tab === 'clinica'" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-5">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3">Datos de la Clínica</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2" x-data="{
                    nombre: '{{ old('clinica_nombre', $config->clinica_nombre) }}',
                    errorNombre: '',
                    validateNombre() {
                        if (!this.nombre) { this.errorNombre = 'El nombre de la clínica es obligatorio.'; }
                        else if (this.nombre.length > 10) { this.errorNombre = 'El nombre no puede tener más de 10 caracteres.'; }
                        else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(this.nombre)) { this.errorNombre = 'El nombre solo puede contener letras y espacios.'; }
                        else { this.errorNombre = ''; }
                    }
                }">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Nombre de la clínica *</label>
                    <input type="text" name="clinica_nombre" x-model="nombre" @input="validateNombre()"
                           required maxlength="10" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="Solo letras y espacios, máximo 10 caracteres"
                           class="w-full px-3 py-2.5 text-sm border rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50"
                           :class="errorNombre ? 'border-red-500' : '{{ $errors->has('clinica_nombre') ? 'border-red-500' : 'border-slate-200' }}'">
                    <p class="text-xs text-red-500 mt-1" x-show="errorNombre" x-text="errorNombre" style="display: none;"></p>
                    @error('clinica_nombre') <p class="text-xs text-red-500 mt-1" x-show="!errorNombre">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Teléfono de contacto</label>
                    <div x-data="{ 
                        telefonoCompleto: '{{ old('clinica_telefono', $config->clinica_telefono) }}',
                        codigo: '+52',
                        numero: '',
                        errorTelefono: '',
                        init() {
                            if (this.telefonoCompleto) {
                                let match = this.telefonoCompleto.match(/^(\+\d{1,3})\s*(.*)$/);
                                if (match) {
                                    this.codigo = match[1];
                                    this.numero = match[2];
                                } else {
                                    this.numero = this.telefonoCompleto.replace('+', '');
                                }
                            }
                        },
                        validateTelefono() {
                            if (this.numero && this.numero.length > 15) {
                                this.errorTelefono = 'El teléfono no puede tener más de 15 caracteres en total.';
                            } else {
                                this.errorTelefono = '';
                            }
                        }
                    }">
                        <input type="hidden" name="clinica_telefono" :value="numero ? (codigo + numero.replace(/\s/g, '')) : ''">
                        <div class="flex shadow-sm rounded-xl">
                            <select x-model="codigo" class="pr-8 pl-3 py-2.5 text-sm border border-r-0 rounded-l-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 text-slate-700 min-w-[110px] appearance-none" style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 0.7rem top 50%; background-size: 0.65rem auto;" :class="errorTelefono ? 'border-red-500' : '{{ $errors->has('clinica_telefono') ? 'border-red-500' : 'border-slate-200' }}'">
                                <option value="+52">🇲🇽 +52</option>
                                <option value="+1">🇺🇸 +1</option>
                                <option value="+34">🇪🇸 +34</option>
                                <option value="+54">🇦🇷 +54</option>
                                <option value="+56">🇨🇱 +56</option>
                                <option value="+57">🇨🇴 +57</option>
                                <option value="+51">🇵🇪 +51</option>
                            </select>
                            <input type="text" x-model="numero" @input="numero = numero.replace(/\D/g, ''); validateTelefono()" placeholder="Ej. 5512345678" maxlength="15"
                                   class="w-full flex-1 px-3 py-2.5 text-sm border rounded-r-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50"
                                   :class="errorTelefono ? 'border-red-500' : '{{ $errors->has('clinica_telefono') ? 'border-red-500' : 'border-slate-200' }}'">
                        </div>
                        <p class="text-xs text-red-500 mt-1" x-show="errorTelefono" x-text="errorTelefono" style="display: none;"></p>
                        @error('clinica_telefono') <p class="text-xs text-red-500 mt-1" x-show="!errorTelefono">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div x-data="{
                    email: '{{ old('clinica_email', $config->clinica_email) }}',
                    errorEmail: '',
                    validateEmail() {
                        if (this.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email)) {
                            this.errorEmail = 'El correo debe ser una dirección válida.';
                        } else {
                            this.errorEmail = '';
                        }
                    }
                }">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico</label>
                    <input type="email" name="clinica_email" x-model="email" @input="validateEmail()"
                           class="w-full px-3 py-2.5 text-sm border rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50"
                           :class="errorEmail ? 'border-red-500' : '{{ $errors->has('clinica_email') ? 'border-red-500' : 'border-slate-200' }}'"
                           placeholder="contacto@vetcare.com">
                    <p class="text-xs text-red-500 mt-1" x-show="errorEmail" x-text="errorEmail" style="display: none;"></p>
                    @error('clinica_email') <p class="text-xs text-red-500 mt-1" x-show="!errorEmail">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Dirección</label>
                    <input type="text" name="clinica_direccion" value="{{ old('clinica_direccion', $config->clinica_direccion) }}"
                           class="w-full px-3 py-2.5 text-sm border {{ $errors->has('clinica_direccion') ? 'border-red-500' : 'border-slate-200' }} rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50"
                           placeholder="Calle, número, colonia, ciudad">
                    @error('clinica_direccion') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-2">Logo de la clínica</label>
                    <div class="flex items-center gap-4">
                        @if($config->clinica_logo)
                            <img src="{{ Storage::url($config->clinica_logo) }}" alt="Logo" class="w-16 h-16 rounded-xl object-cover border border-slate-200">
                        @else
                            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-blue-500 to-sky-600 flex items-center justify-center text-white font-bold text-xl">V</div>
                        @endif
                        <div>
                            <input type="file" name="clinica_logo" accept="image/*" class="text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-slate-400 mt-1">PNG, JPG, SVG, WebP — máx. 2 MB</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel: Horarios --}}
        <div x-show="tab === 'horarios'" x-data="horarioManager()" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Horarios de Atención</h3>
            <input type="hidden" name="horarios" :value="JSON.stringify(horarios)">
            <div class="space-y-3">
                <template x-for="(horario, i) in horarios" :key="i">
                    <div class="flex flex-wrap items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-100">
                        <input type="text" x-model="horario.dia" placeholder="Ej. Lunes - Viernes"
                               class="flex-1 min-w-[150px] px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" x-model="horario.cerrado" class="rounded">
                            Cerrado
                        </label>
                        <template x-if="!horario.cerrado">
                            <div class="flex items-center gap-2">
                                <input type="time" x-model="horario.apertura"
                                       class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                <span class="text-slate-400 text-sm">a</span>
                                <input type="time" x-model="horario.cierre"
                                       class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            </div>
                        </template>
                        <button type="button" @click="horarios.splice(i, 1)"
                                class="p-1.5 text-slate-400 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="horarios.push({dia:'',apertura:'08:00',cierre:'18:00',cerrado:false})"
                        class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Agregar horario
                </button>
            </div>
        </div>

        {{-- Panel: Servicios y precios --}}
        <div x-show="tab === 'servicios'" x-data="servicioManager()" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3 mb-5">Servicios y Precios</h3>
            <input type="hidden" name="servicios" :value="JSON.stringify(servicios)">
            <div class="space-y-3 mb-4">
                <div class="grid grid-cols-3 gap-3 text-xs text-slate-500 font-semibold uppercase tracking-wider px-1">
                    <span>Nombre del servicio</span>
                    <span>Precio (MXN)</span>
                    <span></span>
                </div>
                <template x-for="(servicio, i) in servicios" :key="i">
                    <div class="grid grid-cols-3 gap-3 items-center">
                        <input type="text" x-model="servicio.nombre" placeholder="Ej. Consulta General"
                               class="px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">$</span>
                            <input type="number" x-model="servicio.precio" min="0" step="0.01" placeholder="0.00"
                                   class="w-full pl-7 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                        </div>
                        <button type="button" @click="servicios.splice(i, 1)"
                                class="p-1.5 text-slate-400 hover:text-rose-500 transition-colors justify-self-start">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </template>
                <button type="button" @click="servicios.push({nombre:'',precio:0})"
                        class="flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Agregar servicio
                </button>
            </div>

            <div class="border-t border-slate-100 pt-5">
                <label class="block text-sm font-semibold text-slate-700 mb-3">Métodos de pago aceptados</label>
                <div class="flex flex-wrap gap-3">
                    @foreach(['Efectivo','Tarjeta de crédito','Tarjeta de débito','Transferencia bancaria','OXXO Pay'] as $metodo)
                        <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                            <input type="checkbox" name="metodos_pago[]" value="{{ $metodo }}"
                                   {{ is_array($config->metodos_pago) && in_array($metodo, $config->metodos_pago) ? 'checked' : '' }}
                                   class="rounded text-blue-600">
                            {{ $metodo }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Panel: Mensajes --}}
        <div x-show="tab === 'mensajes'" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-5">
            <h3 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-3">Mensajes de Confirmación</h3>
            <p class="text-sm text-slate-500 bg-blue-50 border border-blue-100 rounded-xl p-3">
                Usa las variables: <code class="bg-white px-1 rounded">{nombre}</code>, <code class="bg-white px-1 rounded">{mascota}</code>, <code class="bg-white px-1 rounded">{fecha}</code>, <code class="bg-white px-1 rounded">{hora}</code>, <code class="bg-white px-1 rounded">{telefono}</code>
            </p>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mensaje de correo electrónico</label>
                <textarea name="mensaje_confirmacion" rows="4" maxlength="1000"
                          class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 resize-none">{{ old('mensaje_confirmacion', $config->mensaje_confirmacion) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Mensaje de WhatsApp</label>
                <textarea name="mensaje_whatsapp" rows="4" maxlength="1000"
                          class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 resize-none">{{ old('mensaje_whatsapp', $config->mensaje_whatsapp) }}</textarea>
            </div>
        </div>

        {{-- Botón Guardar --}}
        <div class="flex justify-end mt-5">
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 btn-blue-pulse text-sm font-semibold shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Guardar Configuración
            </button>
        </div>
    </form>
</x-app-layout>

@push('scripts')
<script>
@php
    $horarios = $config->horarios;
    if (is_string($horarios)) $horarios = json_decode($horarios, true);
    $horarios = $horarios ?: [['dia'=>'Lunes - Viernes','apertura'=>'08:00','cierre'=>'18:00','cerrado'=>false]];

    $servicios = $config->servicios;
    if (is_string($servicios)) $servicios = json_decode($servicios, true);
    $servicios = $servicios ?: [['nombre'=>'Consulta General','precio'=>250]];
@endphp
function horarioManager() {
    return {
        horarios: {!! json_encode($horarios) !!}
    };
}
function servicioManager() {
    return {
        servicios: {!! json_encode($servicios) !!}
    };
}
</script>
@endpush
