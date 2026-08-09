<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Dashboard</h2>
            <p class="text-sm text-slate-500 mt-0.5">Resumen general de la clínica</p>
        </div>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

        <!-- Clientes -->
        <a href="{{ route('clientes.index') }}"
           class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full font-medium">+{{ $clientesEsteMes ?? 0 }} este mes</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $totalClientes ?? 0 }}</p>
            <p class="text-sm text-slate-500 mt-1">Clientes Registrados</p>
        </a>

        <!-- Mascotas -->
        <a href="{{ route('mascotas.index') }}"
           class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-sky-100 flex items-center justify-center text-2xl">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Cojinetes de los dedos -->
                    <path d="M12 4.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <path d="M18.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <path d="M5.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <!-- Cojinete principal (palma) -->
                    <path d="M12 11.5c-2.5 0-4.5 1.5-5.5 3.5-.5 1-1 2-1 3.5 0 2.5 2.5 4.5 6.5 4.5s6.5-2 6.5-4.5c0-1.5-.5-2.5-1-3.5-1-2-3-3.5-5.5-3.5z"/>
                    </svg>
                </div>
                <span class="text-xs text-sky-600 bg-sky-50 px-2 py-1 rounded-full font-medium">+{{ $mascotasEsteMes ?? 0 }} este mes</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $totalMascotas ?? 0 }}</p>
            <p class="text-sm text-slate-500 mt-1">Mascotas Registradas</p>
        </a>

        <!-- Citas Hoy -->
        <a href="{{ route('citas.index') }}"
           class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs text-violet-600 bg-violet-50 px-2 py-1 rounded-full font-medium">Hoy</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $citasHoy ?? 0 }}</p>
            <p class="text-sm text-slate-500 mt-1">Citas Programadas</p>
        </a>

        <!-- Ventas del día -->
        <a href="{{ route('ventas.index') }}"
           class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 transition-all duration-300 cursor-pointer">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded-full font-medium">Hoy</span>
            </div>
            <p class="text-3xl font-bold text-slate-800">${{ number_format($ventasHoy ?? 0, 2) }}</p>
            <p class="text-sm text-slate-500 mt-1">Ingresos del Día</p>
        </a>
    </div>

    <!-- Grid 2 columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Próximas Citas -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Próximas Citas
                </h3>
                <a href="{{ route('citas.index') }}" class="text-xs text-sky-600 hover:underline font-medium">Ver todas →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($proximasCitas ?? [] as $cita)
                    <div @click="$dispatch('ver-cita', { id: {{ $cita->id }} })" class="flex items-center gap-4 px-6 py-3 hover:bg-slate-50 transition-colors cursor-pointer">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-violet-400 to-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($cita->mascota->nombre ?? 'M', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">
                                {{ $cita->mascota->nombre ?? '—' }}
                                <span class="text-slate-400 font-normal">· {{ $cita->cliente->nombre ?? '' }}</span>
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $cita->motivo ?? 'Consulta general' }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-semibold text-slate-700">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('H:i') }}</p>
                            <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($cita->fecha_hora)->format('d/m') }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <span class="text-5xl mb-3">📅</span>
                        <p class="text-sm text-slate-500">No hay citas próximas programadas.</p>
                        <a href="{{ route('citas.create') }}" class="mt-3 text-sm text-sky-600 hover:underline font-medium">Agendar una cita →</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                Acciones Rápidas
            </h3>
            <div class="space-y-3">
                <button type="button" @click="$dispatch('nuevo-cliente', { redirect: '{{ route('clientes.index') }}' })"
                   class="w-full text-left flex items-center gap-3 p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-emerald-800">Nuevo Cliente</p>
                        <p class="text-xs text-emerald-600">Registrar propietario</p>
                    </div>
                </button>


                <button type="button" @click="$dispatch('agendar-cita', { redirect: '{{ route('citas.index') }}' })"
                   class="w-full text-left flex items-center gap-3 p-3 rounded-xl bg-violet-50 hover:bg-violet-100 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-violet-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-violet-800">Nueva Cita</p>
                        <p class="text-xs text-violet-600">Agendar consulta</p>
                    </div>
                </button>

                <a href="{{ route('ventas.create') }}"
                   class="w-full text-left flex items-center gap-3 p-3 rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors group">
                    <div class="w-9 h-9 rounded-lg bg-amber-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-amber-800">Nueva Venta</p>
                        <p class="text-xs text-amber-600">Registrar venta</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Últimos clientes registrados -->
    <div class="mt-5 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Últimos Clientes
            </h3>
            <a href="{{ route('clientes.index') }}" class="text-xs text-sky-600 hover:underline font-medium">Ver todos →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="text-left px-6 py-3 font-semibold">Cliente</th>
                        <th class="text-left px-6 py-3 font-semibold hidden md:table-cell">Teléfono</th>
                        <th class="text-left px-6 py-3 font-semibold hidden lg:table-cell">Mascotas</th>
                        <th class="text-left px-6 py-3 font-semibold">Registrado</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($ultimosClientes ?? [] as $cliente)
                        <tr class="hover:bg-slate-50 transition-colors cursor-pointer" @click="$dispatch('ver-cliente', { id: {{ $cliente->id }} })">
                            <td class="px-6 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($cliente->nombre, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-slate-800">{{ $cliente->nombre }} {{ $cliente->apellido }}</p>
                                        <p class="text-xs text-slate-500">{{ $cliente->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-slate-600 hidden md:table-cell">{{ $cliente->telefono ?? '—' }}</td>
                            <td class="px-6 py-3 hidden lg:table-cell">
                                <span class="inline-flex items-center gap-1 text-xs bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full font-medium">
                                    🐾 {{ $cliente->mascotas_count ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-slate-500 text-xs">{{ $cliente->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-3">
                                <button type="button" @click.stop="$dispatch('ver-cliente', { id: {{ $cliente->id }} })"
                                   class="text-xs text-sky-600 hover:text-sky-800 font-medium">Ver →</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                No hay clientes registrados aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
</x-app-layout>
