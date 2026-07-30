<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('citas.index') }}"
               class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Nueva Cita</h2>
                <p class="text-sm text-slate-500 mt-0.5">Agenda una consulta veterinaria</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-violet-600 to-purple-700 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Agendar Cita</h3>
                        <p class="text-violet-100 text-sm">Completa los datos de la consulta</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('citas.store') }}" id="form-crear-cita" class="p-6 space-y-5">
                @csrf

                <!-- Mascota -->
                <div>
                    <label for="mascota_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Mascota <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-lg">🐾</span>
                        <select id="mascota_id"
                                name="mascota_id"
                                required
                                class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                       {{ $errors->has('mascota_id') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona una mascota —</option>
                            @foreach($mascotas as $mascota)
                                <option value="{{ $mascota->id }}"
                                    {{ old('mascota_id', request('mascota_id')) == $mascota->id ? 'selected' : '' }}>
                                    {{ $mascota->nombre }}
                                    ({{ $mascota->especie }}{{ $mascota->raza ? ' · ' . $mascota->raza : '' }})
                                    — {{ $mascota->cliente->nombre ?? '' }} {{ $mascota->cliente->apellido ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('mascota_id')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Cliente (auto-llenado o selección) -->
                <div>
                    <label for="cliente_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Propietario <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <select id="cliente_id"
                                name="cliente_id"
                                required
                                class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                       {{ $errors->has('cliente_id') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona un propietario —</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    {{ old('cliente_id', request('cliente_id')) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} {{ $cliente->apellido }} · {{ $cliente->telefono ?? $cliente->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('cliente_id')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Fecha y Hora -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="fecha" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Fecha <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <input type="date"
                                   id="fecha"
                                   name="fecha"
                                   value="{{ old('fecha', date('Y-m-d')) }}"
                                   min="{{ date('Y-m-d') }}"
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all
                                          focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                          {{ $errors->has('fecha') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        </div>
                        @error('fecha')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="hora" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Hora <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <input type="time"
                                   id="hora"
                                   name="hora"
                                   value="{{ old('hora', '09:00') }}"
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all
                                          focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                          {{ $errors->has('hora') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        </div>
                        @error('hora')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Tipo Servicio y Estado -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tipo_servicio" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tipo de Servicio <span class="text-rose-500">*</span>
                        </label>
                        <select id="tipo_servicio"
                                name="tipo_servicio"
                                required
                                class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent
                                       {{ $errors->has('tipo_servicio') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona servicio —</option>
                            <option value="Consulta general"    {{ old('tipo_servicio') === 'Consulta general'    ? 'selected' : '' }}>🩺 Consulta general</option>
                            <option value="Vacunación"          {{ old('tipo_servicio') === 'Vacunación'          ? 'selected' : '' }}>💉 Vacunación</option>
                            <option value="Desparasitación"     {{ old('tipo_servicio') === 'Desparasitación'     ? 'selected' : '' }}>🦠 Desparasitación</option>
                            <option value="Cirugía"             {{ old('tipo_servicio') === 'Cirugía'             ? 'selected' : '' }}>🔬 Cirugía</option>
                            <option value="Baño y estética"     {{ old('tipo_servicio') === 'Baño y estética'     ? 'selected' : '' }}>🛁 Baño y estética</option>
                            <option value="Urgencia"            {{ old('tipo_servicio') === 'Urgencia'            ? 'selected' : '' }}>🚨 Urgencia</option>
                            <option value="Revisión"            {{ old('tipo_servicio') === 'Revisión'            ? 'selected' : '' }}>📋 Revisión</option>
                            <option value="Laboratorio"         {{ old('tipo_servicio') === 'Laboratorio'         ? 'selected' : '' }}>🧪 Laboratorio</option>
                            <option value="Otro"                {{ old('tipo_servicio') === 'Otro'                ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('tipo_servicio')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Estado
                        </label>
                        <select id="estado"
                                name="estado"
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent hover:border-slate-300">
                            <option value="pendiente"  {{ old('estado', 'pendiente') === 'pendiente'  ? 'selected' : '' }}>⏳ Pendiente</option>
                            <option value="confirmada" {{ old('estado') === 'confirmada' ? 'selected' : '' }}>✅ Confirmada</option>
                            <option value="completada" {{ old('estado') === 'completada' ? 'selected' : '' }}>🏁 Completada</option>
                            <option value="cancelada"  {{ old('estado') === 'cancelada'  ? 'selected' : '' }}>❌ Cancelada</option>
                        </select>
                    </div>
                </div>

                <!-- Motivo -->
                <div>
                    <label for="motivo" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Motivo / Descripción
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        <textarea id="motivo"
                                  name="motivo"
                                  rows="3"
                                  placeholder="Describe el motivo de la consulta, síntomas observados..."
                                  class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all resize-none
                                         focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-transparent hover:border-slate-300">{{ old('motivo') }}</textarea>
                    </div>
                </div>

                <!-- Notificaciones -->
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p class="text-sm font-semibold text-slate-700 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        Notificaciones al Propietario
                    </p>
                    <div class="space-y-2">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   id="enviado_email"
                                   name="enviado_email"
                                   value="1"
                                   {{ old('enviado_email') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-violet-600 border-slate-300 focus:ring-violet-500">
                            <span class="text-sm text-slate-700">
                                📧 Enviar recordatorio por <strong>Email</strong>
                            </span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox"
                                   id="enviado_whatsapp"
                                   name="enviado_whatsapp"
                                   value="1"
                                   {{ old('enviado_whatsapp') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-violet-600 border-slate-300 focus:ring-violet-500">
                            <span class="text-sm text-slate-700">
                                📱 Enviar recordatorio por <strong>WhatsApp</strong>
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('citas.index') }}"
                       class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                        Cancelar
                    </a>
                    <button type="submit"
                            id="btn-guardar-cita"
                            class="inline-flex items-center justify-center gap-2 bg-violet-600 hover:bg-violet-700 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Agendar Cita
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="mt-4 bg-violet-50 border border-violet-200 rounded-xl p-4 flex gap-3">
            <svg class="w-5 h-5 text-violet-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-violet-800">Horario de atención</p>
                <p class="text-xs text-violet-600 mt-0.5">
                    Lunes a Viernes de 9:00 a 18:00 hrs · Sábados de 9:00 a 14:00 hrs · Emergencias 24/7
                </p>
            </div>
        </div>
    </div>

</x-app-layout>
