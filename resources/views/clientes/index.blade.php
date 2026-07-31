<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Clientes</h2>
                <p class="text-sm text-slate-500 mt-0.5">Gestión de propietarios y sus mascotas</p>
            </div>
            <a href="{{ route('clientes.create') }}"
               id="btn-nuevo-cliente"
               class="ml-4 mt-2 inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Cliente
            </a>
        </div>
    </x-slot>

    <!-- Search & Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('clientes.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text"
                       id="buscar-cliente"
                       name="buscar"
                       value="{{ request('buscar') }}"
                       placeholder="Buscar por nombre, email o teléfono..."
                       class="w-full pl-10 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-slate-50 transition-all">
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-xl w-32 transition-colors">
                Buscar
            </button>
            @if(request('buscar'))
                <a href="{{ route('clientes.index') }}"
                   class="px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors text-center">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    <!-- Clients Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        @if($clientes->isNotEmpty())
            <!-- Table Header with count -->
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando <span class="text-slate-800 font-bold">{{ $clientes->count() }}</span> de
                    <span class="text-slate-800 font-bold">{{ $clientes->total() }}</span> clientes
                </p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                        <th class="text-left px-6 py-3.5 font-semibold">Cliente</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden md:table-cell">Teléfono</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden lg:table-cell">Dirección</th>
                        <th class="text-center px-6 py-3.5 font-semibold hidden md:table-cell">Mascotas</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden xl:table-cell">Registro</th>
                        <th class="px-6 py-3.5 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($clientes as $cliente)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                                         style="background: linear-gradient(135deg, #10b981, #059669)">
                                        {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $cliente->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600 hidden md:table-cell">
                                {{ $cliente->telefono ?? '—' }}
                            </td>
                            <td class="px-6 py-4 hidden lg:table-cell">
                                <span class="text-slate-600 truncate max-w-xs block" title="{{ $cliente->direccion }}">
                                    {{ $cliente->direccion ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center hidden md:table-cell">
                                <a href="{{ route('mascotas.index', ['cliente' => $cliente->id]) }}"
                                   class="inline-flex items-center gap-1 bg-sky-50 hover:bg-sky-100 text-sky-700 text-xs font-semibold px-3 py-1 rounded-full transition-colors">
                                    🐾 {{ $cliente->mascotas_count ?? $cliente->mascotas->count() }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-500 text-xs hidden xl:table-cell">
                                {{ $cliente->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <!-- Ver -->
                                    <a href="{{ route('clientes.show', $cliente) }}"
                                       title="Ver detalle"
                                       class="p-1.5 text-slate-400 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <!-- Editar -->
                                    <a href="{{ route('clientes.edit', $cliente) }}"
                                       title="Editar"
                                       class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <!-- Eliminar -->
                                    <form method="POST" action="{{ route('clientes.destroy', $cliente) }}"
                                          onsubmit="return confirm('¿Estás seguro de eliminar a {{ $cliente->nombre }} {{ $cliente->apellido }}? Esta acción no se puede deshacer.')">
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
                                    <span class="text-6xl mb-4">🐾</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">
                                        {{ request('buscar') ? 'No se encontraron resultados' : 'No hay clientes registrados' }}
                                    </h3>
                                    <p class="text-sm text-slate-400 mb-5">
                                        {{ request('buscar') ? 'Intenta con otra búsqueda.' : 'Comienza registrando tu primer cliente.' }}
                                    </p>
                                    @if(!request('buscar'))
                                        <a href="{{ route('clientes.create') }}"
                                           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Registrar Primer Cliente
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
        @if($clientes->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $clientes->links() }}
            </div>
        @endif
    </div>

</x-app-layout>
