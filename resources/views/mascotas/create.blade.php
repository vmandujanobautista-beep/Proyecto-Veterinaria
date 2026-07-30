<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('mascotas.index') }}"
               class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Nueva Mascota</h2>
                <p class="text-sm text-slate-500 mt-0.5">Registra un nuevo paciente en la clínica</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-sky-600 to-blue-700 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-3xl">
                        🐾
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Registrar Mascota</h3>
                        <p class="text-sky-100 text-sm">Completa los datos del paciente</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('mascotas.store') }}" id="form-crear-mascota" class="p-6 space-y-5">
                @csrf

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nombre de la Mascota <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre') }}"
                           placeholder="Ej: Firulais, Luna, Max..."
                           required
                           class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all
                                  focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent
                                  {{ $errors->has('nombre') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                    @error('nombre')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Propietario -->
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
                                       focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent
                                       {{ $errors->has('cliente_id') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona un propietario —</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    {{ old('cliente_id', request('cliente_id')) == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->nombre }} {{ $cliente->apellido }} ({{ $cliente->email }})
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

                <!-- Especie y Raza -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="especie" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Especie <span class="text-rose-500">*</span>
                        </label>
                        <select id="especie"
                                name="especie"
                                required
                                class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent
                                       {{ $errors->has('especie') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona —</option>
                            <option value="Perro"  {{ old('especie') === 'Perro'  ? 'selected' : '' }}>🐶 Perro</option>
                            <option value="Gato"   {{ old('especie') === 'Gato'   ? 'selected' : '' }}>🐱 Gato</option>
                            <option value="Ave"    {{ old('especie') === 'Ave'    ? 'selected' : '' }}>🦜 Ave</option>
                            <option value="Conejo" {{ old('especie') === 'Conejo' ? 'selected' : '' }}>🐰 Conejo</option>
                            <option value="Reptil" {{ old('especie') === 'Reptil' ? 'selected' : '' }}>🦎 Reptil</option>
                            <option value="Otro"   {{ old('especie') === 'Otro'   ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('especie')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="raza" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Raza
                        </label>
                        <input type="text"
                               id="raza"
                               name="raza"
                               value="{{ old('raza') }}"
                               placeholder="Ej: Labrador, Siamés..."
                               class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent hover:border-slate-300
                                      {{ $errors->has('raza') ? 'border-rose-400 bg-rose-50' : '' }}">
                        @error('raza')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Sexo, Peso y Fecha de Nacimiento -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="sexo" class="block text-sm font-semibold text-slate-700 mb-1.5">Sexo</label>
                        <select id="sexo"
                                name="sexo"
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent hover:border-slate-300">
                            <option value="">— Sexo —</option>
                            <option value="Macho"  {{ old('sexo') === 'Macho'  ? 'selected' : '' }}>♂ Macho</option>
                            <option value="Hembra" {{ old('sexo') === 'Hembra' ? 'selected' : '' }}>♀ Hembra</option>
                        </select>
                    </div>

                    <div>
                        <label for="peso" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Peso (kg)
                        </label>
                        <input type="number"
                               id="peso"
                               name="peso"
                               value="{{ old('peso') }}"
                               placeholder="Ej: 4.5"
                               min="0"
                               step="0.01"
                               class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent hover:border-slate-300
                                      {{ $errors->has('peso') ? 'border-rose-400 bg-rose-50' : '' }}">
                        @error('peso')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Fecha de Nacimiento
                        </label>
                        <input type="date"
                               id="fecha_nacimiento"
                               name="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento') }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent hover:border-slate-300
                                      {{ $errors->has('fecha_nacimiento') ? 'border-rose-400 bg-rose-50' : '' }}">
                        @error('fecha_nacimiento')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Nota Médica -->
                <div>
                    <label for="nota_medica" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nota Médica / Observaciones
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <textarea id="nota_medica"
                                  name="nota_medica"
                                  rows="3"
                                  placeholder="Alergias, condiciones médicas, vacunas, tratamientos actuales..."
                                  class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all resize-none
                                         focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent hover:border-slate-300">{{ old('nota_medica') }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('mascotas.index') }}"
                       class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                        Cancelar
                    </a>
                    <button type="submit"
                            id="btn-guardar-mascota"
                            class="inline-flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Mascota
                    </button>
                </div>
            </form>
        </div>

        <!-- Tip -->
        <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
            <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-amber-800">Tip clínico</p>
                <p class="text-xs text-amber-700 mt-0.5">
                    Ingresa la nota médica con información sobre alergias y condiciones crónicas.
                    Será visible al programar citas.
                </p>
            </div>
        </div>
    </div>

</x-app-layout>
