<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Reabastecimiento — VetCare</title>
    <style>
        body {
            margin: 0; padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .wrapper { max-width: 560px; margin: 32px auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }

        /* Header */
        .header { padding: 32px 40px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; margin: 8px 0 0; }
        .header p  { color: rgba(255,255,255,0.75); font-size: 13px; margin: 4px 0 0; }

        /* Body */
        .body { background: #fff; padding: 36px 40px; }
        .greeting { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 6px; }
        .intro    { font-size: 14px; color: #475569; margin: 0 0 24px; line-height: 1.6; }

        /* Stock badge */
        .stock-box { border-radius: 10px; padding: 16px 24px; text-align: center; margin-bottom: 24px; }
        .stock-box .stock-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; margin: 0 0 4px; }
        .stock-box .stock-num { font-size: 32px; font-weight: 900; margin: 0; }
        .stock-box .stock-sub { font-size: 13px; font-weight: 500; margin: 4px 0 0; }

        /* Detalles */
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .detail-table td { padding: 10px 14px; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
        .detail-table td:first-child { color: #64748b; font-weight: 600; width: 40%; white-space: nowrap; }
        .detail-table td:last-child { color: #0f172a; font-weight: 500; }
        .detail-table tr:last-child td { border-bottom: none; }

        /* Alerta */
        .alert-box { border-radius: 8px; padding: 14px 18px; margin-bottom: 24px; }
        .alert-box p { margin: 0; font-size: 13px; line-height: 1.5; }

        /* Footer */
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center; }
        .footer p { font-size: 12px; color: #94a3b8; margin: 4px 0; line-height: 1.5; }
        .footer .brand { font-size: 16px; font-weight: 800; color: #0f4c75; margin-bottom: 6px; }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- HEADER (color según urgencia) -->
    @if($producto->stock === 0)
        <div class="header" style="background: linear-gradient(135deg, #991b1b, #dc2626);">
            <div style="font-size:42px;">🔴</div>
            <h1>VetCare — Stock Agotado</h1>
            <p>Solicitud de reabastecimiento URGENTE</p>
        </div>
    @elseif($producto->stock <= 4)
        <div class="header" style="background: linear-gradient(135deg, #9a3412, #ea580c);">
            <div style="font-size:42px;">⚠️</div>
            <h1>VetCare — Últimas Unidades</h1>
            <p>Solicitud de reabastecimiento urgente</p>
        </div>
    @else
        <div class="header" style="background: linear-gradient(135deg, #0f4c75, #1b6ca8);">
            <div style="font-size:42px;">📦</div>
            <h1>VetCare — Stock Bajo</h1>
            <p>Solicitud de reabastecimiento</p>
        </div>
    @endif

    <!-- BODY -->
    <div class="body">

        <p class="greeting">Hola, Administrador 👋</p>
        <p class="intro">
            Se ha recibido una <strong>solicitud de reabastecimiento</strong> para el siguiente producto.
            Por favor revisa el inventario y coordina el abastecimiento a la brevedad.
        </p>

        <!-- Stock visual -->
        @if($producto->stock === 0)
            <div class="stock-box" style="background:#fee2e2; border: 2px solid #fca5a5;">
                <p class="stock-label" style="color:#991b1b;">⚠️ Stock actual</p>
                <p class="stock-num" style="color:#dc2626;">AGOTADO</p>
                <p class="stock-sub" style="color:#b91c1c;">0 unidades disponibles</p>
            </div>
        @elseif($producto->stock <= 4)
            <div class="stock-box" style="background:#ffedd5; border: 2px solid #fdba74;">
                <p class="stock-label" style="color:#9a3412;">⚠️ Stock crítico</p>
                <p class="stock-num" style="color:#ea580c;">{{ $producto->stock }}</p>
                <p class="stock-sub" style="color:#c2410c;">{{ $producto->stock === 1 ? 'unidad restante' : 'unidades restantes' }}</p>
            </div>
        @else
            <div class="stock-box" style="background:#fef9c3; border: 2px solid #fde047;">
                <p class="stock-label" style="color:#854d0e;">📦 Stock bajo</p>
                <p class="stock-num" style="color:#ca8a04;">{{ $producto->stock }}</p>
                <p class="stock-sub" style="color:#a16207;">unidades restantes</p>
            </div>
        @endif

        <!-- Tabla de detalles -->
        <table class="detail-table">
            <tr>
                <td>📦 Producto</td>
                <td><strong>{{ $producto->nombre }}</strong></td>
            </tr>
            <tr>
                <td>🔖 Código</td>
                <td><code style="background:#f1f5f9; padding:2px 6px; border-radius:4px; font-size:13px;">{{ $producto->codigo ?? '—' }}</code></td>
            </tr>
            <tr>
                <td>🏷️ Categoría</td>
                <td>{{ ucfirst($producto->categoria ?? '—') }}</td>
            </tr>
            <tr>
                <td>📊 Stock actual</td>
                <td>
                    <strong style="color:{{ $producto->stock === 0 ? '#dc2626' : ($producto->stock <= 4 ? '#ea580c' : '#ca8a04') }}">
                        {{ $producto->stock }} {{ $producto->stock === 1 ? 'unidad' : 'unidades' }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td>💰 Precio unitario</td>
                <td>${{ number_format($producto->precio, 2) }}</td>
            </tr>
            @if($producto->descripcion)
            <tr>
                <td>📝 Descripción</td>
                <td style="font-size:13px; color:#475569;">{{ $producto->descripcion }}</td>
            </tr>
            @endif
        </table>

        <!-- Solicitante -->
        <div class="alert-box" style="background:#eff6ff; border-left:3px solid #3b82f6;">
            <p style="color:#1d4ed8; font-size:13px;">
                <strong>👤 Solicitado por:</strong>
                {{ $solicitante->name }}
                ({{ $solicitante->email }})
                <br>
                <strong>📅 Fecha y hora:</strong> {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>

        <p style="font-size:13px; color:#64748b; text-align:center; margin:0;">
            Por favor confirma la recepción de esta solicitud o coordina el reabastecimiento.
            Contáctanos en <a href="mailto:{{ config('mail.from.address') }}" style="color:#0284c7; font-weight:600; text-decoration:none;">{{ config('mail.from.address') }}</a>
        </p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p class="brand">🐾 VetCare</p>
        <p>Gestión Veterinaria Profesional</p>
        <p style="margin-top:12px; color:#cbd5e1; font-size:11px;">
            © {{ date('Y') }} VetCare. Correo generado automáticamente — no responder.
        </p>
    </div>

</div>
</body>
</html>
