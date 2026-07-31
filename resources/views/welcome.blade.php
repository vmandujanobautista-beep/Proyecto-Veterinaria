<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="VetCare - Sistema de Gestión para Clínicas Veterinarias. Administra citas, clientes, mascotas y ventas desde un solo lugar.">
    <title>VetCare | Sistema de Gestión Veterinaria</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f9ff;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }

        /* ── Main gradient background ── */
        .landing-bg {
            background-color: #f0f9ff;
            background-image: linear-gradient(145deg, #e0f2fe 0%, #f0fdf4 55%, #ecfdf5 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }

        /* ── Soft blobs for depth ── */
        .blob-1 {
            position: absolute;
            top: -120px;
            right: -120px;
            width: 480px;
            height: 480px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(125, 211, 252, 0.25) 0%, transparent 70%);
            pointer-events: none;
        }
        .blob-2 {
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 380px;
            height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(110, 231, 183, 0.22) 0%, transparent 70%);
            pointer-events: none;
        }
        .blob-3 {
            position: absolute;
            top: 40%;
            left: -60px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(186, 230, 253, 0.30) 0%, transparent 70%);
            pointer-events: none;
        }

        /* ── Feature cards (light style) ── */
        .feature-card {
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid rgba(186, 230, 253, 0.60);
            border-radius: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(14, 165, 233, 0.07);
        }
        .feature-card:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(56, 189, 248, 0.45);
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(14, 165, 233, 0.13);
        }

        /* ── Paw animation ── */
        @keyframes pawPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.75; transform: scale(1.08); }
        }
        .paw-pulse { animation: pawPulse 3s ease-in-out infinite; }

        /* ── Logo text gradient (dark-friendly on light bg) ── */
        @keyframes shimmer {
            0%   { background-position: -200% center; }
            100% { background-position:  200% center; }
        }
        .shimmer-text {
            background: linear-gradient(90deg, #0369a1 20%, #0ea5e9 50%, #059669 80%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 5s linear infinite;
        }

        /* ── Buttons ── */
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #3b82f6 100%);
            box-shadow: 0 4px 18px rgba(14, 165, 233, 0.30);
            transition: all 0.25s ease;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #38bdf8 0%, #60a5fa 100%);
            box-shadow: 0 6px 26px rgba(14, 165, 233, 0.45);
            transform: translateY(-2px);
        }
        .btn-primary:active { transform: translateY(0); }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.70);
            border: 1.5px solid rgba(14, 165, 233, 0.35);
            color: #0369a1;
            transition: all 0.25s ease;
        }
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: rgba(14, 165, 233, 0.65);
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(14, 165, 233, 0.15);
        }

        /* ── Badge ── */
        .system-badge {
            background: rgba(186, 230, 253, 0.55);
            border: 1px solid rgba(56, 189, 248, 0.40);
        }

        /* ── Divider ── */
        .divider {
            background: linear-gradient(90deg, transparent, rgba(14, 165, 233, 0.20), transparent);
        }

        /* ── Icon boxes ── */
        .icon-box-violet { background: rgba(167, 139, 250, 0.15); border: 1px solid rgba(167, 139, 250, 0.30); }
        .icon-box-emerald { background: rgba(110, 231, 183, 0.15); border: 1px solid rgba(110, 231, 183, 0.30); }
        .icon-box-amber  { background: rgba(252, 211, 77, 0.15);  border: 1px solid rgba(252, 211, 77, 0.35); }
        .icon-box-sky    { background: rgba(125, 211, 252, 0.15); border: 1px solid rgba(125, 211, 252, 0.30); }
    </style>
</head>
<body>

