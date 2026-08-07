<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Catálogo de Productos</h2>
                <p class="text-sm text-slate-500 mt-0.5">Consulta de inventario disponible para venta</p>
            </div>

        </div>
    </x-slot>



    {{-- ── FILTROS ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('productos.index') }}"
              class="flex flex-col sm:flex-row gap-3 flex-wrap" id="form-filtros-productos">

            {{-- Buscador --}}
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="buscar-producto"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por nombre, código o categoría..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl
                              focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent
                              bg-slate-50 transition-all">
            </div>

            {{-- Filtro categoría --}}
            <select id="filtro-categoria" name="categoria"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-sky-400 bg-slate-50
                           text-slate-700 w-full sm:w-52 transition-all">
                <option value="">Todas las categorías</option>
                <option value="medicamento" {{ request('categoria') === 'medicamento' ? 'selected' : '' }}>💊 Medicamento</option>
                <option value="vacuna"      {{ request('categoria') === 'vacuna'      ? 'selected' : '' }}>💉 Vacuna</option>
                <option value="alimento"    {{ request('categoria') === 'alimento'    ? 'selected' : '' }}>🍖 Alimento</option>
                <option value="accesorio"   {{ request('categoria') === 'accesorio'   ? 'selected' : '' }}>🎀 Accesorio</option>
                <option value="otro"        {{ request('categoria') === 'otro'        ? 'selected' : '' }}>📦 Otro</option>
            </select>


            <button type="submit" id="btn-buscar-productos"
                    class="btn-gold-pulse px-5 py-2.5 text-sm font-medium w-32 shadow-sm active:scale-95">
                Buscar
            </button>

            @if(request('buscar') || request('categoria'))
                <a href="{{ route('productos.index') }}"
                   class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium
                          rounded-xl hover:bg-slate-50 transition-colors w-32 text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- ── TABLA DE PRODUCTOS ─────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        @if($productos->isNotEmpty())
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando <span class="text-slate-800 font-bold">{{ $productos->count() }}</span> de
                    <span class="text-slate-800 font-bold">{{ $productos->total() }}</span> productos
                </p>
                {{-- Pills de filtros activos --}}
                <div class="flex items-center gap-2">
                    @if(request('buscar'))
                        <span class="inline-flex items-center gap-1 text-xs bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full font-medium">
                            🔍 "{{ Str::limit(request('buscar'), 20) }}"
                        </span>
                    @endif
                    @if(request('categoria'))
                        <span class="inline-flex items-center gap-1 text-xs bg-violet-100 text-violet-700 px-2 py-0.5 rounded-full font-medium capitalize">
                            {{ request('categoria') }}
                        </span>
                    @endif

                </div>
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
                        <th class="text-center px-6 py-3.5 font-semibold">Disponibilidad</th>
                        <th class="px-6 py-3.5 text-center font-semibold">Ver</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($productos as $producto)
                        @php
                            // Normalizar a minúsculas para comparación consistente
                            $cat = strtolower($producto->categoria ?? '');

                            $catEmoji = match($cat) {
                                'medicamento' => '💊',
                                'vacuna'      => '💉',
                                'alimento'    => '🍖',
                                'accesorio'   => '🎀',
                                default       => '📦',
                            };
                            $catBadge = match($cat) {
                                'medicamento' => 'bg-blue-100 text-blue-700',
                                'vacuna'      => 'bg-violet-100 text-violet-700',
                                'alimento'    => 'bg-emerald-100 text-emerald-700',
                                'accesorio'   => 'bg-orange-100 text-orange-700',
                                default       => 'bg-slate-100 text-slate-600',
                            };
                            $catIconBg = match($cat) {
                                'medicamento' => 'bg-blue-50',
                                'vacuna'      => 'bg-violet-50',
                                'alimento'    => 'bg-emerald-50',
                                'accesorio'   => 'bg-orange-50',
                                default       => 'bg-slate-100',
                            };

                            // Estado de stock
                            $stock = $producto->stock;
                            if ($stock > 20) {
                                $stockBadge = 'bg-emerald-100 text-emerald-700';
                                $stockLabel = 'Disponible';
                                $stockDot   = '🟢';
                                $rowClass   = '';
                            } elseif ($stock >= 5) {
                                $stockBadge = 'bg-amber-100 text-amber-700';
                                $stockLabel = 'Stock bajo';
                                $stockDot   = '🟡';
                                $rowClass   = '';
                            } elseif ($stock >= 1) {
                                $stockBadge = 'bg-rose-100 text-rose-700';
                                $stockLabel = 'Últimas unidades';
                                $stockDot   = '🔴';
                                $rowClass   = 'bg-rose-50/20';
                            } else {
                                $stockBadge = 'bg-slate-200 text-slate-600';
                                $stockLabel = 'Agotado';
                                $stockDot   = '⚫';
                                $rowClass   = 'bg-slate-50/50 opacity-75';
                            }
                        @endphp

                        <tr class="hover:bg-slate-50/80 transition-colors {{ $rowClass }}">

                            {{-- Producto (imagen/icono + nombre + descripción) --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl {{ $catIconBg }} flex items-center justify-center text-xl flex-shrink-0">
                                        {{ $catEmoji }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-slate-800 leading-tight">
                                            {{ $producto->nombre }}
                                        </p>
                                        @if($producto->descripcion)
                                            <p class="text-xs text-slate-400 mt-0.5 truncate max-w-xs"
                                               title="{{ $producto->descripcion }}">
                                                {{ Str::limit($producto->descripcion, 55) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            {{-- Código --}}
                            <td class="px-6 py-4 hidden md:table-cell">
                                <code class="text-xs bg-slate-100 text-slate-500 px-2 py-1 rounded font-mono tracking-wide">
                                    {{ $producto->codigo ?? '—' }}
                                </code>
                            </td>

                            {{-- Categoría --}}
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $catBadge }}">
                                    {{ $catEmoji }} {{ ucfirst($producto->categoria ?? '—') }}
                                </span>
                            </td>

                            {{-- Precio --}}
                            <td class="px-6 py-4 text-right">
                                <span class="font-bold text-slate-800 text-base">
                                    ${{ number_format($producto->precio, 2) }}
                                </span>
                            </td>

                            {{-- Stock / Disponibilidad --}}
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full {{ $stockBadge }}">
                                        {{ $stockDot }} {{ $stockLabel }}
                                    </span>
                                    <span class="text-xs text-slate-400 font-medium">
                                        {{ $stock }} {{ $stock === 1 ? 'unidad' : 'unidades' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Acción: VER + botón reabastecimiento si stock < 10 --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Botón Ver --}}
                                    <button type="button"
                                            id="btn-ver-producto-{{ $producto->id }}"
                                            @click="$dispatch('ver-producto', { id: {{ $producto->id }} })"
                                            title="Ver ficha del producto"
                                            class="group/btn p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-all duration-150">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"
                                                  class="transition-transform duration-150 ease-out origin-center group-hover/btn:scale-75"/>
                                            <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"
                                                  class="transition-transform duration-150 ease-out origin-center group-hover/btn:scale-y-90"/>
                                        </svg>
                                    </button>

                                    {{-- Botón reabastecimiento (solo si stock < 10) --}}
                                    @if($stock === 0)
                                        <button type="button"
                                                @click="$dispatch('solicitar-reabastecimiento', {
                                                    id:        {{ $producto->id }},
                                                    nombre:    '{{ addslashes($producto->nombre) }}',
                                                    codigo:    '{{ $producto->codigo }}',
                                                    categoria: '{{ $producto->categoria }}',
                                                    stock:     {{ $stock }}
                                                })"
                                                title="Reabastecer urgente — producto agotado"
                                                class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1
                                                       bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition-colors whitespace-nowrap">
                                            🔴 Reabastecer
                                        </button>
                                    @elseif($stock <= 4)
                                        <button type="button"
                                                @click="$dispatch('solicitar-reabastecimiento', {
                                                    id:        {{ $producto->id }},
                                                    nombre:    '{{ addslashes($producto->nombre) }}',
                                                    codigo:    '{{ $producto->codigo }}',
                                                    categoria: '{{ $producto->categoria }}',
                                                    stock:     {{ $stock }}
                                                })"
                                                title="Solicitar urgente — últimas unidades"
                                                class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1
                                                       bg-orange-100 text-orange-700 hover:bg-orange-200 rounded-lg transition-colors whitespace-nowrap">
                                            ⚠️ Urgente
                                        </button>
                                    @elseif($stock < 10)
                                        <button type="button"
                                                @click="$dispatch('solicitar-reabastecimiento', {
                                                    id:        {{ $producto->id }},
                                                    nombre:    '{{ addslashes($producto->nombre) }}',
                                                    codigo:    '{{ $producto->codigo }}',
                                                    categoria: '{{ $producto->categoria }}',
                                                    stock:     {{ $stock }}
                                                })"
                                                title="Solicitar más stock"
                                                class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-1
                                                       bg-amber-100 text-amber-700 hover:bg-amber-200 rounded-lg transition-colors whitespace-nowrap">
                                            📦 Solicitar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-6xl mb-4">🔍</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request('buscar') || request('categoria')
                                            ? 'No se encontraron productos'
                                            : 'No hay productos en el catálogo' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request('buscar') || request('categoria')
                                            ? 'Intenta con otra búsqueda o limpia los filtros.'
                                            : 'El catálogo de productos aparecerá aquí cuando esté disponible.' }}
                                    </p>

                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($productos->hasPages())
            <div class="px-6 py-6 border-t border-slate-100 flex justify-center">
                {{ $productos->links('vendor.pagination.uiverse-gold') }}
            </div>
        @endif

    </div>

</x-app-layout>
