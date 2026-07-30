<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('ventas.index') }}"
               class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h2 class="text-xl font-bold text-slate-800">Nueva Venta</h2>
                <p class="text-sm text-slate-500 mt-0.5">Registra una venta de productos</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-rose-600 to-pink-700 px-6 py-5">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-3xl">
                        🧾
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg">Registrar Venta</h3>
                        <p class="text-rose-100 text-sm">Completa los datos de la transacción</p>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('ventas.store') }}" id="form-crear-venta" class="p-6 space-y-6">
                @csrf

                <!-- Cliente y Mascota -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="cliente_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Cliente <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <select id="cliente_id"
                                    name="cliente_id"
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all appearance-none
                                           focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent
                                           {{ $errors->has('cliente_id') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                                <option value="">— Selecciona cliente —</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}"
                                        {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre }} {{ $cliente->apellido }}
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

                    <div>
                        <label for="mascota_id" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Mascota <span class="text-slate-400 font-normal text-xs">(opcional)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-base">🐾</span>
                            <select id="mascota_id"
                                    name="mascota_id"
                                    class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all appearance-none
                                           focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent hover:border-slate-300">
                                <option value="">— Sin mascota —</option>
                                @foreach($mascotas as $mascota)
                                    <option value="{{ $mascota->id }}"
                                        {{ old('mascota_id') == $mascota->id ? 'selected' : '' }}>
                                        {{ $mascota->nombre }} ({{ $mascota->especie }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Método de Pago y Estado -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="metodo_pago" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Método de Pago <span class="text-rose-500">*</span>
                        </label>
                        <select id="metodo_pago"
                                name="metodo_pago"
                                required
                                class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent
                                       {{ $errors->has('metodo_pago') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona método —</option>
                            <option value="Efectivo"      {{ old('metodo_pago') === 'Efectivo'      ? 'selected' : '' }}>💵 Efectivo</option>
                            <option value="Tarjeta"       {{ old('metodo_pago') === 'Tarjeta'       ? 'selected' : '' }}>💳 Tarjeta de crédito/débito</option>
                            <option value="Transferencia" {{ old('metodo_pago') === 'Transferencia' ? 'selected' : '' }}>🏦 Transferencia bancaria</option>
                        </select>
                        @error('metodo_pago')
                            <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="estado" class="block text-sm font-semibold text-slate-700 mb-1.5">Estado</label>
                        <select id="estado"
                                name="estado"
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all appearance-none
                                       focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent hover:border-slate-300">
                            <option value="completada" {{ old('estado', 'completada') === 'completada' ? 'selected' : '' }}>✅ Completada</option>
                            <option value="pendiente"  {{ old('estado') === 'pendiente'  ? 'selected' : '' }}>⏳ Pendiente de pago</option>
                            <option value="cancelada"  {{ old('estado') === 'cancelada'  ? 'selected' : '' }}>❌ Cancelada</option>
                        </select>
                    </div>
                </div>

                <!-- Productos Section -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-sm font-semibold text-slate-700">
                            Productos <span class="text-rose-500">*</span>
                        </label>
                        <button type="button"
                                id="btn-agregar-fila"
                                class="inline-flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-800 font-semibold bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Agregar producto
                        </button>
                    </div>

                    <div id="productos-container" class="space-y-3">
                        <!-- Fila inicial -->
                        <div class="producto-fila grid grid-cols-12 gap-2 items-start bg-slate-50 rounded-xl p-3 border border-slate-100">
                            <div class="col-span-12 sm:col-span-5">
                                <label class="text-xs text-slate-500 mb-1 block">Producto</label>
                                <select name="productos[0][producto_id]"
                                        class="producto-select w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white">
                                    <option value="">— Selecciona —</option>
                                    @foreach($productos as $producto)
                                        <option value="{{ $producto->id }}"
                                                data-precio="{{ $producto->precio }}"
                                                data-stock="{{ $producto->stock }}">
                                            {{ $producto->nombre }} — ${{ number_format($producto->precio, 2) }} ({{ $producto->stock }} uds)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-4 sm:col-span-2">
                                <label class="text-xs text-slate-500 mb-1 block">Cantidad</label>
                                <input type="number"
                                       name="productos[0][cantidad]"
                                       class="cantidad-input w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white text-center"
                                       value="1" min="1">
                            </div>
                            <div class="col-span-5 sm:col-span-3">
                                <label class="text-xs text-slate-500 mb-1 block">Precio unit.</label>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-bold">$</span>
                                    <input type="number"
                                           name="productos[0][precio_unitario]"
                                           class="precio-input w-full pl-5 pr-2 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-rose-400 bg-white"
                                           placeholder="0.00" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-span-10 sm:col-span-1">
                                <label class="text-xs text-slate-500 mb-1 block">Subtotal</label>
                                <p class="subtotal-display text-sm font-bold text-slate-700 py-2">$0.00</p>
                            </div>
                            <div class="col-span-2 sm:col-span-1 flex items-end pb-0.5">
                                <button type="button"
                                        class="btn-quitar-fila text-slate-300 hover:text-rose-500 transition-colors p-1 invisible">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="mt-4 flex justify-end">
                        <div class="bg-slate-800 text-white rounded-xl px-6 py-3 flex items-center gap-4">
                            <span class="text-sm font-medium text-slate-300">Total a cobrar:</span>
                            <span id="total-display" class="text-2xl font-bold">$0.00</span>
                        </div>
                    </div>

                    <!-- Hidden total input -->
                    <input type="hidden" name="total" id="total-input" value="0">
                </div>

                <!-- Actions -->
                <div class="border-t border-slate-100 pt-4 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('ventas.index') }}"
                       class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                        Cancelar
                    </a>
                    <button type="submit"
                            id="btn-guardar-venta"
                            class="inline-flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-700 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                        </svg>
                        Registrar Venta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let filaIndex = 1;

        const productosData = @json($productos->keyBy('id'));

        function calcularSubtotal(fila) {
            const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
            const precio   = parseFloat(fila.querySelector('.precio-input').value)   || 0;
            const subtotal = cantidad * precio;
            fila.querySelector('.subtotal-display').textContent = '$' + subtotal.toFixed(2);
            calcularTotal();
        }

        function calcularTotal() {
            let total = 0;
            document.querySelectorAll('.producto-fila').forEach(fila => {
                const cantidad = parseFloat(fila.querySelector('.cantidad-input').value) || 0;
                const precio   = parseFloat(fila.querySelector('.precio-input').value)   || 0;
                total += cantidad * precio;
            });
            document.getElementById('total-display').textContent = '$' + total.toFixed(2);
            document.getElementById('total-input').value = total.toFixed(2);
        }

        function inicializarFila(fila) {
            const productoSelect = fila.querySelector('.producto-select');
            const precioInput    = fila.querySelector('.precio-input');
            const cantidadInput  = fila.querySelector('.cantidad-input');
            const btnQuitar      = fila.querySelector('.btn-quitar-fila');

            productoSelect.addEventListener('change', () => {
                const id = productoSelect.value;
                if (id && productosData[id]) {
                    precioInput.value = productosData[id].precio;
                    cantidadInput.max = productosData[id].stock;
                }
                calcularSubtotal(fila);
            });

            precioInput.addEventListener('input', () => calcularSubtotal(fila));
            cantidadInput.addEventListener('input', () => calcularSubtotal(fila));

            btnQuitar.addEventListener('click', () => {
                fila.remove();
                actualizarBotonesQuitar();
                calcularTotal();
            });
        }

        function actualizarBotonesQuitar() {
            const filas = document.querySelectorAll('.producto-fila');
            filas.forEach(f => {
                const btn = f.querySelector('.btn-quitar-fila');
                btn.classList.toggle('invisible', filas.length === 1);
            });
        }

        // Inicializar fila existente
        inicializarFila(document.querySelector('.producto-fila'));

        document.getElementById('btn-agregar-fila').addEventListener('click', () => {
            const template = document.querySelector('.producto-fila').cloneNode(true);

            // Reset values
            template.querySelector('.producto-select').name = `productos[${filaIndex}][producto_id]`;
            template.querySelector('.producto-select').value = '';
            template.querySelector('.cantidad-input').name  = `productos[${filaIndex}][cantidad]`;
            template.querySelector('.cantidad-input').value = '1';
            template.querySelector('.precio-input').name    = `productos[${filaIndex}][precio_unitario]`;
            template.querySelector('.precio-input').value   = '';
            template.querySelector('.subtotal-display').textContent = '$0.00';
            template.querySelector('.btn-quitar-fila').classList.remove('invisible');

            document.getElementById('productos-container').appendChild(template);
            inicializarFila(template);
            actualizarBotonesQuitar();
            filaIndex++;
        });
    </script>

</x-app-layout>
