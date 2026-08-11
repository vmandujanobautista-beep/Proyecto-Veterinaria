<!DOCTYPE html>
@php
    $config = \App\Models\Configuracion::instancia();
    $clinica_nombre = $config->clinica_nombre ?: 'VetCare';
    $clinica_direccion = $config->clinica_direccion ?: 'Dirección no registrada';
    $clinica_telefono = $config->clinica_telefono ?: 'Sin teléfono';
    $clinica_email = $config->clinica_email ?: config('mail.from.address');
@endphp
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Cita — {{ $clinica_nombre }}</title>
    <style>
        body {
            margin: 0; padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
        }
        .wrapper { max-width: 560px; margin: 32px auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,.08); }

        /* Header */
        .header { background: #0f4c75; padding: 32px 40px; text-align: center; }
        .header h1 { color: #fff; font-size: 22px; margin: 8px 0 0; }
        .header p  { color: #7dd3fc; font-size: 13px; margin: 4px 0 0; }

        /* Body */
        .body { background: #fff; padding: 36px 40px; }
        .greeting { font-size: 20px; font-weight: 700; color: #0f172a; margin: 0 0 6px; }
        .intro    { font-size: 14px; color: #475569; margin: 0 0 28px; line-height: 1.6; }

        /* Fecha box */
        .fecha-box { background: #0f4c75; border-radius: 10px; padding: 18px 24px; text-align: center; margin-bottom: 24px; }
        .fecha-box .label { color: #7dd3fc; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; margin: 0 0 4px; }
        .fecha-box .fecha { color: #fff; font-size: 20px; font-weight: 800; margin: 0; }
        .fecha-box .hora  { color: #bae6fd; font-size: 15px; font-weight: 500; margin: 4px 0 0; }

        /* Detalles */
        .detail-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .detail-table td { padding: 10px 14px; font-size: 14px; border-bottom: 1px solid #f1f5f9; }
        .detail-table td:first-child { color: #64748b; font-weight: 600; width: 40%; white-space: nowrap; }
        .detail-table td:last-child { color: #0f172a; font-weight: 500; }
        .detail-table tr:last-child td { border-bottom: none; }

        /* Nota médica */
        .nota-box { background: #fef9c3; border-left: 3px solid #eab308; border-radius: 0 8px 8px 0; padding: 12px 16px; margin-bottom: 24px; }
        .nota-box p { margin: 0; font-size: 13px; color: #713f12; line-height: 1.5; }
        .nota-box .nota-label { font-weight: 700; margin-bottom: 4px; }

        /* Tips */
        .tips { background: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; }
        .tips p { margin: 0 0 8px; font-size: 13px; font-weight: 700; color: #9a3412; }
        .tips ul { margin: 0; padding-left: 16px; }
        .tips ul li { font-size: 13px; color: #7c2d12; margin-bottom: 4px; line-height: 1.4; }

        /* Footer */
        .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 40px; text-align: center; }
        .footer p { font-size: 12px; color: #94a3b8; margin: 4px 0; line-height: 1.5; }
        .footer .brand { font-size: 16px; font-weight: 800; color: #0f4c75; margin-bottom: 6px; }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- HEADER -->
    <div class="header">
        <div style="font-size:42px;">🐾</div>
        <h1>{{ $clinica_nombre }}</h1>
        <p>Gestión Veterinaria Profesional</p>
    </div>

    <!-- BODY -->
    <div class="body">

        <p class="greeting">¡Hola, {{ $cita->cliente->nombre }}! 👋</p>
        <p class="intro">
            Tu cita veterinaria ha sido <strong>confirmada exitosamente</strong>.
            Aquí tienes todos los detalles de tu próxima visita.
        </p>

        <!-- Fecha y Hora -->
        <div class="fecha-box">
            <p class="label">📅 Tu cita está programada para</p>
            <p class="fecha">{{ \Carbon\Carbon::parse($cita->fecha)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
            <p class="hora">🕐 {{ $cita->hora }} hrs</p>
        </div>

        <!-- Tabla de detalles -->
        <table class="detail-table">
            <tr>
                <td>🐾 Paciente</td>
                <td>
                    <strong>{{ $cita->mascota->nombre ?? '—' }}</strong>
                    @if($cita->mascota)
                        <br>
                        <span style="color:#64748b; font-size:12px;">
                            {{ $cita->mascota->especie ?? '' }}
                            {{ $cita->mascota->raza ? ' · ' . $cita->mascota->raza : '' }}
                        </span>
                    @endif
                </td>
            </tr>
            <tr>
                <td>👤 Propietario</td>
                <td>{{ $cita->cliente->nombre }} {{ $cita->cliente->apellido }}</td>
            </tr>
            <tr>
                <td>🩺 Servicio</td>
                <td>{{ $cita->tipo_servicio ?? 'Consulta general' }}</td>
            </tr>
            @if($cita->motivo)
                <tr>
                    <td>📝 Motivo</td>
                    <td>{{ $cita->motivo }}</td>
                </tr>
            @endif
            <tr>
                <td>📅 Fecha</td>
                <td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td>🕐 Hora</td>
                <td>{{ $cita->hora }} hrs</td>
            </tr>
            <tr>
                <td>✅ Estado</td>
                <td><strong>{{ ucfirst($cita->estado ?? 'Confirmada') }}</strong></td>
            </tr>
        </table>

        <!-- Nota médica (si existe) -->
        @if($cita->mascota?->nota_medica)
            <div class="nota-box">
                <p class="nota-label">⚕️ Nota médica de {{ $cita->mascota->nombre }}</p>
                <p>{{ $cita->mascota->nota_medica }}</p>
            </div>
        @endif

        <!-- Tips -->
        <div class="tips">
            <p>🌟 Recomendaciones para tu visita</p>
            <ul>
                <li>Llega 10 minutos antes de tu hora de cita.</li>
                <li>Trae el historial médico previo y carnet de vacunación.</li>
                <li>Si tu mascota ayunó, indícalo al veterinario.</li>
                <li>Usa transportador o correa según corresponda.</li>
            </ul>
        </div>

        <p style="font-size:13px; color:#64748b; text-align:center; margin:0;">
            ¿Necesitas cancelar o reprogramar?
            Escríbenos a <a href="mailto:{{ $clinica_email }}" style="color:#0284c7; font-weight:600; text-decoration:none;">{{ $clinica_email }}</a>
            o llámanos al <strong>📞 {{ $clinica_telefono }}</strong>
        </p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p class="brand">🐾 {{ $clinica_nombre }}</p>
        <p>{{ $clinica_direccion }}</p>
        <p>Lunes—Viernes: 9:00—18:00 • Sábados: 9:00—14:00 • Urgencias 24/7</p>
        <p style="margin-top:12px; color:#cbd5e1; font-size:11px;">
            © {{ date('Y') }} {{ $clinica_nombre }}. Este correo fue enviado a {{ $cita->cliente->email }}.
        </p>
    </div>

</div>
</body>
</html>
