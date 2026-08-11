<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Reportes</h2>
            <p class="text-sm text-slate-500 mt-0.5">Analiza el rendimiento de la clínica por periodo</p>
        </div>
    </x-slot>

    <div x-data="reportesApp()" x-init="cargar()">

        {{-- Filtros de fecha --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
            <div class="flex flex-wrap gap-3 items-end">
                {{-- Accesos rápidos --}}
                <div class="flex gap-2">
                    <button type="button" @click="aplicarPeriodo('hoy')"
                            :class="periodo === 'hoy' ? 'bg-blue-600 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50'"
                            class="px-3 py-2 text-sm font-medium rounded-xl transition-all">Hoy</button>
                    <button type="button" @click="aplicarPeriodo('semana')"
                            :class="periodo === 'semana' ? 'bg-blue-600 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50'"
                            class="px-3 py-2 text-sm font-medium rounded-xl transition-all">Esta semana</button>
                    <button type="button" @click="aplicarPeriodo('mes')"
                            :class="periodo === 'mes' ? 'bg-blue-600 text-white' : 'border border-slate-200 text-slate-600 hover:bg-slate-50'"
                            class="px-3 py-2 text-sm font-medium rounded-xl transition-all">Este mes</button>
                </div>
                <div class="flex items-center gap-2">
                    <div>
                        <label class="text-xs text-slate-500 block mb-0.5">Desde</label>
                        <input type="date" x-model="desde" @change="periodo = 'custom'"
                               class="px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                    </div>
                    <div>
                        <label class="text-xs text-slate-500 block mb-0.5">Hasta</label>
                        <input type="date" x-model="hasta" @change="periodo = 'custom'"
                               class="px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                    </div>
                    <button type="button" @click="cargar()" class="mt-4 px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" :class="cargando ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Actualizar
                    </button>
                </div>
            </div>
        </div>

        {{-- Loading state --}}
        <div x-show="cargando" class="flex flex-col items-center justify-center py-20">
            <svg class="animate-spin w-10 h-10 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <p class="text-slate-500 text-sm">Cargando reportes...</p>
        </div>

        <div x-show="!cargando" x-cloak>
            {{-- Tarjetas de resumen --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Ingresos del periodo</p>
                    <p class="text-2xl font-bold text-slate-800" x-text="'$' + Number(datos.ventasTotales).toLocaleString('es-MX', {minimumFractionDigits:2})">$0.00</p>
                    <p class="text-xs text-slate-400 mt-1" x-text="datos.ventasCount + ' ventas'"></p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Citas realizadas</p>
                    <p class="text-2xl font-bold text-emerald-600" x-text="(datos.citasPorEstado?.completada ?? 0) + (datos.citasPorEstado?.confirmada ?? 0)">0</p>
                    <p class="text-xs text-rose-400 mt-1" x-text="(datos.citasPorEstado?.cancelada ?? 0) + ' canceladas'"></p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Clientes nuevos</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="datos.clientesNuevos ?? 0">0</p>
                    <p class="text-xs text-slate-400 mt-1" x-text="(datos.mascotasNuevas ?? 0) + ' mascotas registradas'"></p>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Productos bajo stock</p>
                    <p class="text-2xl font-bold text-amber-600" x-text="datos.productosBajos?.length ?? 0">0</p>
                    <p class="text-xs text-slate-400 mt-1">≤ 5 unidades en inventario</p>
                </div>
            </div>

            {{-- Estado de citas por tipo --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Citas por estado</h3>
                    <div class="space-y-3">
                        <template x-for="[estado, color, label] in [['completada','emerald','Completadas'],['confirmada','blue','Confirmadas'],['pendiente','amber','Pendientes'],['cancelada','rose','Canceladas']]" :key="estado">
                            <div class="flex items-center gap-3">
                                <div class="w-2 h-2 rounded-full flex-shrink-0" :class="`bg-${color}-500`"></div>
                                <span class="text-sm text-slate-600 flex-1" x-text="label"></span>
                                <span class="text-sm font-bold text-slate-800" x-text="datos.citasPorEstado?.[estado] ?? 0"></span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Productos más vendidos --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Productos más vendidos</h3>
                    <div x-show="datos.productosVendidos?.length === 0" class="text-center py-6 text-slate-400 text-sm">Sin datos en este periodo</div>
                    <div class="space-y-2">
                        <template x-for="(p, i) in (datos.productosVendidos ?? [])" :key="i">
                            <div class="flex items-center gap-3 py-1">
                                <span class="text-xs text-slate-400 font-bold w-5 text-right" x-text="i+1"></span>
                                <span class="text-sm text-slate-700 flex-1 truncate" x-text="p.nombre"></span>
                                <span class="text-xs text-slate-500" x-text="p.total_vendido + ' uds.'"></span>
                                <span class="text-sm font-semibold text-slate-800" x-text="'$' + Number(p.total_ingresos).toLocaleString('es-MX', {minimumFractionDigits:2})"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Tabla de ventas --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-5 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-700">Últimas 50 ventas del periodo</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <th class="text-left px-5 py-3 font-semibold">#</th>
                                <th class="text-left px-5 py-3 font-semibold">Cliente</th>
                                <th class="text-left px-5 py-3 font-semibold">Usuario</th>
                                <th class="text-left px-5 py-3 font-semibold">Fecha</th>
                                <th class="text-center px-5 py-3 font-semibold">Estado</th>
                                <th class="text-right px-5 py-3 font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-if="datos.ventas?.length === 0">
                                <tr><td colspan="6" class="py-12 text-center text-slate-400 text-sm">Sin ventas en este periodo</td></tr>
                            </template>
                            <template x-for="venta in (datos.ventas ?? [])" :key="venta.id">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3 text-slate-500" x-text="'#' + venta.id"></td>
                                    <td class="px-5 py-3 text-slate-700" x-text="venta.cliente"></td>
                                    <td class="px-5 py-3 text-slate-500 text-xs" x-text="venta.usuario"></td>
                                    <td class="px-5 py-3 text-slate-500 text-xs" x-text="venta.fecha"></td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex text-xs px-2 py-0.5 rounded-full font-semibold"
                                              :class="{
                                                'bg-emerald-100 text-emerald-700': venta.estado === 'completada',
                                                'bg-amber-100 text-amber-700': venta.estado === 'pendiente',
                                                'bg-rose-100 text-rose-700': venta.estado === 'cancelada',
                                              }"
                                              x-text="venta.estado"></span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-slate-800" x-text="'$' + Number(venta.total).toLocaleString('es-MX', {minimumFractionDigits:2})"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Productos con stock bajo --}}
            <div x-show="datos.productosBajos?.length > 0" class="bg-white rounded-2xl shadow-sm border border-amber-100 mb-5 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-amber-100 bg-amber-50">
                    <h3 class="text-sm font-bold text-amber-800">⚠️ Productos con inventario bajo (≤ 5 unidades)</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    <template x-for="p in (datos.productosBajos ?? [])" :key="p.id">
                        <div class="flex items-center gap-4 px-5 py-3">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-700 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-slate-800" x-text="p.nombre"></p>
                                <p class="text-xs text-slate-400" x-text="p.categoria"></p>
                            </div>
                            <span class="text-sm font-bold text-rose-600" x-text="p.stock + ' uds.'"></span>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Actividad de usuarios --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-700">Actividad de usuarios en el periodo</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <th class="text-left px-5 py-3 font-semibold">Usuario</th>
                                <th class="text-left px-5 py-3 font-semibold">Rol</th>
                                <th class="text-center px-5 py-3 font-semibold">Ventas</th>
                                <th class="text-center px-5 py-3 font-semibold">Citas</th>
                                <th class="text-center px-5 py-3 font-semibold">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-for="u in (datos.actividadUsuarios ?? [])" :key="u.id">
                                <tr class="hover:bg-slate-50/80">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white font-bold text-xs" x-text="u.name.charAt(0).toUpperCase()"></div>
                                            <span class="text-sm text-slate-700" x-text="u.name"></span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold"
                                              :class="u.role === 'admin' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600'"
                                              x-text="u.role === 'admin' ? 'Admin' : 'Recepcionista'"></span>
                                    </td>
                                    <td class="px-5 py-3 text-center font-semibold text-slate-800" x-text="u.ventas_periodo"></td>
                                    <td class="px-5 py-3 text-center font-semibold text-slate-800" x-text="u.citas_periodo"></td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-semibold"
                                              :class="u.activo ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'"
                                              x-text="u.activo ? 'Activo' : 'Inactivo'"></span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function reportesApp() {
    const hoy = new Date().toISOString().slice(0, 10);
    const inicioMes = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().slice(0, 10);
    return {
        desde: inicioMes,
        hasta: hoy,
        periodo: 'mes',
        cargando: false,
        datos: {},

        aplicarPeriodo(p) {
            this.periodo = p;
            const hoyDate = new Date();
            if (p === 'hoy') {
                this.desde = this.hasta = hoyDate.toISOString().slice(0, 10);
            } else if (p === 'semana') {
                const dia = hoyDate.getDay() || 7;
                const inicioSemana = new Date(hoyDate);
                inicioSemana.setDate(hoyDate.getDate() - dia + 1);
                this.desde = inicioSemana.toISOString().slice(0, 10);
                this.hasta = hoyDate.toISOString().slice(0, 10);
            } else if (p === 'mes') {
                this.desde = new Date(hoyDate.getFullYear(), hoyDate.getMonth(), 1).toISOString().slice(0, 10);
                this.hasta = hoyDate.toISOString().slice(0, 10);
            }
            this.cargar();
        },

        async cargar() {
            this.cargando = true;
            try {
                const res = await fetch(`{{ route('admin.reportes.datos') }}?desde=${this.desde}&hasta=${this.hasta}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.datos = await res.json();
            } catch (e) {
                console.error('Error al cargar reportes:', e);
            } finally {
                this.cargando = false;
            }
        }
    };
}
</script>
