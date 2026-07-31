<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Productos</h2>
                <p class="text-sm text-slate-500 mt-0.5">Inventario y catálogo de la clínica</p>
            </div>
            <a href="{{ route('productos.create') }}"
               id="btn-nuevo-producto"
               class="ml-4 mt-2 inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Producto
            </a>
        </div>
    </x-slot>

    <!-- Stock Alert -->
    @if(isset($stockBajo) && $stockBajo->isNotEmpty())
        <div class="mb-5 bg-rose-50 border border-rose-200 rounded-xl p-4 flex gap-3">
            <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-semibold text-rose-800">⚠️ Productos con stock bajo</p>
                <p class="text-xs text-rose-600 mt-0.5">
                    {{ $stockBajo->pluck('nombre')->join(', ') }} — requieren reabastecimiento.
                </p>
            </div>
        </div>
    @endif

    <!-- Search & Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('productos.index') }}" class="flex flex-col sm:flex-row gap-3 flex-wrap">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="buscar-producto"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por nombre, código o descripción..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-slate-50 transition-all">
            </div>

            <select id="filtro-categoria"
                    name="categoria"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 bg-slate-50 text-slate-700 w-full sm:w-56 transition-all">
                <option value="">Todas las categorías</option>
                <option value="Medicamento"  {{ request('categoria') === 'Medicamento'  ? 'selected' : '' }}>💊 Medicamento</option>
                <option value="Alimento"     {{ request('categoria') === 'Alimento'     ? 'selected' : '' }}>🥗 Alimento</option>
                <option value="Accesorio"    {{ request('categoria') === 'Accesorio'    ? 'selected' : '' }}>🎾 Accesorio</option>
                <option value="Higiene"      {{ request('categoria') === 'Higiene'      ? 'selected' : '' }}>🧴 Higiene</option>
                <option value="Vacuna"       {{ request('categoria') === 'Vacuna'       ? 'selected' : '' }}>💉 Vacuna</option>
                <option value="Suplemento"   {{ request('categoria') === 'Suplemento'   ? 'selected' : '' }}>🌿 Suplemento</option>
                <option value="Otro"         {{ request('categoria') === 'Otro'         ? 'selected' : '' }}>Otro</option>
            </select>

            <button type="submit"
                    class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl w-32 transition-colors">
                Buscar
            </button>
            @if(request('buscar') || request('categoria'))
                <a href="{{ route('productos.index') }}"
                   class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors w-32  text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        @if($productos->isNotEmpty())
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando <span class="text-slate-800 font-bold">{{ $productos->count() }}</span> de
                    <span class="text-slate-800 font-bold">{{ $productos->total() }}</span> productos
                </p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                        <th class="text-left px-6 py-3.5 font-semibold">Producto</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden md:table-cell">Código</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden lg:table-cell">Categoría</th>
                        <th class="text-right px-6 py-3.5 font-semibold">Precio</th>
                        <th class="text-center px-6 py-3.5 font-semibold">Stock</th>
                        <th class="px-6 py-3.5 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($productos as $producto)
                        @php
                            $categoriaEmoji = match($producto->categoria) {
                                'Medicamento' => '💊', 'Alimento'   => '🥗',
                                'Accesorio'   => '🎾', 'Higiene'    => '🧴',
                                'Vacuna'      => '💉', 'Suplemento' => '🌿',
                                default       => '📦',
                            };
                            $categoriaColor = match($producto->categoria) {
                                'Medicamento' => 'bg-rose-100 text-rose-700',
                                'Alimento'    => 'bg-emerald-100 text-emerald-700',
                                'Accesorio'   => 'bg-sky-100 text-sky-700',
                                'Higiene'     => 'bg-teal-100 text-teal-700',
                                'Vacuna'      => 'bg-violet-100 text-violet-700',
                                'Suplemento'  => 'bg-lime-100 text-lime-700',
                                default       => 'bg-slate-100 text-slate-600',
                            };
                            $stockCritico = $producto->stock <= 5;
                            $stockBajoAlerta = $producto->stock > 5 && $producto->stock <= 15;
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors {{ $stockCritico ? 'bg-rose-50/30' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center text-xl flex-shrink-0">
                                        {{ $categoriaEmoji }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $producto->nombre }}</p>
                                        @if($producto->descripcion)
                                            <p class="text-xs text-slate-500 mt-0.5 max-w-xs truncate">{{ $producto->descripcion }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <code class="text-xs bg-slate-100 text-slate-600 px-2 py-0.5 rounded font-mono">
                                    {{ $producto->codigo ?? '—' }}
                                </code>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $categoriaColor }}">
                                    {{ $categoriaEmoji }} {{ $producto->categoria ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-slate-800">${{ number_format($producto->precio, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-full
                                    {{ $stockCritico ? 'bg-rose-100 text-rose-700' : ($stockBajoAlerta ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                    @if($stockCritico)
                                        ⚠️
                                    @endif
                                    {{ $producto->stock }} uds
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('productos.edit', $producto) }}"
                                       title="Editar"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('productos.destroy', $producto) }}"
                                          onsubmit="return confirm('¿Eliminar el producto {{ $producto->nombre }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                title="Eliminar"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-6xl mb-4">📦</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request('buscar') || request('categoria') ? 'No se encontraron productos' : 'No hay productos registrados' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request('buscar') || request('categoria') ? 'Intenta con otra búsqueda.' : 'Agrega productos al inventario.' }}
                                    </p>
                                    @if(!request('buscar') && !request('categoria'))
                                        <a href="{{ route('productos.create') }}"
                                           class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Agregar Primer Producto
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($productos->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $productos->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
