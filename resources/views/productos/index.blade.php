<x-app-layout>
    <style>
        .btn-nuevo-producto {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #d97706, #fbbf24);
            border-radius: 0.75rem; /* rounded-xl */
            background-size: 100% auto;
            font-family: inherit;
            font-size: 0.875rem; /* text-sm */
            font-weight: 600; /* font-semibold */
            padding: 0.625rem 1rem; /* px-4 py-2.5 */
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-nuevo-producto:hover {
            background-position: right center;
            background-size: 200% auto;
            animation: pulse512 1.5s infinite;
        }

        @keyframes pulse512 {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.6);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(245, 158, 11, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        .pen-group {
            transform-origin: 50% 50%;
            transform-box: fill-box;
        }
        
        .pen-slash {
            stroke-dasharray: 10;
            stroke-dashoffset: 10;
            opacity: 0;
        }

        .group\/btn:hover .pen-group {
            animation: pen-scribble 1.05s ease-in-out infinite;
        }

        .group\/btn:hover .pen-slash {
            animation: pen-slash-draw 1.05s ease-out infinite;
        }

        @keyframes pen-scribble {
            0% { transform: translate(0, 0) rotate(0deg); }
            13% { transform: translate(1px, -2px) rotate(-6deg); }
            26% { transform: translate(-1px, -4px) rotate(-4deg); }
            39% { transform: translate(1px, -6px) rotate(-6deg); }
            52% { transform: translate(-1px, -8px) rotate(-4deg); }
            65% { transform: translate(0, -10px) rotate(0deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        @keyframes pen-slash-draw {
            0% { stroke-dashoffset: 10; opacity: 0; }
            30% { stroke-dashoffset: 10; opacity: 0; }
            50% { stroke-dashoffset: 0; opacity: 1; }
            80% { stroke-dashoffset: 0; opacity: 0; }
            100% { stroke-dashoffset: 0; opacity: 0; }
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Catálogo de Productos</h2>
                <p class="text-sm text-slate-500 mt-0.5">Consulta de inventario disponible para venta</p>
            </div>
            @if(auth()->check() && auth()->user()->isAdmin())
                <button type="button" @click="$dispatch('open-modal-nuevo-producto')" class="btn-nuevo-producto shadow-sm ml-4 mt-2 sm:mt-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo Producto
                </button>
            @endif
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
                <option value="Medicamento" {{ request('categoria') === 'Medicamento' ? 'selected' : '' }}>💊 Medicamento</option>
                <option value="Alimento"    {{ request('categoria') === 'Alimento'    ? 'selected' : '' }}>🥗 Alimento</option>
                <option value="Accesorio"   {{ request('categoria') === 'Accesorio'   ? 'selected' : '' }}>🎾 Accesorio</option>
                <option value="Higiene"     {{ request('categoria') === 'Higiene'     ? 'selected' : '' }}>🧴 Higiene</option>
                <option value="Vacuna"      {{ request('categoria') === 'Vacuna'      ? 'selected' : '' }}>💉 Vacuna</option>
                <option value="Suplemento"  {{ request('categoria') === 'Suplemento'  ? 'selected' : '' }}>🌿 Suplemento</option>
                <option value="Otro"        {{ request('categoria') === 'Otro'        ? 'selected' : '' }}>📦 Otro</option>
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

                                    {{-- Botón Editar (Solo Admin) --}}
                                    @if(auth()->check() && auth()->user()->isAdmin())
                                        <button type="button"
                                           @click="$dispatch('open-modal-editar-producto', { id: {{ $producto->id }} })"
                                           title="Editar ficha del producto"
                                           class="group/btn p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-150">
                                            <svg class="w-4 h-4" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square" stroke-miterlimit="10" style="overflow: visible;">
                                                <g class="pen-group">
                                                    <path class="pen-slash" d="M20 6 L26 12" />
                                                    <path class="pen-body" d="m10.5,27.5l-8,2 2-8L22.257,3.743c1.657-1.657,4.343-1.657,6,0s1.657,4.343,0,6L10.5,27.5Z" />
                                                </g>
                                            </svg>
                                        </button>
                                    @endif

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

    {{-- Modal NUEVO PRODUCTO --}}
    <div x-data="{ 
            open: false,
            nombre: '{{ old('nombre', '') }}',
            categoria: '{{ old('categoria', '') }}',
            codigo: '{{ old('codigo', '') }}',
            precio: {{ old('precio', '0') }},
            stock: {{ old('stock', '0') }},
            get emojisCategoria() {
                return {
                    'Medicamento': '💊', 'Alimento': '🥗', 'Accesorio': '🎾',
                    'Higiene': '🧴', 'Vacuna': '💉', 'Suplemento': '🌿', 'Otro': '📦'
                };
            },
            async fetchNextSku() {
                if (!this.categoria) {
                    this.codigo = '';
                    return;
                }
                try {
                    const r = await fetch(`/api/productos/next-sku?categoria=${this.categoria}`);
                    const data = await r.json();
                    this.codigo = data.sku;
                } catch(e) { console.error('Error fetching sku', e); }
            }
         }"
         x-init="@if($errors->any()) open = true; @endif"
         x-cloak
         x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[80] flex items-center justify-center p-4"
         @keydown.escape.window="open = false"
         @open-modal-nuevo-producto.window="open = true">

        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative w-full bg-white rounded-2xl shadow-2xl overflow-y-auto"
             style="max-width: 640px; max-height: 90vh;"
             @click.stop>

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-5 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-2xl">📦</div>
                    <div>
                        <h3 class="text-white font-bold text-lg leading-tight">Registrar Producto</h3>
                        <p class="text-amber-100 text-xs">Completa los datos del producto</p>
                    </div>
                </div>
                <button @click="open = false" type="button" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('productos.store') }}" class="p-6 space-y-5">
                @csrf

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nombre del Producto <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="nombre" name="nombre" x-model="nombre"
                           placeholder="Ej: Amoxicilina 250mg, Royal Canin Adult..." required
                           class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent {{ $errors->has('nombre') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                    @error('nombre')
                        <p class="mt-1.5 text-xs text-rose-600 flex items-center gap-1">{{ $message }}</p>
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
                            <input type="text" id="codigo" name="codigo" x-model="codigo" placeholder="Generado automáticamente" readonly tabindex="-1"
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all font-mono focus:outline-none bg-slate-100 text-slate-500 cursor-not-allowed {{ $errors->has('codigo') ? 'border-rose-400' : 'border-slate-200' }}">
                        </div>
                        @error('codigo')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="categoria" class="block text-sm font-semibold text-slate-700 mb-1.5">Categoría <span class="text-rose-500">*</span></label>
                        <select id="categoria" name="categoria" x-model="categoria" @change="fetchNextSku()" required
                                class="w-full px-4 py-2.5 text-sm border rounded-xl transition-all appearance-none focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent {{ $errors->has('categoria') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                            <option value="">— Selecciona categoría —</option>
                            <option value="Medicamento">💊 Medicamento</option>
                            <option value="Alimento">🥗 Alimento</option>
                            <option value="Accesorio">🎾 Accesorio</option>
                            <option value="Higiene">🧴 Higiene</option>
                            <option value="Vacuna">💉 Vacuna</option>
                            <option value="Suplemento">🌿 Suplemento</option>
                            <option value="Otro">📦 Otro</option>
                        </select>
                        @error('categoria')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Precio y Stock -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="precio" class="block text-sm font-semibold text-slate-700 mb-1.5">Precio (MXN) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm">$</span>
                            <input type="number" id="precio" name="precio" x-model="precio" placeholder="0.00" min="0" step="0.01" required
                                   class="w-full pl-7 pr-4 py-2.5 text-sm border rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent {{ $errors->has('precio') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        </div>
                        @error('precio')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-semibold text-slate-700 mb-1.5">Stock Inicial <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                            <input type="number" id="stock" name="stock" x-model="stock" placeholder="0" min="0" required
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent {{ $errors->has('stock') ? 'border-rose-400 bg-rose-50' : 'border-slate-200 bg-slate-50 hover:border-slate-300' }}">
                        </div>
                        @error('stock')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
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
                        <textarea id="descripcion" name="descripcion" rows="3" placeholder="Dosis, indicaciones, especies compatibles, composición..."
                                  class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all resize-none focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">{{ old('descripcion') }}</textarea>
                    </div>
                </div>

                <!-- Preview -->
                <div x-show="nombre.trim() !== ''" class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-amber-700 mb-2 uppercase tracking-wide">Vista previa</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-200 flex items-center justify-center text-xl" x-text="emojisCategoria[categoria] || '📦'"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-800" x-text="nombre || '—'"></p>
                            <p class="text-xs text-slate-500" x-text="categoria || 'Sin categoría'"></p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-sm font-bold text-amber-700" x-text="'$' + (parseFloat(precio) || 0).toFixed(2)"></p>
                            <p class="text-xs text-slate-500" x-text="(parseInt(stock) || 0) + ' unidades'"></p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-slate-100 pt-4 flex justify-end gap-3">
                    <button type="button" @click="open = false"
                            class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
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

    {{-- Modal EDITAR PRODUCTO --}}
    <div x-data="{ 
            open: false,
            id: null,
            nombre: '',
            categoria: '',
            codigo: '',
            precio: 0,
            stock: 0,
            descripcion: '',
            get emojisCategoria() {
                return {
                    'Medicamento': '💊', 'Alimento': '🥗', 'Accesorio': '🎾',
                    'Higiene': '🧴', 'Vacuna': '💉', 'Suplemento': '🌿', 'Otro': '📦'
                };
            }
         }"
         x-cloak
         x-show="open"
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[80] flex items-center justify-center p-4"
         @keydown.escape.window="open = false"
         @open-modal-editar-producto.window="
            fetch('/productos/' + $event.detail.id, { headers: { 'Accept': 'application/json' } })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        const p = data.producto;
                        id = p.id;
                        nombre = p.nombre || '';
                        categoria = p.categoria ? (p.categoria.charAt(0).toUpperCase() + p.categoria.slice(1).toLowerCase()) : '';
                        codigo = p.codigo || '';
                        precio = p.precio || 0;
                        stock = p.stock || 0;
                        descripcion = p.descripcion || '';
                        open = true;
                    }
                });
         ">

        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="open = false"></div>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 scale-95"
             class="relative w-full bg-white rounded-2xl shadow-2xl overflow-y-auto"
             style="max-width: 640px; max-height: 90vh;"
             @click.stop>

            <!-- Card Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-5 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-white font-bold text-lg leading-tight">Editar Producto</h3>
                        <p class="text-amber-100 text-xs">Modifica los datos del producto</p>
                    </div>
                </div>
                <button @click="open = false" type="button" class="text-white/70 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Form -->
            <form method="POST" :action="'{{ url('productos') }}/' + id" class="p-6 space-y-5">
                @csrf
                @method('PUT')

                <!-- Hidden fields for disabled inputs -->
                <input type="hidden" name="codigo" :value="codigo">
                <input type="hidden" name="categoria" :value="categoria">

                <!-- Nombre -->
                <div>
                    <label for="edit_nombre" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Nombre del Producto <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="edit_nombre" name="nombre" x-model="nombre"
                           placeholder="Ej: Amoxicilina 250mg, Royal Canin Adult..." required
                           class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">
                </div>

                <!-- Código y Categoría -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_codigo" class="block text-sm font-semibold text-slate-700 mb-1.5">Código / SKU</label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <input type="text" id="edit_codigo" x-model="codigo" disabled
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl transition-all font-mono focus:outline-none bg-slate-100 text-slate-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label for="edit_categoria" class="block text-sm font-semibold text-slate-700 mb-1.5">Categoría</label>
                        <select id="edit_categoria" x-model="categoria" disabled
                                class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl transition-all appearance-none focus:outline-none bg-slate-100 text-slate-500 cursor-not-allowed">
                            <option value="">— Selecciona categoría —</option>
                            <option value="Medicamento">💊 Medicamento</option>
                            <option value="Alimento">🥗 Alimento</option>
                            <option value="Accesorio">🎾 Accesorio</option>
                            <option value="Higiene">🧴 Higiene</option>
                            <option value="Vacuna">💉 Vacuna</option>
                            <option value="Suplemento">🌿 Suplemento</option>
                            <option value="Otro">📦 Otro</option>
                        </select>
                    </div>
                </div>

                <!-- Precio y Stock -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_precio" class="block text-sm font-semibold text-slate-700 mb-1.5">Precio (MXN) <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm">$</span>
                            <input type="number" id="edit_precio" name="precio" x-model="precio" min="0" step="0.01" required
                                   class="w-full pl-7 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">
                        </div>
                    </div>

                    <div>
                        <label for="edit_stock" class="block text-sm font-semibold text-slate-700 mb-1.5">Stock Actual <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"/>
                            </svg>
                            <input type="number" id="edit_stock" name="stock" x-model="stock" min="0" required
                                   class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300">
                        </div>
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label for="edit_descripcion" class="block text-sm font-semibold text-slate-700 mb-1.5">Descripción</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-3 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <textarea id="edit_descripcion" name="descripcion" x-model="descripcion" rows="3" placeholder="Dosis, indicaciones, especies compatibles, composición..."
                                  class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 bg-slate-50 rounded-xl transition-all resize-none focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent hover:border-slate-300"></textarea>
                    </div>
                </div>

                <!-- Preview -->
                <div x-show="nombre.trim() !== ''" class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-xs font-semibold text-amber-700 mb-2 uppercase tracking-wide">Vista previa</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-200 flex items-center justify-center text-xl" x-text="emojisCategoria[categoria] || '📦'"></div>
                        <div>
                            <p class="text-sm font-bold text-slate-800" x-text="nombre || '—'"></p>
                            <p class="text-xs text-slate-500" x-text="categoria || 'Sin categoría'"></p>
                        </div>
                        <div class="ml-auto text-right">
                            <p class="text-sm font-bold text-amber-700" x-text="'$' + (parseFloat(precio) || 0).toFixed(2)"></p>
                            <p class="text-xs text-slate-500" x-text="(parseInt(stock) || 0) + ' unidades'"></p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="border-t border-slate-100 pt-4 flex justify-end gap-3">
                    <button type="button" @click="open = false"
                            class="px-5 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 active:scale-95 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