<div class="landing-bg relative">

    <!-- Soft blobs -->
    <div class="blob-1"></div>
    <div class="blob-2"></div>
    <div class="blob-3"></div>

    <!-- ===== HEADER ===== -->
    <header class="relative z-10 w-full overflow-hidden"
            style="background: linear-gradient(90deg, #38bdf8 0%, #0ea5e9 40%, #0284c7 100%);
                   box-shadow: 0 4px 20px rgba(2,132,199,0.30);">

        <!-- Fondo decorativo: puntos y huellas sutiles -->
        <div class="absolute inset-0 pointer-events-none" style="opacity:0.12;">
            <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
                <!-- Patrones de patitas distribuidas -->
                <g fill="white">
                    <!-- Patita 1 -->
                    <ellipse cx="5%" cy="50%" rx="6" ry="4"/>
                    <circle cx="3%" cy="35%" r="3.5"/>
                    <circle cx="7%" cy="35%" r="3.5"/>
                    <circle cx="2%" cy="65%" r="3"/>
                    <!-- Patita 2 -->
                    <ellipse cx="18%" cy="55%" rx="6" ry="4"/>
                    <circle cx="16%" cy="40%" r="3.5"/>
                    <circle cx="20%" cy="40%" r="3.5"/>
                    <circle cx="15%" cy="68%" r="3"/>
                    <!-- Patita 3 -->
                    <ellipse cx="82%" cy="45%" rx="6" ry="4"/>
                    <circle cx="80%" cy="30%" r="3.5"/>
                    <circle cx="84%" cy="30%" r="3.5"/>
                    <circle cx="79%" cy="60%" r="3"/>
                    <!-- Patita 4 -->
                    <ellipse cx="95%" cy="55%" rx="6" ry="4"/>
                    <circle cx="93%" cy="38%" r="3.5"/>
                    <circle cx="97%" cy="38%" r="3.5"/>
                    <circle cx="92%" cy="68%" r="3"/>
                    <!-- Líneas de circuito decorativas -->
                    <rect x="8%" y="49%" width="6%" height="1.5" rx="1"/>
                    <rect x="22%" y="52%" width="4%" height="1.5" rx="1"/>
                    <rect x="74%" y="48%" width="5%" height="1.5" rx="1"/>
                    <rect x="88%" y="52%" width="4%" height="1.5" rx="1"/>
                    <!-- Nodos -->
                    <circle cx="8%" cy="49%" r="2.5"/>
                    <circle cx="14%" cy="49%" r="2.5"/>
                    <circle cx="22%" cy="52%" r="2.5"/>
                    <circle cx="74%" cy="48%" r="2.5"/>
                    <circle cx="79%" cy="48%" r="2.5"/>
                    <circle cx="88%" cy="52%" r="2.5"/>
                </g>
            </svg>
        </div>

        <div class="relative flex items-center justify-between py-3 px-6 w-full">

            <!-- ===== IZQUIERDA: LOGO ===== -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <!-- Ícono con fondo blanco translúcido -->
                <div class="w-11 h-11 rounded-xl flex items-center justify-center paw-pulse"
                     style="background: rgba(255,255,255,0.22); border: 1.5px solid rgba(255,255,255,0.45);
                            box-shadow: 0 2px 10px rgba(0,0,0,0.10);">
                    <svg class="w-6 h-6" fill="white" viewBox="0 0 24 24">
                        <path d="M12 4.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                        <path d="M18.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                        <path d="M5.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                        <path d="M12 11.5c-2.5 0-4.5 1.5-5.5 3.5-.5 1-1 2-1 3.5 0 2.5 2.5 4.5 6.5 4.5s6.5-2 6.5-4.5c0-1.5-.5-2.5-1-3.5-1-2-3-3.5-5.5-3.5z"/>
                    </svg>
                </div>
                <div>
                    <span class="font-bold text-lg leading-tight block" style="color:white; text-shadow:0 1px 3px rgba(0,0,0,0.15);">VetCare</span>
                    <span class="text-xs font-medium block" style="color: rgba(255,255,255,0.80);">Sistema de Gestión</span>
                </div>
            </div>

            <!-- ===== CENTRO: MENSAJE DE BIENVENIDA ===== -->
            <div class="hidden md:flex items-center gap-2.5 px-6 py-2 rounded-full"
                 style="background: rgba(255,255,255,0.18);
                        border: 2px dashed rgba(255,255,255,0.55);
                        backdrop-filter: blur(4px);">
                <svg class="w-5 h-5 flex-shrink-0" fill="white" viewBox="0 0 24 24">
                    <path d="M12 4.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <path d="M18.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <path d="M5.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <path d="M12 11.5c-2.5 0-4.5 1.5-5.5 3.5-.5 1-1 2-1 3.5 0 2.5 2.5 4.5 6.5 4.5s6.5-2 6.5-4.5c0-1.5-.5-2.5-1-3.5-1-2-3-3.5-5.5-3.5z"/>
                </svg>
                <span class="font-bold text-sm tracking-wide" style="color: white; text-shadow: 0 1px 2px rgba(0,0,0,0.12);">
                    ¡Listos para cuidar peluditos hoy!
                </span>
            </div>

            <!-- ===== DERECHA: STATS + BOTONES ===== -->
            <div class="flex items-center gap-3 flex-shrink-0">

                <!-- Badges de estadísticas (decorativos en landing) -->
                <div class="hidden lg:flex items-center gap-3 mr-1">
                    <!-- 12 Citas -->
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg"
                         style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                        <svg class="w-4 h-4" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs font-semibold" style="color:white;">12 CITAS</span>
                    </div>
                    <!-- 2 Alertas -->
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg"
                         style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);">
                        <svg class="w-4 h-4" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <span class="text-xs font-semibold" style="color:white;">2 ALERTAS</span>
                    </div>
                </div>

                <!-- Separador -->
                <div class="hidden lg:block h-8 w-px" style="background: rgba(255,255,255,0.30);"></div>

                <!-- Botones de navegación -->
                <nav class="flex items-center gap-2">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg transition-all"
                               style="background: #0c4a6e; color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.20);"
                               onmouseover="this.style.background='#075985'; this.style.transform='translateY(-1px)'"
                               onmouseout="this.style.background='#0c4a6e'; this.style.transform='translateY(0)'">
                                <!-- Ícono casa -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Ir al Dashboard
                            </a>
                        @else
                            <!-- Botón Iniciar Sesión -->
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg transition-all"
                               style="background: #0c4a6e; color: white; box-shadow: 0 2px 8px rgba(0,0,0,0.20);"
                               onmouseover="this.style.background='#075985'; this.style.transform='translateY(-1px)'"
                               onmouseout="this.style.background='#0c4a6e'; this.style.transform='translateY(0)'">
                                <!-- Ícono candado -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Iniciar Sesión
                            </a>
                            <!-- Botón Registrarse -->
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-lg transition-all"
                                   style="background: rgba(255,255,255,0.92); color: #0369a1; border: 1px solid rgba(255,255,255,0.60);
                                          box-shadow: 0 2px 8px rgba(0,0,0,0.10);"
                                   onmouseover="this.style.background='white'; this.style.transform='translateY(-1px)'"
                                   onmouseout="this.style.background='rgba(255,255,255,0.92)'; this.style.transform='translateY(0)'">
                                    <!-- Ícono usuario+ -->
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </nav>
            </div>

        </div>
    </header>

    
    <!-- ===== HERO SECTION ===== -->
    <main class="relative z-10 flex flex-col items-center justify-center text-center px-4 pt-16 pb-12">

        <!-- System badge -->
        <div class="system-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-8">
            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:#0ea5e9"></span>
            <span class="text-xs font-semibold uppercase tracking-wider" style="color:#0369a1">Portal de Acceso Interno</span>
        </div>

        <!-- Main icon -->
        <div class="relative mb-8">
            <div class="w-28 h-28 rounded-3xl flex items-center justify-center mx-auto"
                 style="background:linear-gradient(135deg,rgba(186,230,253,0.70) 0%,rgba(167,243,208,0.70) 100%);
                        border:1.5px solid rgba(56,189,248,0.35);
                        box-shadow:0 0 50px rgba(14,165,233,0.15);">
                <svg class="w-14 h-14" style="color:#0ea5e9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M18.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M5.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 11.5c-2.5 0-4.5 1.5-5.5 3.5-.5 1-1 2-1 3.5 0 2.5 2.5 4.5 6.5 4.5s6.5-2 6.5-4.5c0-1.5-.5-2.5-1-3.5-1-2-3-3.5-5.5-3.5z"/>
                </svg>
            </div>
            <!-- Decorative ring -->
            <div class="absolute inset-0 w-28 h-28 mx-auto rounded-3xl scale-110 opacity-50"
                 style="border:1.5px solid rgba(56,189,248,0.25)"></div>
        </div>

        <!-- Headline -->
        <h1 class="shimmer-text text-5xl font-extrabold tracking-tight mb-4 max-w-2xl">
            VetCare
        </h1>
        <p class="text-xl font-semibold mb-3 max-w-xl" style="color:#0c4a6e">
            Sistema de Gestión para Clínicas Veterinarias
        </p>
        <p class="text-base max-w-lg leading-relaxed mb-12" style="color:#475569">
            Administra citas, clientes, mascotas y ventas desde un solo lugar.
            Diseñado para el equipo interno de tu clínica.
        </p>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row items-center gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}"
                       id="btn-ir-dashboard"
                       class="btn-primary inline-flex items-center gap-2.5 font-semibold text-base px-8 py-3.5 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Ir al Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       id="btn-iniciar-sesion"
                       class="btn-primary inline-flex items-center gap-2.5 font-semibold text-base px-8 py-3.5 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Iniciar Sesión
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                           id="btn-registrarse"
                           class="btn-secondary inline-flex items-center gap-2 font-medium text-base px-6 py-3.5 rounded-xl">
                            <svg class="w-5 h-5" style="color:#0ea5e9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Registrar usuario
                        </a>
                    @endif
                @endauth
            @endif
        </div>

        <!-- Divider -->
        <div class="divider h-px w-full max-w-2xl mt-20 mb-16"></div>

        <!-- ===== FEATURE CARDS ===== -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl w-full px-4">

            <!-- Gestión de Citas -->
            <div class="feature-card p-5 text-left">
                <div class="w-10 h-10 rounded-xl icon-box-violet flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" style="color:#7c3aed" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-sm mb-1.5" style="color:#1e293b">Gestión de Citas</h3>
                <p class="text-xs leading-relaxed" style="color:#64748b">
                    Programa, confirma y da seguimiento a todas las citas de la clínica.
                </p>
            </div>

            <!-- Clientes y Mascotas -->
            <div class="feature-card p-5 text-left">
                <div class="w-10 h-10 rounded-xl icon-box-emerald flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" style="color:#059669" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-sm mb-1.5" style="color:#1e293b">Clientes y Mascotas</h3>
                <p class="text-xs leading-relaxed" style="color:#64748b">
                    Registro completo de propietarios y expedientes de cada paciente.
                </p>
            </div>

            <!-- Punto de Venta -->
            <div class="feature-card p-5 text-left">
                <div class="w-10 h-10 rounded-xl icon-box-amber flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" style="color:#d97706" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-sm mb-1.5" style="color:#1e293b">Punto de Venta</h3>
                <p class="text-xs leading-relaxed" style="color:#64748b">
                    Gestiona ventas de productos, medicamentos e insumos de la clínica.
                </p>
            </div>

            <!-- Confirmación Automática -->
            <div class="feature-card p-5 text-left">
                <div class="w-10 h-10 rounded-xl icon-box-sky flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" style="color:#0ea5e9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-sm mb-1.5" style="color:#1e293b">Confirmación Automática</h3>
                <p class="text-xs leading-relaxed" style="color:#64748b">
                    Envía recordatorios de citas por correo electrónico y WhatsApp.
                </p>
            </div>

        </div>
    </main>

    <!-- ===== FOOTER ===== -->
    <footer class="relative z-10 text-center pb-8 pt-4">
        <p class="text-xs" style="color:#94a3b8">
            VetCare &copy; {{ date('Y') }} &mdash; Sistema de Gestión Interno &mdash; Uso exclusivo del personal autorizado
        </p>
    </footer>

</div>

</body>
</html>
