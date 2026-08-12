<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Supervisión</title>
    <style>
        @page {
            margin: 40px;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            color: #334155;
            background-color: #ffffff;
        }
        .header {
            background-color: #1e293b;
            color: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header p {
            margin: 0;
            color: #94a3b8;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        /* Tarjetas (4 columnas) */
        .cards-table {
            margin-bottom: 30px;
        }
        .cards-table td {
            width: 25%;
            padding: 0 5px;
            vertical-align: top;
        }
        .cards-table td:first-child { padding-left: 0; }
        .cards-table td:last-child { padding-right: 0; }
        .card {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
        }
        .card-title {
            font-size: 9px;
            color: #64748b;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        .card-value {
            font-size: 26px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 5px;
        }
        .card-desc {
            font-size: 11px;
            color: #94a3b8;
        }
        .text-red { color: #ef4444; }

        /* Dos columnas: Resumen Citas y Alertas Inventario */
        .cols-table {
            margin-bottom: 30px;
        }
        .cols-table td {
            width: 50%;
            vertical-align: top;
        }
        .cols-table td.col-left { padding-right: 15px; }
        .cols-table td.col-right { padding-left: 15px; }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 10px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }
        
        /* Tablas de datos */
        .data-table {
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            width: 100%;
            overflow: hidden; /* For border radius */
        }
        .data-table th {
            text-align: left;
            padding: 10px 15px;
            font-size: 9px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }
        .data-table td {
            padding: 12px 15px;
            font-size: 11px;
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        
        /* Badges */
        .badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-completadas { background-color: #dcfce7; color: #166534; }
        .badge-confirmadas { background-color: #dbeafe; color: #1e40af; }
        .badge-pendientes { background-color: #fef3c7; color: #92400e; }
        .badge-canceladas { background-color: #ffe4e6; color: #e11d48; }

        .text-orange { color: #f59e0b; font-weight: bold; }
        .text-danger { color: #dc2626; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        .small-text {
            font-size: 10px;
            color: #94a3b8;
            display: block;
            margin-top: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte Ejecutivo de Supervisión</h1>
        <p>Periodo de Análisis: {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }} | Sistema de Control e Inventario</p>
    </div>

    <!-- 4 Cards -->
    <table class="cards-table">
        <tr>
            <td>
                <div class="card">
                    <div class="card-title">CLIENTES<br>REGISTRADOS</div>
                    <div class="card-value">{{ $totalClientesGlobal }}</div>
                    <div class="card-desc">+{{ $clientesNuevos }} en el periodo</div>
                </div>
            </td>
            <td>
                <div class="card">
                    <div class="card-title">MASCOTAS<br>REGISTRADAS</div>
                    <div class="card-value">{{ $totalMascotasGlobal }}</div>
                    <div class="card-desc">+{{ $mascotasNuevas }} en el periodo</div>
                </div>
            </td>
            <td>
                <div class="card">
                    <div class="card-title">CITAS DEL<br>PERIODO</div>
                    <div class="card-value">{{ $citasPeriodoActual }}</div>
                    <div class="card-desc text-red">{{ $citasPorEstado['cancelada'] ?? 0 }} canceladas</div>
                </div>
            </td>
            <td>
                <div class="card">
                    <div class="card-title">INGRESOS DEL<br>PERIODO</div>
                    <div class="card-value">${{ number_format($ventasTotales, 2) }}</div>
                    <div class="card-desc">{{ $ventasCount }} ventas registradas</div>
                </div>
            </td>
        </tr>
    </table>

    <!-- 2 Columns -->
    <table class="cols-table">
        <tr>
            <td class="col-left">
                <div class="section-title">Resumen de Citas</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ESTADO</th>
                            <th class="text-right">CANTIDAD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><span class="badge badge-completadas">Completadas</span></td>
                            <td class="text-right">{{ $citasPorEstado['completada'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-confirmadas">Confirmadas</span></td>
                            <td class="text-right">{{ $citasPorEstado['confirmada'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-pendientes">Pendientes</span></td>
                            <td class="text-right">{{ $citasPorEstado['pendiente'] ?? 0 }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge badge-canceladas">Canceladas</span></td>
                            <td class="text-right">{{ $citasPorEstado['cancelada'] ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td class="col-right">
                <div class="section-title">Alertas de Inventario (&lt; 10 uds.)</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>PRODUCTO</th>
                            <th class="text-right">STOCK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productosBajos->take(4) as $p)
                        <tr>
                            <td>
                                <strong>{{ $p->nombre }}</strong>
                                <span class="small-text">{{ $p->categoria }}</span>
                            </td>
                            <td class="text-right {{ $p->stock == 0 ? 'text-danger' : 'text-orange' }}">{{ $p->stock }} uds.</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center" style="color:#94a3b8; padding: 20px;">Sin alertas de inventario</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Registro de Ventas Recientes</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>FOLIO</th>
                <th>CLIENTE</th>
                <th>USUARIO (CAJERO)</th>
                <th>FECHA Y HORA</th>
                <th>ESTADO</th>
                <th class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php $count = 0; @endphp
            @forelse($ventas as $v)
                @if($count < 5)
                <tr>
                    <td style="color:#94a3b8;">#{{ $v['id'] }}</td>
                    <td>{{ $v['cliente'] }}</td>
                    <td>{{ $v['usuario'] }}</td>
                    <td>{{ $v['fecha'] }}</td>
                    <td>
                        @if(in_array($v['estado'], ['completada', 'pagada']))
                            <span class="badge badge-completadas">Pagada</span>
                        @elseif($v['estado'] == 'pendiente')
                            <span class="badge badge-pendientes">Pendiente</span>
                        @else
                            <span class="badge badge-canceladas">Cancelada</span>
                        @endif
                    </td>
                    <td class="text-right"><strong>${{ number_format($v['total'], 2) }}</strong></td>
                </tr>
                @php $count++; @endphp
                @endif
            @empty
            <tr>
                <td colspan="6" class="text-center" style="color:#94a3b8; padding: 20px;">No hay ventas recientes.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
