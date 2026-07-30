<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('clientes.index') }}"
               class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Nuevo Cliente</h2>
                <p class="text-sm text-slate-500 mt-0.5">Registra un nuevo propietario de mascota</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Registrar Cliente</h3>
                        <p class="text-emerald-100 text-sm">Completa los datos del propietario</p>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('clientes.store') }}" id="form-crear-cliente" class="p-6 space-y-5">
                @csrf

                <!-- Nombre y Apellido -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nombre <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="nombre"
                               name="nombre"
                               value="{{ old('nombre') }}"
                               placeholder="Ej: María"
                               required
                               autocomplete="given-name"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                      {{ $errors->has('nombre') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        @error('nombre')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="apellido" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Apellido <span class="text-rose-500">*</span>
                        </label>
                        <input type="text"
                               id="apellido"
                               name="apellido"
                               value="{{ old('apellido') }}"
                               placeholder="Ej: García López"
                               required
                               autocomplete="family-name"
                               class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                      {{ $errors->has('apellido') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        @error('apellido')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Correo Electrónico <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <input type="email"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="correo@ejemplo.com"
                               required
                               autocomplete="email"
                               class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                      {{ $errors->has('email') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                    </div>
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Teléfono
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <input type="tel"
                               id="telefono"
                               name="telefono"
                               value="{{ old('telefono') }}"
                               placeholder="Ej: +52 55 1234 5678"
                               autocomplete="tel"
                               class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all
                                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                      {{ $errors->has('telefono') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                    </div>
                    @error('telefono')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Dirección
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <textarea id="direccion"
                                  name="direccion"
                                  rows="3"
                                  placeholder="Calle, número, colonia, ciudad..."
                                  autocomplete="street-address"
                                  class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all resize-none
                                         focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                         {{ $errors->has('direccion') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">{{ old('direccion') }}</textarea>
                    </div>
                    @error('direccion')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Divider -->
                <div class="border-t border-slate-100 pt-4">
                    <div class="flex flex-col sm:flex-row gap-3 justify-end">
                        <a href="{{ route('clientes.index') }}"
                           class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                            Cancelar
                        </a>
                        <button type="submit"
                                id="btn-guardar-cliente"
                                class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Guardar Cliente
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Hint Card -->
        <div class="mt-4 bg-sky-50 border border-sky-200 rounded-xl p-4 flex gap-3">
            <svg class="w-5 h-5 text-sky-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <p class="text-sm font-semibold text-sky-800">¿Sabías que?</p>
                <p class="text-xs text-sky-600 mt-0.5">
                    Después de registrar el cliente, podrás agregar sus mascotas y programar citas desde su perfil.
                </p>
            </div>
        </div>
    </div>

</x-app-layout>
