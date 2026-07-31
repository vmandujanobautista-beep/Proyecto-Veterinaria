<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Mascotas</h2>
                <p class="text-sm text-slate-500 mt-0.5">Gestión de pacientes de la clínica</p>
            </div>
            <a href="{{ route('mascotas.create') }}"
               id="btn-nueva-mascota"
               class=" ml-4 mt-2 inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nueva Mascota
            </a>
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
                    class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl w-32 transition-colors">
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
                            $colorGradient = match($mascota->especie) {
                                'Perro'  => 'from-amber-400 to-orange-500',
                                'Gato'   => 'from-purple-400 to-violet-600',
                                'Ave'    => 'from-emerald-400 to-teal-600',
                                'Conejo' => 'from-pink-400 to-rose-500',
                                default  => 'from-sky-400 to-blue-600',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0 bg-gradient-to-br {{ $colorGradient }}">
                                        {{ $especieEmoji }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $mascota->nombre }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5 md:hidden">{{ $mascota->especie }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-slate-700 font-medium">{{ $mascota->especie }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $mascota->raza ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                @if($mascota->cliente)
                                    <a href="{{ route('clientes.show', $mascota->cliente) }}"
                                       class="text-sm text-sky-600 hover:underline font-medium">
                                        {{ $mascota->cliente->nombre }} {{ $mascota->cliente->apellido }}
                                    </a>
                                @else
                                    <span class="text-slate-300">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center hidden md:table-cell">
                                @if($mascota->sexo)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                                        {{ $mascota->sexo === 'Macho' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                                        {{ $mascota->sexo === 'Macho' ? '♂' : '♀' }} {{ $mascota->sexo }}
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
                                    <a href="{{ route('mascotas.show', $mascota) }}"
                                       title="Ver detalle"
                                       class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <!-- Editar -->
                                    <a href="{{ route('mascotas.edit', $mascota) }}"
                                       title="Editar"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <!-- Eliminar -->
                                    <form method="POST" action="{{ route('mascotas.destroy', $mascota) }}"
                                          onsubmit="return confirm('¿Eliminar a {{ $mascota->nombre }}? Esta acción no se puede deshacer.')">
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
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-6xl mb-4">🐾</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request('buscar') || request('especie') ? 'No se encontraron resultados' : 'No hay mascotas registradas' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request('buscar') || request('especie') ? 'Intenta con otra búsqueda o filtro.' : 'Comienza registrando la primera mascota.' }}
                                    </p>
                                    @if(!request('buscar') && !request('especie'))
                                        <a href="{{ route('mascotas.create') }}"
                                           class="inline-flex items-center gap-2 bg-sky-600 hover:bg-sky-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Registrar Primera Mascota
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mascotas->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $mascotas->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
