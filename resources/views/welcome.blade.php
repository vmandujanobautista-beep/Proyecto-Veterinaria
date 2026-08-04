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
        /* Oculta elementos con x-cloak hasta que Alpine.js los inicialice */
        [x-cloak] { display: none !important; }

        /* Corrección 1: Garantizar selección, copiar, pegar y cortar en todos los campos y modals */
        * {
            user-select: auto;
            -webkit-user-select: auto;
        }
        input, textarea, [contenteditable] {
            user-select: text !important;
            -webkit-user-select: text !important;
            pointer-events: auto !important;
        }

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

        /* ── Animated Veterinary Background Pattern ── */
        @keyframes vetPatternMove {
            0% {
                transform: translate(0, 0);
            }
            100% {
                transform: translate(-220px, -220px);
            }
        }
        .vet-pattern-layer {
            position: absolute;
            top: -220px;
            left: -220px;
            right: -220px;
            bottom: -220px;
            width: calc(100% + 440px);
            height: calc(100% + 440px);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='220' height='220' viewBox='0 0 220 220'%3E%3Cg opacity='0.22'%3E%3C!-- 1. CURITA --%3E%3Cg transform='translate(5, 5) scale(0.55) rotate(15)'%3E%3Crect x='15' y='35' width='70' height='30' rx='15' fill='%230284c7' opacity='0.35'/%3E%3Crect x='35' y='32' width='30' height='36' rx='4' fill='none' stroke='%230ea5e9' stroke-width='2'/%3E%3Cpath d='M47,42 h6 v16 h-6 z M42,47 h16 v6 h-16 z' fill='%230ea5e9'/%3E%3Ccircle cx='25' cy='45' r='2.5' fill='%230ea5e9'/%3E%3Ccircle cx='25' cy='55' r='2.5' fill='%230ea5e9'/%3E%3Ccircle cx='75' cy='45' r='2.5' fill='%230ea5e9'/%3E%3Ccircle cx='75' cy='55' r='2.5' fill='%230ea5e9'/%3E%3C/g%3E%3C!-- 2. MALETIN --%3E%3Cg transform='translate(125, 10) scale(0.5) rotate(-10)'%3E%3Cpath d='M35,25 v-8 a15,15 0 0,1 30,0 v8' fill='none' stroke='%230284c7' stroke-width='6' stroke-linecap='round'/%3E%3Crect x='15' y='25' width='70' height='60' rx='8' fill='%230284c7' opacity='0.35'/%3E%3Cpath d='M45,42 h10 v8 h8 v10 h-8 v8 h-10 v-8 h-8 v-10 h8 z' fill='%230ea5e9'/%3E%3C/g%3E%3C!-- 3. ESTETOSCOPIO --%3E%3Cg transform='translate(75, 60) scale(0.52) rotate(10)' stroke='%23059669' fill='none' stroke-width='6' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='25' cy='15' r='4' fill='%23059669'/%3E%3Ccircle cx='75' cy='15' r='4' fill='%23059669'/%3E%3Cpath d='M25,20 v25 a25,25 0 0,0 50,0 v-25'/%3E%3Cpath d='M50,45 v30'/%3E%3Ccircle cx='50' cy='82' r='10' stroke-width='5' fill='none'/%3E%3Cpath d='M48,79 h4 v6 h-4 z M45,80 h10 v4 h-10 z' fill='%23059669' stroke='none'/%3E%3C/g%3E%3C!-- 4. JERINGA --%3E%3Cg transform='translate(10, 115) scale(0.52) rotate(15)'%3E%3Cg transform='rotate(45 50 50)'%3E%3Crect x='35' y='5' width='30' height='5' rx='2' fill='%230ea5e9'/%3E%3Crect x='47' y='10' width='6' height='20' fill='%230ea5e9'/%3E%3Crect x='35' y='30' width='30' height='45' rx='4' fill='none' stroke='%230ea5e9' stroke-width='5'/%3E%3Crect x='38' y='45' width='24' height='28' fill='%230ea5e9' opacity='0.4'/%3E%3Cpath d='M35,38 h10 M35,45 h6 M35,52 h10 M35,59 h6' stroke='%230284c7' stroke-width='2' fill='none'/%3E%3Crect x='45' y='75' width='10' height='5' fill='%230ea5e9'/%3E%3Crect x='49' y='80' width='2' height='15' fill='%230ea5e9'/%3E%3C/g%3E%3C/g%3E%3C!-- 5. HUELLA CON CORAZON --%3E%3Cg transform='translate(135, 115) scale(0.5) rotate(-15)'%3E%3Cg fill='%230284c7' opacity='0.45'%3E%3Cellipse cx='28' cy='30' rx='12' ry='16' transform='rotate(-20 28 30)'/%3E%3Cellipse cx='50' cy='18' rx='14' ry='18'/%3E%3Cellipse cx='72' cy='30' rx='12' ry='16' transform='rotate(20 72 30)'/%3E%3Cpath d='M22,55 Q10,75 30,90 Q50,105 70,90 Q90,75 78,55 Q65,40 50,48 Q35,40 22,55 Z'/%3E%3C/g%3E%3Cpath d='M50,80 Q40,68 40,62 Q40,56 45,56 Q48,56 50,59 Q52,56 55,56 Q60,56 60,62 Q60,68 50,80 Z' fill='%230ea5e9'/%3E%3C/g%3E%3C!-- 6. HUESO --%3E%3Cg transform='translate(70, 155) scale(0.5) rotate(-10)'%3E%3Cg fill='%23059669' opacity='0.4' transform='rotate(20 50 50)'%3E%3Crect x='25' y='40' width='50' height='20' /%3E%3Ccircle cx='25' cy='35' r='14' /%3E%3Ccircle cx='25' cy='65' r='14' /%3E%3Ccircle cx='75' cy='35' r='14' /%3E%3Ccircle cx='75' cy='65' r='14' /%3E%3C/g%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            background-repeat: repeat;
            background-size: 220px 220px;
            animation: vetPatternMove 38s linear infinite;
            pointer-events: none;
            z-index: 0;
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
        /* ── Animated PawPrint Icon (Iniciar Sesión) ── */
        @keyframes pawStamp {
            0% {
                opacity: 0;
                transform: scale(1.5) translateY(-12px);
            }
            30% {
                opacity: 1;
                transform: scale(0.8) translateY(0);
            }
            60% {
                transform: scale(1.15) translateY(-2px);
            }
            85% {
                transform: scale(0.95) translateY(0);
            }
            100% {
                opacity: 0.9;
                transform: scale(1.05) translateY(0);
            }
        }
        .btn-primary .paw-inner {
            transform-origin: center;
            transition: transform 0.25s ease-out, opacity 0.25s ease-out;
        }
        .btn-primary:hover .paw-inner {
            animation: pawStamp 0.55s cubic-bezier(0.33, 1, 0.68, 1) forwards;
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid transparent;
            color: #64748b;
            transition: all 0.25s ease;
        }
        .btn-secondary:hover {
            background: rgba(240, 249, 255, 0.80);
            border-color: rgba(186, 230, 253, 0.60);
            color: #0284c7;
            transform: translateY(-1px);
        }

        /* ── Animated UserPlus Icon (Registrar Usuario) ── */
        .btn-secondary .user-avatar {
            transform-origin: 50% 50%;
            transition: transform 0.25s ease-out;
        }
        .btn-secondary .plus-sign {
            transform-origin: 19px 19px;
            transition: transform 0.3s ease-out;
        }
        .btn-secondary:hover .user-avatar {
            transform: scale(1.08) translateY(-1.5px);
        }
        .btn-secondary:hover .plus-sign {
            transform: scale(1.15) rotate(90deg);
        }

        /* ── Animated QuestionMark Icon (Ayuda) ── */
        @keyframes drawQuestionMark {
            0% { stroke-dashoffset: 20; }
            100% { stroke-dashoffset: 0; }
        }
        @keyframes dotBounce {
            0% { transform: translateY(0); stroke-dashoffset: 4; }
            50% { transform: translateY(-3px); }
            100% { transform: translateY(0); stroke-dashoffset: 0; }
        }
        @keyframes groupPulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.15); }
        }
        .btn-help {
            color: rgba(255,255,255,0.90);
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            transition: all 0.25s ease;
        }
        .btn-help:hover {
            background: rgba(255,255,255,0.22);
            color: #ffffff;
        }
        .btn-help .question-group {
            transform-origin: center;
            transition: transform 0.25s ease;
        }
        .btn-help .question-mark {
            stroke-dasharray: 20;
            stroke-dashoffset: 0;
        }
        .btn-help .question-mark-dot {
            stroke-dasharray: 4;
            stroke-dashoffset: 0;
            transform-origin: center;
        }
        .btn-help:hover .question-group {
            animation: groupPulse 0.25s ease-out;
        }
        .btn-help:hover .question-mark {
            animation: drawQuestionMark 0.4s ease-in-out;
        }
        .btn-help:hover .question-mark-dot {
            animation: dotBounce 0.3s ease-out;
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

<div class="landing-bg relative"
     x-data="{ activeModal: @js(session('open_modal') ?? session('success_modal') ?? ($errors->any() ? 'login' : null)) }"
     x-init="
         $watch('activeModal', val => {
             if (val) {
                 document.body.style.overflow = 'hidden';
             } else {
                 document.body.style.overflow = '';
             }
         });
         // Trigger inicial por si carga con un modal abierto
         if (activeModal) document.body.style.overflow = 'hidden';
         
         if ('{{ session('success_modal') }}' === 'reset_success') {
             setTimeout(() => { activeModal = 'login'; }, 2000);
         }
     ">

    <!-- Soft blobs -->
    <div class="blob-1"></div>
    <div class="blob-2"></div>
    <div class="blob-3"></div>

    <!-- Animated Veterinary Background Pattern -->
    <div class="vet-pattern-layer"></div>

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
            <div class="mr-20 hidden md:flex items-center gap-2.5 px-6 py-2 rounded-full "
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

            <!-- ===== DERECHA: ACCIONES ===== -->
            <div class="flex items-center gap-3 flex-shrink-0">
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
                            <button type="button"
                               @click="activeModal = 'reset'"
                               class="btn-help inline-flex items-center gap-1.5 text-sm font-medium px-3.5 py-1.5 rounded-lg transition-all">
                                <svg class="question-group w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path class="question-mark" d="M8 8a3.5 3 0 0 1 3.5 -3h1a3.5 3 0 0 1 3.5 3a3 3 0 0 1 -2 3a3 4 0 0 0 -2 4" />
                                    <path class="question-mark-dot" d="M12 19l0 .01" />
                                </svg>
                                Ayuda
                            </button>
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

            <!-- Contenedor Flex para alinear ícono y texto -->
<div class="flex items-center justify-center gap-5 mb-6">
    
    <!-- Main icon -->
    <div class="relative">
        <!-- Se redujo el tamaño a w-20 h-20 para mejor proporción horizontal -->
        <div class="w-20 h-20 rounded-3xl flex items-center justify-center"
             style="background:linear-gradient(135deg,rgba(186,230,253,0.70) 0%,rgba(167,243,208,0.70) 100%);
                    border:1.5px solid rgba(56,189,248,0.35);
                    box-shadow:0 0 50px rgba(14,165,233,0.15);">
            <svg class="w-10 h-10" style="color:#0ea5e9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="absolute inset-0 w-20 h-20 rounded-3xl scale-110 opacity-50"
             style="border:1.5px solid rgba(56,189,248,0.25)"></div>
    </div>

    <!-- Headline -->
    <!-- Se eliminó el margen inferior para que quede centrado verticalmente con el ícono -->
    <h1 class="shimmer-text text-5xl font-extrabold tracking-tight m-0">
        VetCare
    </h1>
</div>

<p class="text-xl font-semibold mb-3 max-w-xl" style="color:#0c4a6e">
    Sistema de Gestión para Clínicas Veterinarias
</p>
<p class="text-base max-w-lg leading-relaxed mb-10" style="color:#475569">
    Administra citas, clientes, mascotas y ventas desde un solo lugar.
    Diseñado para el equipo interno de tu clínica.
</p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full">
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
                        <button type="button"
                           id="btn-iniciar-sesion"
                           @click="activeModal = 'login'"
                           class="btn-primary inline-flex items-center gap-2.5 font-semibold text-base px-8 py-3.5 rounded-xl">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" style="overflow: visible;">
                                <g class="paw-inner" style="transform-origin: center;">
                                    <circle cx="11" cy="4" r="2" />
                                    <circle cx="18" cy="8" r="2" />
                                    <circle cx="20" cy="16" r="2" />
                                    <path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z" />
                                </g>
                            </svg>
                            Iniciar Sesión
                        </button>
                        @if (Route::has('register'))
                            <button type="button"
                               id="btn-registrarse"
                               @click="activeModal = 'register'"
                               class="btn-secondary border border-gray-500 hover:border-sky-700 inline-flex items-center gap-2 font-medium text-sm sm:text-base px-5 py-3 rounded-xl">
                               <svg class="w-5 h-5" style="color:#0ea5e9" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <!-- User avatar -->
                                    <g class="user-avatar">
                                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                                    </g>
                                    <!-- Plus sign -->
                                    <g class="plus-sign">
                                        <path d="M16 19h6" />
                                        <path d="M19 16v6" />
                                    </g>
                                </svg>
                                Registrar Usuario
                            </button>
                        @endif
                    @endauth
                @endif
            </div>

   

        <!-- Divider -->
        <div class="divider bg-blue-900 h-px w-full max-w-4xl mt-16 mb-12"></div> 

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

    {{-- ═══ VALIDADORES EN TIEMPO REAL ALPIINE.JS ═══ --}}
    <script>
        window.VetCareValidators = {
            email(val) {
                if (!val || val.trim() === '') {
                    return 'El correo electrónico es obligatorio.';
                }
                if (/\s/.test(val)) {
                    return 'El correo no debe contener espacios.';
                }
                if (/[A-Z]/.test(val)) {
                    return 'El correo no debe contener letras mayúsculas (todo debe ser en minúsculas).';
                }
                if (/[ñÑáéíóúÁÉÍÓÚ]/u.test(val)) {
                    return 'El correo no debe contener eñes ni acentos.';
                }
                if (/[\(\)\[\]\{\}<>":;\\,]/.test(val)) {
                    return 'El correo contiene caracteres especiales no permitidos.';
                }
                const atCount = (val.match(/@/g) || []).length;
                if (atCount === 0) {
                    return 'El correo debe contener exactamente un símbolo @.';
                }
                if (atCount > 1) {
                    return 'El correo no puede tener más de una @.';
                }
                if (!/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/.test(val)) {
                    return 'El correo debe tener un formato y dominio válido (.com, .es, .org, etc.).';
                }
                return '';
            },

            name(val) {
                if (!val || val.trim() === '') {
                    return 'El nombre completo es obligatorio.';
                }
                if (/[0-9]/.test(val)) {
                    return 'El nombre no puede contener números.';
                }
                if (/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/u.test(val)) {
                    return 'El nombre solo debe contener letras, espacios, acentos y eñes.';
                }
                return '';
            },

            password(val) {
                if (!val || val.trim() === '') {
                    return 'La contraseña es obligatoria.';
                }
                if (val.length < 8) {
                    return 'La contraseña debe tener al menos 8 caracteres.';
                }
                return '';
            }
        };
    </script>

    {{-- ═══ MODALS DE AUTENTICACIÓN ─ deben estar dentro del div x-data ═══ --}}
    @include('partials.modals.modal-login')
    @include('partials.modals.modal-register')
    @include('partials.modals.modal-reset-password')

</div>

</body>
</html>
