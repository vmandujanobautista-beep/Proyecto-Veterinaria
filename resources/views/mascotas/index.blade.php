<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Mascotas</h2>
                <p class="text-sm text-slate-500 mt-0.5">Gestión de pacientes de la clínica</p>
            </div>

        </div>
    </x-slot>

    <!-- Search & Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('mascotas.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="buscar-mascota"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por nombre, especie o raza..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent bg-slate-50 transition-all">
            </div>



            <!-- Filtro especie -->
            <select id="filtro-especie"
                    name="especie"
                    class="px-5 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-sky-500 bg-slate-50 text-slate-700 w-full sm:w-56 transition-all">
                <option value="">Todas las especies</option>
                <option value="Perro"   {{ request('especie') === 'Perro'   ? 'selected' : '' }}>🐶 Perro</option>
                <option value="Gato"    {{ request('especie') === 'Gato'    ? 'selected' : '' }}>🐱 Gato</option>
                <option value="Ave"     {{ request('especie') === 'Ave'     ? 'selected' : '' }}>🦜 Ave</option>
                <option value="Conejo"  {{ request('especie') === 'Conejo'  ? 'selected' : '' }}>🐰 Conejo</option>
                <option value="Reptil"  {{ request('especie') === 'Reptil'  ? 'selected' : '' }}>🦎 Reptil</option>
                <option value="Otro"    {{ request('especie') === 'Otro'    ? 'selected' : '' }}>Otro</option>
            </select>

            <button type="submit"
                    class="btn-blue-pulse px-5 py-2.5 text-sm font-medium w-32 shadow-sm active:scale-95">
                Buscar
            </button>
            @if(request('buscar') || request('especie'))
                <a href="{{ route('mascotas.index') }}"
                   class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Mascotas Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        @if($mascotas->isNotEmpty())
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando <span class="text-slate-800 font-bold">{{ $mascotas->count() }}</span> de
                    <span class="text-slate-800 font-bold">{{ $mascotas->total() }}</span> mascotas
                </p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                        <th class="text-left px-6 py-3.5 font-semibold">Mascota</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden md:table-cell">Especie / Raza</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden lg:table-cell">Propietario</th>
                        <th class="text-center px-6 py-3.5 font-semibold hidden md:table-cell">Sexo</th>
                        <th class="text-center px-6 py-3.5 font-semibold hidden xl:table-cell">Peso</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden xl:table-cell">Nacimiento</th>
                        <th class="px-6 py-3.5 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($mascotas as $mascota)
                        @php
                            $especieEmoji = match($mascota->especie) {
                                'Perro'  => '🐶',
                                'Gato'   => '🐱',
                                'Ave'    => '🦜',
                                'Conejo' => '🐰',
                                'Reptil' => '🦎',
                                default  => '🐾',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0 bg-blue-500">
                                        {{ $especieEmoji }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $mascota->nombre }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5 md:hidden capitalize">{{ $mascota->especie }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-slate-700 font-medium capitalize">{{ $mascota->especie }}</p>
                                <p class="text-xs text-slate-500 mt-0.5 capitalize">{{ $mascota->raza ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                @if($mascota->cliente)
                                    <button type="button" @click="$dispatch('ver-cliente', { id: {{ $mascota->cliente->id }} })"
                                       class="text-sm text-emerald-600 hover:underline font-medium text-left">
                                        {{ $mascota->cliente->nombre }} {{ $mascota->cliente->apellido }}
                                    </button>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center hidden md:table-cell">
                                @if($mascota->sexo)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full text-white capitalize
                                        {{ strtolower($mascota->sexo) === 'macho' ? 'bg-blue-500' : 'bg-pink-500' }}">
                                        {{ strtolower($mascota->sexo) === 'macho' ? '♂' : '♀' }} {{ $mascota->sexo }}
                                    </span>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center hidden xl:table-cell">
                                @if($mascota->peso)
                                    <span class="text-sm font-semibold text-slate-700">{{ number_format($mascota->peso, 1) }}</span>
                                    <span class="text-xs text-slate-400"> kg</span>
                                @else
                                    <span class="text-slate-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-500 hidden xl:table-cell">
                                @if($mascota->fecha_nacimiento)
                                    {{ $mascota->fecha_nacimiento->format('d/m/Y') }}
                                    <p class="text-slate-400 mt-0.5">{{ $mascota->fecha_nacimiento->age }} años</p>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Ver -->
                                    <button type="button" @click="$dispatch('ver-mascota', { id: {{ $mascota->id }} })"
                                       title="Ver detalle"
                                       class="group/btn p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <!-- Pupil -->
                                            <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"
                                                class="transition-transform duration-150 ease-out origin-center group-hover/btn:scale-75" />
                                            <!-- Eye shape -->
                                            <path
                                                d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"
                                                class="transition-transform duration-150 ease-out origin-center group-hover/btn:scale-y-90" />
                                        </svg>
                                    </button>
                                    <!-- Editar -->
                                    <button type="button" @click="$dispatch('editar-mascota', { id: {{ $mascota->id }} })"
                                        title="Editar"
                                        class="group/edit p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors relative overflow-visible">
                                        <svg class="w-4 h-4 overflow-visible" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="square" stroke-miterlimit="10">
                                            <g class="pen-group" style="transform-origin: 50% 50%;">
                                                <path
                                                    class="pen-slash opacity-0 transition-opacity duration-300 group-hover/edit:opacity-100"
                                                    d="M20 6 L26 12" />
                                                <path class="pen-body"
                                                    d="m10.5,27.5l-8,2 2-8L22.257,3.743c1.657-1.657,4.343-1.657,6,0s1.657,4.343,0,6L10.5,27.5Z" />
                                            </g>
                                        </svg>
                                    </button>
                                    <!-- Eliminar -->
                                    <form method="POST" action="{{ route('mascotas.destroy', $mascota) }}"
                                          onsubmit="return confirm('¿Eliminar a {{ $mascota->nombre }}? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Eliminar"
                                            class="group/del p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 7l16 0"
                                                    class="transition-transform duration-200 ease-out origin-bottom group-hover/del:-rotate-[25deg] group-hover/del:-translate-y-1" />
                                                <path d="M10 11l0 6" />
                                                <path d="M14 11l0 6" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"
                                                    class="transition-transform duration-200 ease-out origin-bottom group-hover/del:-rotate-[35deg] group-hover/del:-translate-y-1.5 group-hover/del:-translate-x-0.5" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-6xl mb-4">🐾</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request('buscar') || request('especie') ? 'No se encontraron resultados' : 'No hay mascotas registradas' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request('buscar') || request('especie') ? 'Intenta con otra búsqueda o filtro.' : 'Comienza registrando la primera mascota.' }}
                                    </p>

                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mascotas->hasPages())
            <div class="px-6 py-8 border-t border-slate-100 flex justify-center">
                {{ $mascotas->links('vendor.pagination.uiverse-navy') }}
            </div>
        @endif
    </div>

</x-app-layout>
