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
                <h2 class="text-xl font-bold text-slate-800">Nuevo Producto</h2>
                <p class="text-sm text-slate-500 mt-0.5">Agrega un producto al inventario</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-3xl">
                        📦
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Registrar Producto</h3>
                        <p class="text-amber-100 text-sm">Completa los datos del producto</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('productos.store') }}" id="form-crear-producto" class="p-6 space-y-5">
                @csrf

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nombre del Producto <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nombre"
                           name="nombre"
                           value="{{ old('nombre') }}"
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
                        <label for="codigo" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Código / SKU
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <input type="text"
                                   id="codigo"
                                   name="codigo"
                                   value="{{ old('codigo') }}"
                                   placeholder="Ej: MED-001"
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all font-mono
                                          focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                          {{ $errors->has('codigo') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        </div>
                        @error('codigo')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
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
                            <option value="">— Selecciona categoría —</option>
                            <option value="Medicamento" {{ old('categoria') === 'Medicamento' ? 'selected' : '' }}>💊 Medicamento</option>
                            <option value="Alimento"    {{ old('categoria') === 'Alimento'    ? 'selected' : '' }}>🥗 Alimento</option>
                            <option value="Accesorio"   {{ old('categoria') === 'Accesorio'   ? 'selected' : '' }}>🎾 Accesorio</option>
                            <option value="Higiene"     {{ old('categoria') === 'Higiene'     ? 'selected' : '' }}>🧴 Higiene</option>
                            <option value="Vacuna"      {{ old('categoria') === 'Vacuna'      ? 'selected' : '' }}>💉 Vacuna</option>
                            <option value="Suplemento"  {{ old('categoria') === 'Suplemento'  ? 'selected' : '' }}>🌿 Suplemento</option>
                            <option value="Otro"        {{ old('categoria') === 'Otro'        ? 'selected' : '' }}>📦 Otro</option>
                        </select>
                        @error('categoria')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
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
                                   value="{{ old('precio') }}"
                                   placeholder="0.00"
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
                            Stock Inicial <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                            <input type="number"
                                   id="stock"
                                   name="stock"
                                   value="{{ old('stock', 0) }}"
                                   placeholder="0"
                                   min="0"
                                   required
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all
                                          focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                                          {{ $errors->has('stock') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        </div>
                        @error('stock')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="descripcion" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Descripción
                    </label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <textarea id="descripcion"
                                  name="descripcion"
                                  rows="3"
                                  placeholder="Dosis, indicaciones, especies compatibles, composición..."
                                  class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all resize-none
                                         focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <!-- Preview -->
                <div id="producto-preview" class="hidden bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-amber-700 mb-2 uppercase tracking-wide">Vista previa</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-200 flex items-center justify-center text-xl" id="preview-emoji">📦</div>
                        <div>
                            <p class="text-sm font-bold text-slate-800" id="preview-nombre">—</p>
                            <p class="text-xs text-slate-500" id="preview-categoria">Sin categoría</p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-sm font-bold text-amber-700" id="preview-precio">$0.00</p>
                            <p class="text-xs text-slate-500" id="preview-stock">0 unidades</p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('productos.index') }}"
                       class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                        Cancelar
                    </a>
                    <button type="submit"
                            id="btn-guardar-producto"
                            class="inline-flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const emojisCategoria = {
            'Medicamento': '💊', 'Alimento': '🥗', 'Accesorio': '🎾',
            'Higiene': '🧴', 'Vacuna': '💉', 'Suplemento': '🌿', 'Otro': '📦'
        };

        function actualizarPreview() {
            const nombre    = document.getElementById('nombre').value.trim();
            const categoria = document.getElementById('categoria').value;
            const precio    = parseFloat(document.getElementById('precio').value) || 0;
            const stock     = parseInt(document.getElementById('stock').value) || 0;
            const preview   = document.getElementById('producto-preview');

            if (nombre) {
                preview.classList.remove('hidden');
                document.getElementById('preview-nombre').textContent    = nombre || '—';
                document.getElementById('preview-categoria').textContent = categoria || 'Sin categoría';
                document.getElementById('preview-emoji').textContent     = emojisCategoria[categoria] || '📦';
                document.getElementById('preview-precio').textContent    = '$' + precio.toFixed(2);
                document.getElementById('preview-stock').textContent     = stock + ' unidades';
            } else {
                preview.classList.add('hidden');
            }
        }

        ['nombre','categoria','precio','stock'].forEach(id => {
            document.getElementById(id).addEventListener('input', actualizarPreview);
        });
    </script>

</x-app-layout>
