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
                <h2 class="text-xl font-bold text-slate-800">Editar Mascota</h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Modificando datos de
                    <span class="font-semibold text-slate-700">{{ $mascota->nombre }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">

        <!-- Pet Summary Badge -->
        @php
            $especieEmoji = match($mascota->especie) {
                'Perro'  => '🐶', 'Gato' => '🐱', 'Ave' => '🦜',
                'Conejo' => '🐰', 'Reptil' => '🦎', default => '🐾',
            };
        @endphp

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-3xl flex-shrink-0">
                {{ $especieEmoji }}
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-slate-800 text-lg">{{ $mascota->nombre }}</h3>
                <p class="text-sm text-slate-500">
                    {{ $mascota->especie }}{{ $mascota->raza ? ' · ' . $mascota->raza : '' }}
                    @if($mascota->sexo)
                        · <span class="{{ $mascota->sexo === 'Macho' ? 'text-blue-600' : 'text-pink-600' }} font-medium">{{ $mascota->sexo }}</span>
                    @endif
                </p>
            </div>
            @if($mascota->cliente)
                <div class="text-right flex-shrink-0 hidden sm:block">
                    <p class="text-xs text-slate-400">Propietario</p>
                    <p class="text-sm font-semibold text-slate-700">
                        {{ $mascota->cliente->nombre }} {{ $mascota->cliente->apellido }}
                    </p>
                </div>
            @endif
            @if($mascota->fecha_nacimiento)
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-slate-400">Edad</p>
                    <p class="text-sm font-bold text-sky-600">{{ $mascota->fecha_nacimiento->age }} años</p>
                </div>
            @endif
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Actualizar Información</h3>
                        <p class="text-amber-100 text-sm">Modifica los datos del paciente</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('mascotas.update', $mascota) }}" id="form-editar-mascota" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nombre de la Mascota <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre', $mascota->nombre) }}"
                           placeholder="Ej: Firulais, Luna, Max..."
                           required
                           class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all
                                  focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
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
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                       {{ $errors->has('cliente_id') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona un propietario —</option>
                            @foreach($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    {{ old('cliente_id', $mascota->cliente_id) == $cliente->id ? 'selected' : '' }}>
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
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                       {{ $errors->has('especie') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona —</option>
                            <option value="Perro"  {{ old('especie', $mascota->especie) === 'Perro'  ? 'selected' : '' }}>🐶 Perro</option>
                            <option value="Gato"   {{ old('especie', $mascota->especie) === 'Gato'   ? 'selected' : '' }}>🐱 Gato</option>
                            <option value="Ave"    {{ old('especie', $mascota->especie) === 'Ave'    ? 'selected' : '' }}>🦜 Ave</option>
                            <option value="Conejo" {{ old('especie', $mascota->especie) === 'Conejo' ? 'selected' : '' }}>🐰 Conejo</option>
                            <option value="Reptil" {{ old('especie', $mascota->especie) === 'Reptil' ? 'selected' : '' }}>🦎 Reptil</option>
                            <option value="Otro"   {{ old('especie', $mascota->especie) === 'Otro'   ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('especie')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="raza" class="block text-sm font-semibold text-slate-700 mb-1.5">Raza</label>
                        <input type="text"
                               id="raza"
                               name="raza"
                               value="{{ old('raza', $mascota->raza) }}"
                               placeholder="Ej: Labrador, Siamés..."
                               class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">
                    </div>
                </div>

                <!-- Sexo, Peso, Fecha Nacimiento -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label for="sexo" class="block text-sm font-semibold text-slate-700 mb-1.5">Sexo</label>
                        <select id="sexo"
                                name="sexo"
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">
                            <option value="">— Sexo —</option>
                            <option value="Macho"  {{ old('sexo', $mascota->sexo) === 'Macho'  ? 'selected' : '' }}>♂ Macho</option>
                            <option value="Hembra" {{ old('sexo', $mascota->sexo) === 'Hembra' ? 'selected' : '' }}>♀ Hembra</option>
                        </select>
                    </div>

                    <div>
                        <label for="peso" class="block text-sm font-semibold text-slate-700 mb-1.5">Peso (kg)</label>
                        <input type="number"
                               id="peso"
                               name="peso"
                               value="{{ old('peso', $mascota->peso) }}"
                               placeholder="Ej: 4.5"
                               min="0"
                               step="0.01"
                               class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">
                    </div>

                    <div>
                        <label for="fecha_nacimiento" class="block text-sm font-semibold text-slate-700 mb-1.5">Fecha Nacimiento</label>
                        <input type="date"
                               id="fecha_nacimiento"
                               name="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento', $mascota->fecha_nacimiento?->format('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">
                    </div>
                </div>

                <!-- Nota Médica -->
                <div>
                    <label for="nota_medica" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nota Médica / Observaciones
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <textarea id="nota_medica"
                                  name="nota_medica"
                                  rows="3"
                                  placeholder="Alergias, condiciones médicas, vacunas, tratamientos actuales..."
                                  class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all resize-none
                                         focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">{{ old('nota_medica', $mascota->nota_medica) }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row gap-3 justify-between items-center">
                    <form method="POST" action="{{ route('mascotas.destroy', $mascota) }}"
                          onsubmit="return confirm('⚠️ ¿Eliminar a {{ $mascota->nombre }}? Se perderán todas sus citas y registros.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                id="btn-eliminar-mascota"
                                class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors border border-rose-200 hover:border-rose-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    </form>

                    <div class="flex gap-3">
                        <a href="{{ route('mascotas.index') }}"
                           class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                                id="btn-actualizar-mascota"
                                class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Actualizar Mascota
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Historial de Citas -->
        @if($mascota->citas->count() > 0)
            <div class="mt-5 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Historial de Citas
                    </h3>
                    <a href="{{ route('citas.create', ['mascota_id' => $mascota->id]) }}"
                       class="text-xs text-violet-600 hover:underline font-medium">+ Nueva cita</a>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($mascota->citas->sortByDesc('fecha')->take(5) as $cita)
                        @php
                            $estadoClasses = match($cita->estado) {
                                'pendiente'  => 'bg-amber-100 text-amber-700',
                                'confirmada' => 'bg-emerald-100 text-emerald-700',
                                'completada' => 'bg-sky-100 text-sky-700',
                                'cancelada'  => 'bg-rose-100 text-rose-700',
                                default      => 'bg-slate-100 text-slate-600',
                            };
                        @endphp
                        <div class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50 transition-colors">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800">{{ $cita->tipo_servicio ?? 'Consulta' }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $cita->motivo ?? '—' }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <p class="text-xs font-semibold text-slate-700">{{ $cita->fecha->format('d/m/Y') }} {{ $cita->hora }}</p>
                                <span class="inline-block text-xs px-2 py-0.5 rounded-full mt-0.5 font-medium {{ $estadoClasses }}">
                                    {{ ucfirst($cita->estado ?? 'pendiente') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</x-app-layout>
