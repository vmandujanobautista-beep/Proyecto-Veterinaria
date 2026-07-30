<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('productos.index') }}"
               class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Editar Producto</h2>
                <p class="text-sm text-slate-500 mt-0.5">
                    Modificando: <span class="font-semibold text-slate-700">{{ $producto->nombre }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">

        @php
            $categoriaEmoji = match($producto->categoria) {
                'Medicamento' => '💊', 'Alimento' => '🥗', 'Accesorio' => '🎾',
                'Higiene' => '🧴', 'Vacuna' => '💉', 'Suplemento' => '🌿', default => '📦',
            };
            $stockCritico = $producto->stock <= 5;
        @endphp

        <!-- Product Badge -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center text-3xl flex-shrink-0">
                {{ $categoriaEmoji }}
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-slate-800 text-lg">{{ $producto->nombre }}</h3>
                <div class="flex items-center gap-2 mt-0.5">
                    @if($producto->codigo)
                        <code class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono">{{ $producto->codigo }}</code>
                    @endif
                    @if($producto->categoria)
                        <span class="text-xs text-slate-500">{{ $producto->categoria }}</span>
                    @endif
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-lg font-bold text-amber-600">${{ number_format($producto->precio, 2) }}</p>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                    {{ $stockCritico ? 'bg-rose-100 text-rose-700' : 'bg-emerald-100 text-emerald-700' }}">
                    {{ $stockCritico ? '⚠️' : '' }} {{ $producto->stock }} uds
                </span>
            </div>
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
                        <h3 class="text-white font-bold text-lg">Actualizar Producto</h3>
                        <p class="text-amber-100 text-sm">Modifica los datos del producto</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('productos.update', $producto) }}" id="form-editar-producto" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nombre del Producto <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre', $producto->nombre) }}"
                           placeholder="Ej: Amoxicilina 250mg, Royal Canin Adult..."
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

                <!-- Código y Categoría -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="codigo" class="block text-sm font-semibold text-slate-700 mb-1.5">Código / SKU</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <input type="text"
                                   id="codigo"
                                   name="codigo"
                                   value="{{ old('codigo', $producto->codigo) }}"
                                   placeholder="Ej: MED-001"
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all font-mono
                                          focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">
                        </div>
                    </div>

                    <div>
                        <label for="categoria" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Categoría <span class="text-rose-500">*</span>
                        </label>
                        <select id="categoria"
                                name="categoria"
                                required
                                class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                       {{ $errors->has('categoria') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona —</option>
                            @foreach(['Medicamento' => '💊','Alimento' => '🥗','Accesorio' => '🎾','Higiene' => '🧴','Vacuna' => '💉','Suplemento' => '🌿','Otro' => '📦'] as $cat => $emoji)
                                <option value="{{ $cat }}" {{ old('categoria', $producto->categoria) === $cat ? 'selected' : '' }}>
                                    {{ $emoji }} {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Precio y Stock -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="precio" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Precio (MXN) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm">$</span>
                            <input type="number"
                                   id="precio"
                                   name="precio"
                                   value="{{ old('precio', $producto->precio) }}"
                                   min="0"
                                   step="0.01"
                                   required
                                   class="w-full pl-7 pr-4 py-2.5 text-sm border rounded-xl transition-all
                                          focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                          {{ $errors->has('precio') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        </div>
                        @error('precio')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Stock Actual <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                            <input type="number"
                                   id="stock"
                                   name="stock"
                                   value="{{ old('stock', $producto->stock) }}"
                                   min="0"
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all
                                          focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                          {{ $errors->has('stock') ? 'border-rose-400 bg-rose-50' : ($stockCritico ? 'border-rose-300 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300') }}">
                        </div>
                        @if($stockCritico && !$errors->has('stock'))
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                Stock crítico — considera reabastecer
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-semibold text-slate-700 mb-1.5">Descripción</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <textarea id="descripcion"
                                  name="descripcion"
                                  rows="3"
                                  placeholder="Dosis, indicaciones, especies compatibles, composición..."
                                  class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all resize-none
                                         focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row gap-3 justify-between items-center">
                    <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                          onsubmit="return confirm('⚠️ ¿Eliminar {{ $producto->nombre }}? Se perderá del inventario.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                id="btn-eliminar-producto"
                                class="inline-flex items-center gap-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 text-sm font-medium px-4 py-2.5 rounded-xl transition-colors border border-rose-200 hover:border-rose-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar
                        </button>
                    </form>

                    <div class="flex gap-3">
                        <a href="{{ route('productos.index') }}"
                           class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                                id="btn-actualizar-producto"
                                class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            Actualizar Producto
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
