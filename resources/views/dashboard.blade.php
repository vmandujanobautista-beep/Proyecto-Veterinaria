@if(auth()->user()->isAdmin())
{{-- ═══════════════════════════════════════════════════════════════════
     DASHBOARD ADMINISTRADOR — Panel de Supervisión
═══════════════════════════════════════════════════════════════════ --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3 print:hidden">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Panel de Supervisión</h2>
                <p class="text-sm text-slate-500 mt-0.5">Vista general del rendimiento de la clínica</p>
            </div>
        </div>
    </x-slot>

    <style>
        .btn-gradient {
            border: none;
            color: #fff !important;
            border-radius: 0.75rem; /* rounded-xl para igualar los paneles */
            background-size: 100% auto;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        .btn-gradient:hover {
            background-position: right center;
            background-size: 200% auto;
        }
        
        .btn-red { background-image: linear-gradient(30deg, #b91c1c, #f87171); }
        .btn-red:hover { animation: pulse-red 1.5s infinite; }
        
        .btn-green { background-image: linear-gradient(30deg, #15803d, #4ade80); }
        .btn-green:hover { animation: pulse-green 1.5s infinite; }
        
        .btn-blue { background-image: linear-gradient(30deg, #0400ff, #4ce3f7); }
        .btn-blue:hover { animation: pulse-blue 1.5s infinite; }

        @@keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        @@keyframes pulse-green {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }
        @@keyframes pulse-blue {
            0% { box-shadow: 0 0 0 0 rgba(5, 186, 218, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(5, 186, 218, 0); }
            100% { box-shadow: 0 0 0 0 rgba(5, 186, 218, 0); }
        }

        .btn-gradient:hover .file-animated-path {
            animation: draw-file 0.4s ease-out forwards;
        }
        @@keyframes draw-file {
            0% { stroke-dasharray: 20; stroke-dashoffset: 20; }
            100% { stroke-dasharray: 20; stroke-dashoffset: 0; }
        }

        /* ===== ESTILOS DE IMPRESION ===== */
        @@media print {
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            body { background: white !important; margin: 0; }
            .print\:hidden, .print-hidden { display: none !important; }
            .print\:block { display: block !important; }
            aside, nav, header { display: none !important; }
            main { padding: 0 !important; margin: 0 !important; }

            /* Mostrar cabecera PDF --*/
            #pdf-header { display: block !important; }

            /* Tarjetas stat */
            .stat-card {
                border: 1px solid #e2e8f0 !important;
                box-shadow: none !important;
                break-inside: avoid;
                page-break-inside: avoid;
            }
            /* Paneles */
            .bg-white {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
                break-inside: avoid;
            }
            canvas, table {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            /* Tabla de ventas con cabecera azul oscuro */
            thead tr {
                background: #1e3a8a !important;
                color: white !important;
            }
            thead th {
                color: white !important;
                font-weight: 700 !important;
                padding: 8px 12px !important;
            }
            tbody tr:nth-child(even) {
                background: #f1f5f9 !important;
            }
            /* Scroll oculto para imprimir */
            .print\:max-h-none { max-height: none !important; }
            .print\:overflow-visible { overflow: visible !important; }
        }
    </style>

    {{-- Chart.js CDN (solo Admin) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <div x-data="adminDashboard()" x-init="init()">

        {{-- ========== CABECERA PREMIUM PARA PDF (solo en impresión) ========== --}}
        <div id="pdf-header" style="display:none;" class="mb-5">
            {{-- Franja con degradado azul --}}
            <div style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 55%, #0ea5e9 100%); color: white; padding: 28px 32px 22px; border-radius: 12px 12px 0 0;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <div style="font-size: 9px; font-weight: 700; letter-spacing: 3px; text-transform: uppercase; opacity: 0.65; margin-bottom: 6px;">REPORTE OFICIAL — SISTEMA VETERINARIO</div>
                        <div style="font-size: 30px; font-weight: 900; letter-spacing: -1px; margin-bottom: 4px;">Reporte de Desempeño</div>
                        <div style="font-size: 14px; opacity: 0.8;">Clínica Veterinaria <strong>{{ $clinica_nombre ?? 'VetCare' }}</strong></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.35); border-radius: 10px; padding: 12px 18px; display: inline-block;">
                            <div style="font-size: 8px; letter-spacing: 2px; text-transform: uppercase; opacity: 0.65; margin-bottom: 3px;">PERÍODO DEL REPORTE</div>
                            <div style="font-size: 14px; font-weight: 800;" x-text="desde + ' → ' + hasta">—</div>
                        </div>
                        <div style="font-size: 10px; opacity: 0.55; margin-top: 10px;">Generado: {{ date('d/m/Y \a \l\a\s H:i') }}</div>
                    </div>
                </div>
            </div>
            {{-- Barra de métricas clave --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-top: 3px solid #2563eb; padding: 18px 32px; display: grid; grid-template-columns: repeat(4,1fr); gap: 0; border-radius: 0 0 12px 12px;">
                <div style="text-align: center; padding: 8px 16px; border-right: 1px solid #e2e8f0;">
                    <div style="font-size: 24px; font-weight: 900; color: #1e3a8a; margin-bottom: 4px;" x-text="'$' + Number(datos.ventasTotales ?? 0).toLocaleString('es-MX', {minimumFractionDigits:2})">—</div>
                    <div style="font-size: 9px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Ingresos del Periodo</div>
                </div>
                <div style="text-align: center; padding: 8px 16px; border-right: 1px solid #e2e8f0;">
                    <div style="font-size: 24px; font-weight: 900; color: #16a34a; margin-bottom: 4px;" x-text="datos.citasPeriodoActual ?? 0">—</div>
                    <div style="font-size: 9px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Citas del Periodo</div>
                </div>
                <div style="text-align: center; padding: 8px 16px; border-right: 1px solid #e2e8f0;">
                    <div style="font-size: 24px; font-weight: 900; color: #0369a1; margin-bottom: 4px;" x-text="datos.clientesNuevos ?? 0">—</div>
                    <div style="font-size: 9px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Clientes Nuevos</div>
                </div>
                <div style="text-align: center; padding: 8px 16px;">
                    <div style="font-size: 24px; font-weight: 900; color: #7c3aed; margin-bottom: 4px;" x-text="datos.ventasCount ?? 0">—</div>
                    <div style="font-size: 9px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Ventas Registradas</div>
                </div>
            </div>
        </div>

        {{-- ── FILTRO DE PERIODO ─────────────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5 print:hidden">
            <div class="flex flex-wrap gap-3 items-end">
                {{-- Botones rápidos --}}
                <div class="flex gap-2 flex-wrap">
                    <template x-for="btn in periodos" :key="btn.key">
                        <button type="button"
                            @click="aplicarPeriodo(btn.key)"
                            :class="periodo === btn.key
                                ? 'bg-blue-600 text-white shadow-sm'
                                : 'border border-slate-200 text-slate-600 hover:bg-slate-50'"
                            class="px-3 py-2 text-sm font-medium rounded-xl transition-all"
                            x-text="btn.label">
                        </button>
                    </template>
                </div>
                {{-- Rango personalizado --}}
                <div class="flex items-center gap-2 ml-auto flex-wrap">
                    <div x-show="periodo === 'personalizado'" class="flex items-center gap-2">
                        <div>
                            <label class="text-xs text-slate-500 block mb-0.5">Desde</label>
                            <input type="date" x-model="desde" @change="cargar()"
                                class="px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                        </div>
                        <div>
                            <label class="text-xs text-slate-500 block mb-0.5">Hasta</label>
                            <input type="date" x-model="hasta" @change="cargar()"
                                class="px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                        </div>
                    </div>
                    
                    <button type="button" @click="cargar()"
                        class="mt-4 px-5 py-2 text-sm font-medium flex items-center gap-2 group btn-gradient btn-blue">
                        <svg class="w-5 h-5 text-white transition-transform duration-500 ease-in-out group-hover:rotate-180" :class="cargando ? 'animate-spin' : ''" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                            <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                        </svg>
                        Actualizar
                    </button>

                    <button type="button" 
                        @click="descargarPDF()"
                        class="mt-4 px-5 py-2 text-sm font-medium flex items-center gap-2 group btn-gradient btn-red">
                        <svg class="w-5 h-5 text-white group-hover:animate-pulse" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" class="file-animated-path" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M9 17h6" class="file-animated-path" />
                            <path d="M9 13h6" class="file-animated-path" />
                        </svg>
                        <span>PDF</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- ── ESTADO DE CARGA ───────────────────────────────────────── --}}
        <div x-show="cargando" class="flex flex-col items-center justify-center py-20">
            <svg class="animate-spin w-10 h-10 text-blue-600 mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <p class="text-slate-500 text-sm">Cargando indicadores...</p>
        </div>

        {{-- ── ERROR ─────────────────────────────────────────────────── --}}
        <div x-show="errorMsg && !cargando" x-cloak
            class="mb-5 p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-center gap-3 text-rose-700 text-sm">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span x-text="errorMsg"></span>
        </div>

        <div x-show="!cargando && !errorMsg" x-cloak>

            {{-- ── TARJETAS SUPERIORES ───────────────────────────────── --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 print:grid-cols-2 gap-5 mb-5">

                {{-- Clientes --}}
                <a href="{{ route('clientes.index') }}"
                   class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 transition-all duration-300 cursor-pointer block">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full"
                            :class="variacion(datos.clientesNuevos, datos.clientesNuevosAnterior).cls"
                            x-text="variacion(datos.clientesNuevos, datos.clientesNuevosAnterior).txt">
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800" x-text="(datos.totalClientesGlobal ?? 0).toLocaleString('es-MX')">—</p>
                    <p class="text-sm text-slate-500 mt-1">Clientes Registrados</p>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="'+' + (datos.clientesNuevos ?? 0) + ' en el periodo'"></p>
                </a>

                {{-- Mascotas --}}
                <a href="{{ route('mascotas.index') }}"
                   class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 transition-all duration-300 cursor-pointer block">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-sky-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 4.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                                <path d="M18.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                                <path d="M5.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                                <path d="M12 11.5c-2.5 0-4.5 1.5-5.5 3.5-.5 1-1 2-1 3.5 0 2.5 2.5 4.5 6.5 4.5s6.5-2 6.5-4.5c0-1.5-.5-2.5-1-3.5-1-2-3-3.5-5.5-3.5z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full"
                            :class="variacion(datos.mascotasNuevas, datos.mascotasNuevasAnterior).cls"
                            x-text="variacion(datos.mascotasNuevas, datos.mascotasNuevasAnterior).txt">
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800" x-text="(datos.totalMascotasGlobal ?? 0).toLocaleString('es-MX')">—</p>
                    <p class="text-sm text-slate-500 mt-1">Mascotas Registradas</p>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="'+' + (datos.mascotasNuevas ?? 0) + ' en el periodo'"></p>
                </a>

                {{-- Citas del periodo --}}
                <a href="{{ route('citas.index') }}"
                   class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 transition-all duration-300 cursor-pointer block">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full"
                            :class="variacion(datos.citasPeriodoActual, datos.citasPeriodoAnterior).cls"
                            x-text="variacion(datos.citasPeriodoActual, datos.citasPeriodoAnterior).txt">
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800" x-text="(datos.citasPeriodoActual ?? 0).toLocaleString('es-MX')">—</p>
                    <p class="text-sm text-slate-500 mt-1">Citas del Periodo</p>
                    <p class="text-xs text-rose-400 mt-0.5" x-text="(datos.citasPorEstado?.cancelada ?? 0) + ' canceladas'"></p>
                </a>

                {{-- Ingresos del periodo --}}
                <a href="{{ route('ventas.index') }}"
                   class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-slate-100 transition-all duration-300 cursor-pointer block">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-medium px-2 py-1 rounded-full"
                            :class="variacion(datos.ventasTotales, datos.ventasTotalesAnterior).cls"
                            x-text="variacion(datos.ventasTotales, datos.ventasTotalesAnterior).txt">
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-slate-800"
                        x-text="'$' + Number(datos.ventasTotales ?? 0).toLocaleString('es-MX', {minimumFractionDigits:2})">—</p>
                    <p class="text-sm text-slate-500 mt-1">Ingresos del Periodo</p>
                    <p class="text-xs text-slate-400 mt-0.5" x-text="(datos.ventasCount ?? 0) + ' ventas registradas'"></p>
                </a>
            </div>

            {{-- ── GRÁFICOS FILA 1 — Ingresos y Citas por estado ────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 print:grid-cols-1 gap-5 mb-5">

                {{-- Gráfico: Ingresos por día --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 inline-block"></span>
                            Ingresos por día
                        </h3>
                    </div>
                    <div x-show="(datos.ventasPorDia ?? []).length === 0" class="flex flex-col items-center justify-center h-48 text-slate-400">
                        <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p class="text-sm">No hay datos para este periodo</p>
                    </div>
                    <div x-show="(datos.ventasPorDia ?? []).length > 0" class="relative h-48">
                        <canvas id="chartIngresos"></canvas>
                    </div>
                </div>

                {{-- Gráfico: Ventas por día --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                            Ventas por día
                        </h3>
                    </div>
                    <div x-show="(datos.ventasPorDia ?? []).length === 0" class="flex flex-col items-center justify-center h-48 text-slate-400">
                        <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        <p class="text-sm">No hay datos para este periodo</p>
                    </div>
                    <div x-show="(datos.ventasPorDia ?? []).length > 0" class="relative h-48">
                        <canvas id="chartVentas"></canvas>
                    </div>
                </div>

                {{-- Gráfico: Citas por Estado (dona) --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2 mb-4">
                        <span class="w-2.5 h-2.5 rounded-full bg-violet-500 inline-block"></span>
                        Citas por Estado
                    </h3>
                    <div x-show="Object.keys(datos.citasPorEstado ?? {}).length === 0" class="flex flex-col items-center justify-center h-48 text-slate-400">
                        <svg class="w-10 h-10 mb-2 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm">Sin citas en este periodo</p>
                    </div>
                    <div x-show="Object.keys(datos.citasPorEstado ?? {}).length > 0" class="relative h-48">
                        <canvas id="chartCitas"></canvas>
                    </div>
                    {{-- Leyenda manual --}}
                    <div class="mt-3 space-y-1.5" x-show="Object.keys(datos.citasPorEstado ?? {}).length > 0">
                        <template x-for="[key, label, color] in [
                            ['completada','Completadas','bg-emerald-500'],
                            ['confirmada','Confirmadas','bg-blue-500'],
                            ['pendiente','Pendientes','bg-amber-400'],
                            ['cancelada','Canceladas','bg-rose-400']
                        ]" :key="key">
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-1.5">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" :class="color"></span>
                                    <span class="text-slate-600" x-text="label"></span>
                                </div>
                                <span class="font-semibold text-slate-800" x-text="datos.citasPorEstado?.[key] ?? 0"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ── GRÁFICOS FILA 2 — Crecimiento, Stock, Actividad ──── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 print:grid-cols-1 gap-5 mb-5">

                {{-- Gráfico: Crecimiento Clientes y Mascotas --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2 mb-4">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                        Crecimiento (últimos 6 meses)
                    </h3>
                    <div class="relative h-40">
                        <canvas id="chartCrecimiento"></canvas>
                    </div>
                    <div class="flex items-center gap-4 mt-3 justify-center text-xs text-slate-500">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span> Clientes</span>
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-sky-500 inline-block"></span> Mascotas</span>
                    </div>
                </div>

                {{-- Tabla: Productos con Stock Bajo --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-100 bg-amber-50/60 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                            Inventario Bajo (< 10 uds.)
                        </h3>
                        <a href="{{ route('productos.index') }}" class="text-xs text-sky-600 hover:underline font-medium print:hidden">Ver todos →</a>
                    </div>
                    <div x-show="(datos.productosBajos ?? []).length === 0" class="flex flex-col items-center justify-center py-8 text-slate-400">
                        <svg class="w-8 h-8 mb-2 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-sm text-slate-500 font-medium">Inventario al día</p>
                        <p class="text-xs text-slate-400 mt-0.5">No hay productos con stock crítico</p>
                    </div>
                    <div class="divide-y divide-slate-50 max-h-52 overflow-y-auto print:max-h-none print:overflow-visible">
                        <template x-for="p in (datos.productosBajos ?? [])" :key="p.id">
                            <div class="flex items-center justify-between px-5 py-2.5 hover:bg-slate-50 transition-colors">
                                <div>
                                    <p class="text-sm font-medium text-slate-700" x-text="p.nombre"></p>
                                    <p class="text-xs text-slate-400" x-text="p.categoria"></p>
                                </div>
                                <span class="text-xs font-bold px-2.5 py-1 rounded-full"
                                    :class="p.stock === 0 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700'"
                                    x-text="p.stock + ' uds.'">
                                </span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Actividad: Últimos accesos de usuarios --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Actividad de Usuarios
                        </h3>
                        <a href="{{ route('admin.usuarios.index') }}" class="text-xs text-sky-600 hover:underline font-medium print:hidden">Gestionar →</a>
                    </div>
                    <div class="divide-y divide-slate-50 max-h-52 overflow-y-auto print:max-h-none print:overflow-visible">
                        <template x-for="u in (datos.actividadUsuarios ?? [])" :key="u.id">
                            <div class="px-5 py-2.5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-purple-600 flex items-center justify-center text-white font-bold text-xs flex-shrink-0"
                                        x-text="u.name.charAt(0).toUpperCase()"></div>
                                    <div>
                                        <div class="flex items-center gap-1.5">
                                            <p class="text-sm font-medium text-slate-700" x-text="u.name"></p>
                                            <span x-show="!u.activo" class="text-[10px] bg-rose-100 text-rose-600 px-1.5 py-0.5 rounded font-bold">INACTIVO</span>
                                        </div>
                                        <p class="text-xs text-slate-400">
                                            <span x-text="u.role === 'admin' ? 'Admin' : 'Recepcionista'"></span>
                                            · <span x-text="u.ventas_periodo"></span> ventas · <span x-text="u.citas_periodo"></span> citas
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-xs font-medium text-slate-500" x-text="u.last_login_at ? formatRelTime(u.last_login_at) : 'Nunca'"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- ── TABLA DE VENTAS RECIENTES ─────────────────────────── --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <h3 class="text-sm font-bold text-slate-700">Ventas Recientes del Periodo</h3>
                    <span class="text-xs text-slate-400" x-text="datos.ventas ? datos.ventas.length + ' registros en total' : ''"></span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-xs text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                <th class="text-left px-5 py-3 font-semibold">#</th>
                                <th class="text-left px-5 py-3 font-semibold">Cliente</th>
                                <th class="text-left px-5 py-3 font-semibold hidden md:table-cell">Usuario</th>
                                <th class="text-left px-5 py-3 font-semibold hidden sm:table-cell">Fecha</th>
                                <th class="text-center px-5 py-3 font-semibold">Estado</th>
                                <th class="text-right px-5 py-3 font-semibold">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <template x-if="(datos.ventas ?? []).length === 0">
                                <tr><td colspan="6" class="py-12 text-center text-slate-400 text-sm">Sin ventas registradas en este periodo</td></tr>
                            </template>
                            <template x-for="venta in ventasPaginadas" :key="venta.id">
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3 text-slate-400 text-xs" x-text="'#' + venta.id"></td>
                                    <td class="px-5 py-3 font-medium text-slate-700" x-text="venta.cliente"></td>
                                    <td class="px-5 py-3 text-slate-500 text-xs hidden md:table-cell" x-text="venta.usuario"></td>
                                    <td class="px-5 py-3 text-slate-400 text-xs hidden sm:table-cell" x-text="venta.fecha"></td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-flex text-xs px-2 py-0.5 rounded-full font-semibold"
                                            :class="{
                                                'bg-emerald-100 text-emerald-700': venta.estado === 'completada',
                                                'bg-amber-100 text-amber-700': venta.estado === 'pendiente',
                                                'bg-rose-100 text-rose-700': venta.estado === 'cancelada',
                                            }"
                                            x-text="venta.estado">
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-right font-bold text-slate-800"
                                        x-text="'$' + Number(venta.total).toLocaleString('es-MX', {minimumFractionDigits:2})"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                
                {{-- Controles de Paginación --}}
                <div class="px-5 py-3 border-t border-slate-100 flex items-center justify-between bg-white print:hidden" x-show="(datos.ventas ?? []).length > 10" x-cloak>
                    <span class="text-xs text-slate-500" x-text="`Página ${paginaActualVentas} de ${totalPaginasVentas}`"></span>
                    <div class="flex items-center gap-1">
                        <button type="button" @click="if(paginaActualVentas > 1) paginaActualVentas--" 
                            :disabled="paginaActualVentas === 1"
                            class="px-2.5 py-1.5 text-xs font-medium rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">Anterior</button>
                        <button type="button" @click="if(paginaActualVentas < totalPaginasVentas) paginaActualVentas++" 
                            :disabled="paginaActualVentas === totalPaginasVentas"
                            class="px-2.5 py-1.5 text-xs font-medium rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">Siguiente</button>
                    </div>
                </div>
            </div>

        </div>{{-- /!cargando --}}
    </div>{{-- /x-data --}}

    <script>
    function adminDashboard() {
        const h = new Date();
        const inicioMes = new Date(h.getFullYear(), h.getMonth(), 1).toISOString().slice(0, 10);
        const finMes = new Date(h.getFullYear(), h.getMonth() + 1, 0).toISOString().slice(0, 10);

        let chartIngresos = null;
        let chartVentas   = null;
        let chartCitas    = null;
        let chartCrec     = null;

        return {
            descargandoPDF: false,
            descargandoCSV: false,
            desde:   inicioMes,
            hasta:   finMes,
            periodo: 'mes',
            cargando: true,
            errorMsg: '',
            datos:   {},
            paginaActualVentas: 1,
            get ventasPaginadas() {
                const arr = this.datos.ventas ?? [];
                return arr.slice((this.paginaActualVentas - 1) * 10, this.paginaActualVentas * 10);
            },
            get totalPaginasVentas() {
                return Math.ceil((this.datos.ventas ?? []).length / 10) || 1;
            },
            periodos: [
                { key: 'hoy',          label: 'Hoy' },
                { key: 'semana',       label: 'Esta semana' },
                { key: 'mes',          label: 'Este mes' },
                { key: 'anio',         label: 'Este año' },
                { key: 'personalizado',label: 'Personalizado' },
            ],

            init() {
                this.cargar();
            },

            aplicarPeriodo(p) {
                this.periodo = p;
                const h = new Date();
                if (p === 'hoy') {
                    this.desde = this.hasta = h.toISOString().slice(0, 10);
                } else if (p === 'semana') {
                    const dia = h.getDay() || 7; // 1-7 (Lun-Dom)
                    const ini = new Date(h);
                    ini.setDate(h.getDate() - dia + 1);
                    const fin = new Date(ini);
                    fin.setDate(ini.getDate() + 6);
                    this.desde = ini.toISOString().slice(0, 10);
                    this.hasta = fin.toISOString().slice(0, 10);
                } else if (p === 'mes') {
                    this.desde = new Date(h.getFullYear(), h.getMonth(), 1).toISOString().slice(0, 10);
                    this.hasta = new Date(h.getFullYear(), h.getMonth() + 1, 0).toISOString().slice(0, 10);
                } else if (p === 'anio') {
                    this.desde = new Date(h.getFullYear(), 0, 1).toISOString().slice(0, 10);
                    this.hasta = new Date(h.getFullYear(), 11, 31).toISOString().slice(0, 10);
                }
                // personalizado: el usuario ajusta manualmente, no cargamos automáticamente
                if (p !== 'personalizado') this.cargar();
            },

            descargarPDF() {
                window.open(`{{ route('admin.reportes.pdf') }}?desde=${this.desde}&hasta=${this.hasta}`, '_blank');
            },

            async cargar() {
                this.cargando = true;
                this.errorMsg = '';
                this.paginaActualVentas = 1;
                try {
                    const res = await fetch(
                        `{{ route('admin.reportes.datos') }}?desde=${this.desde}&hasta=${this.hasta}`,
                        { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }
                    );
                    if (!res.ok) throw new Error('Error al obtener los datos del servidor.');
                    this.datos = await res.json();
                    this.$nextTick(() => this.renderCharts());
                } catch (e) {
                    this.errorMsg = e.message || 'No se pudieron cargar los indicadores. Intenta nuevamente.';
                } finally {
                    this.cargando = false;
                }
            },

            renderCharts() {
                this.renderChartIngresos();
                this.renderChartVentas();
                this.renderChartCitas();
                this.renderChartCrecimiento();
            },

            fillDateGaps(dias) {
                if (!dias || dias.length === 0) return [];
                const res = [];
                let d = new Date(this.desde + 'T00:00:00');
                const fin = new Date(this.hasta + 'T00:00:00');
                const map = {};
                dias.forEach(x => map[x.fecha] = x);
                
                while (d <= fin) {
                    const iso = d.toISOString().slice(0, 10);
                    res.push(map[iso] || { fecha: iso, total: 0, cantidad: 0 });
                    d.setDate(d.getDate() + 1);
                }
                return res;
            },

            formatDateLabel(iso) {
                const date = new Date(iso + 'T00:00:00');
                const meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
                return `${date.getDate()} ${meses[date.getMonth()]}`;
            },

            renderChartIngresos() {
                const el = document.getElementById('chartIngresos');
                if (!el) return;
                let dias = this.datos.ventasPorDia ?? [];
                if (dias.length === 0) return;
                
                dias = this.fillDateGaps(dias);

                if (chartIngresos) chartIngresos.destroy();
                chartIngresos = new Chart(el, {
                    type: 'bar',
                    data: {
                        labels: dias.map(d => this.formatDateLabel(d.fecha)),
                        datasets: [
                            {
                                label: 'Ingresos ($)',
                                data: dias.map(d => d.total),
                                backgroundColor: 'rgba(99, 102, 241, 0.85)',
                                borderRadius: 4,
                                borderWidth: 0,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 500, easing: 'easeOutQuart' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: ctx => ctx[0].label,
                                    label: ctx => ' $' + Number(ctx.raw).toLocaleString('es-MX', { minimumFractionDigits: 2 })
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                            y: { position: 'left', grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, callback: v => '$' + v.toLocaleString('es-MX') } }
                        }
                    }
                });
            },

            renderChartVentas() {
                const el = document.getElementById('chartVentas');
                if (!el) return;
                let dias = this.datos.ventasPorDia ?? [];
                if (dias.length === 0) return;
                
                dias = this.fillDateGaps(dias);

                if (chartVentas) chartVentas.destroy();
                chartVentas = new Chart(el, {
                    type: 'bar',
                    data: {
                        labels: dias.map(d => this.formatDateLabel(d.fecha)),
                        datasets: [
                            {
                                label: 'Ventas',
                                data: dias.map(d => d.cantidad),
                                backgroundColor: 'rgba(16, 185, 129, 0.85)',
                                borderRadius: 4,
                                borderWidth: 0,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 500, easing: 'easeOutQuart' },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    title: ctx => ctx[0].label,
                                    label: ctx => ' ' + ctx.raw + ' ventas'
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                            y: { position: 'left', grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, stepSize: 1 } }
                        }
                    }
                });
            },

            renderChartCitas() {
                const el = document.getElementById('chartCitas');
                if (!el) return;
                const estados = this.datos.citasPorEstado ?? {};
                if (Object.keys(estados).length === 0) return;

                if (chartCitas) chartCitas.destroy();
                const orden = ['completada','confirmada','pendiente','cancelada'];
                const labels = { completada:'Completadas', confirmada:'Confirmadas', pendiente:'Pendientes', cancelada:'Canceladas' };
                const colors = { completada:'rgba(16,185,129,0.85)', confirmada:'rgba(59,130,246,0.85)', pendiente:'rgba(251,191,36,0.85)', cancelada:'rgba(251,113,133,0.85)' };

                chartCitas = new Chart(el, {
                    type: 'doughnut',
                    data: {
                        labels: orden.map(k => labels[k]),
                        datasets: [{
                            data: orden.map(k => estados[k] ?? 0),
                            backgroundColor: orden.map(k => colors[k]),
                            borderWidth: 2,
                            borderColor: '#fff',
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw}` } }
                        }
                    }
                });
            },

            renderChartCrecimiento() {
                const el = document.getElementById('chartCrecimiento');
                if (!el) return;

                // Build last-6-months labels
                const meses6 = [];
                const now = new Date();
                for (let i = 5; i >= 0; i--) {
                    const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
                    meses6.push(d.toISOString().slice(0, 7));
                }
                const labelsMeses = meses6.map(m => {
                    const [y, mo] = m.split('-');
                    const nombres = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
                    return nombres[parseInt(mo) - 1] + ' ' + y.slice(2);
                });

                const clientes = meses6.map(m => this.datos.clientesPorMes?.[m] ?? 0);
                const mascotas = meses6.map(m => this.datos.mascotasPorMes?.[m] ?? 0);

                if (chartCrec) chartCrec.destroy();
                chartCrec = new Chart(el, {
                    type: 'bar',
                    data: {
                        labels: labelsMeses,
                        datasets: [
                            {
                                label: 'Clientes',
                                data: clientes,
                                backgroundColor: 'rgba(16, 185, 129, 0.75)',
                                borderRadius: 4,
                                borderWidth: 0,
                            },
                            {
                                label: 'Mascotas',
                                data: mascotas,
                                backgroundColor: 'rgba(14, 165, 233, 0.75)',
                                borderRadius: 4,
                                borderWidth: 0,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 9 } } },
                            y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }, stepSize: 1 } }
                        }
                    }
                });
            },

            // Calcula variación entre periodo actual y anterior
            variacion(actual, anterior) {
                const a = Number(actual ?? 0);
                const b = Number(anterior ?? 0);
                if (b === 0 && a === 0) return { txt: '—', cls: 'bg-slate-100 text-slate-500' };
                if (b === 0) return { txt: `+${a}`, cls: 'bg-emerald-50 text-emerald-600' };
                const pct = Math.round(((a - b) / b) * 100);
                if (pct > 0) return { txt: `+${pct}%`, cls: 'bg-emerald-50 text-emerald-600' };
                if (pct < 0) return { txt: `${pct}%`, cls: 'bg-rose-50 text-rose-500' };
                return { txt: '0%', cls: 'bg-slate-100 text-slate-500' };
            },

            // Formatea una fecha ISO en tiempo relativo sencillo
            formatRelTime(isoStr) {
                const d = new Date(isoStr);
                const diffMs = Date.now() - d.getTime();
                const diffMin = Math.floor(diffMs / 60000);
                if (diffMin < 1)  return 'Ahora';
                if (diffMin < 60) return `Hace ${diffMin} min`;
                const diffH = Math.floor(diffMin / 60);
                if (diffH < 24)   return `Hace ${diffH} h`;
                const diffD = Math.floor(diffH / 24);
                if (diffD < 30)   return `Hace ${diffD} días`;
                return d.toLocaleDateString('es-MX', { day: '2-digit', month: 'short' });
            }
        };
    }
    </script>

</x-app-layout>

@else
{{-- ═══════════════════════════════════════════════════════════════════
     DASHBOARD RECEPCIONISTA — Vista Operativa (SIN CAMBIOS)
═══════════════════════════════════════════════════════════════════ --}}
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
                        <path d="M12 4.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                        <path d="M18.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                        <path d="M5.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
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
                            <p class="text-xs font-semibold text-violet-600 mt-0.5">
                                @php
                                    $icono = match($cita->tipo_servicio) {
                                        'Consulta General' => '🩺',
                                        'Vacunación' => '💉',
                                        'Desparasitación' => '🦠',
                                        'Baño y Corte' => '🛁',
                                        'Esterilización/Castración' => '✂️',
                                        'Cirugía' => '🔬',
                                        'Laboratorio' => '🧪',
                                        'Rayos X / Ultrasonido' => '📡',
                                        'Chequeo General' => '📋',
                                        'Urgencias' => '🚨',
                                        default => '🐾'
                                    };
                                @endphp
                                {{ $icono }} {{ $cita->tipo_servicio ?? 'Consulta general' }}
                                @if($cita->motivo)
                                    <span class="text-slate-500 font-normal ml-1 border-l border-slate-300 pl-1">{{ Str::limit($cita->motivo, 35) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-semibold text-slate-700">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</p>
                            <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m') }}</p>
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

        <!-- Acciones Rápidas -->
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
    </div>

</x-app-layout>
@endif
