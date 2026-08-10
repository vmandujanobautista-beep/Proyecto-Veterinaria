<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestión Veterinaria - Administra clientes, mascotas y citas de tu clínica veterinaria">

    <title>{{ config('app.name', 'VetCare') }} | {{ $title ?? 'Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar gradient */
        .vet-sidebar {
            background: linear-gradient(180deg, #0f4c75 0%, #1b262c 100%);
        }

        /* Active nav item */
        .nav-item-active {
            background: rgba(255, 255, 255, 0.15);
            border-left: 3px solid #4fc3f7;
        }

        /* Card hover effect */
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }

        /* Smooth transitions */
        .nav-link-item {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .nav-link-item:hover {
            background: rgba(255, 255, 255, 0.10);
            border-left: 3px solid rgba(79, 195, 247, 0.6);
        }

        /* Paw print animation */
        @keyframes pawPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.7; transform: scale(1.05); }
        }

        .paw-pulse {
            animation: pawPulse 2.5s ease-in-out infinite;
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }

        /* Global button animations */
        .btn-emerald-pulse {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #059669, #34d399);
            border-radius: 12px;
            background-size: 100% auto;
            transition: background-position 0.3s ease;
        }
        .btn-emerald-pulse:hover {
            background-position: right center;
            background-size: 200% auto;
            animation: pulseEmerald 1.5s infinite;
        }
        @keyframes pulseEmerald {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .btn-blue-pulse {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #1d4ed8, #3b82f6);
            border-radius: 12px;
            background-size: 100% auto;
            transition: background-position 0.3s ease;
        }
        .btn-blue-pulse:hover {
            background-position: right center;
            background-size: 200% auto;
            animation: pulseBlue 1.5s infinite;
        }
        @keyframes pulseBlue {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5); }
            70% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }

        .group\/edit:hover .pen-group {
            animation: pen-scribble 0.6s ease-in-out infinite;
        }
        @keyframes pen-scribble {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            25% { transform: translate(1px, -2px) rotate(-6deg); }
            50% { transform: translate(-1px, -4px) rotate(-4deg); }
            75% { transform: translate(1px, -6px) rotate(-6deg); }
        }
        
        .btn-gold-pulse {
            border: none;
            color: #fff;
            background-image: linear-gradient(30deg, #d97706, #fbbf24);
            border-radius: 12px;
            background-size: 100% auto;
            transition: background-position 0.3s ease;
        }
        .btn-gold-pulse:hover {
            background-position: right center;
            background-size: 200% auto;
            animation: pulseGold 1.5s infinite;
        }
        @keyframes pulseGold {
            0% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.5); }
            70% { box-shadow: 0 0 0 10px rgba(245, 158, 11, 0); }
            100% { box-shadow: 0 0 0 0 rgba(245, 158, 11, 0); }
        }
        
        .group:hover .home-roof {
            animation: home-roof 0.4s ease-out forwards;
        }
        .group:hover .home-house {
            animation: home-house 0.3s ease-out forwards;
        }
        .group:hover .home-door {
            animation: home-door 0.3s ease-out forwards;
            animation-delay: 0.3s;
        }
        @keyframes home-roof {
            0% { transform: translateY(-2px); opacity: 0.6; }
            100% { transform: translateY(0); opacity: 1; }
        }
        @keyframes home-house {
            0% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
        @keyframes home-door {
            0% { transform: scaleY(0); }
            100% { transform: scaleY(1); }
        }
        
        .user-center, .user-left, .user-right {
            transition: all 0.25s ease-in-out;
            transform-origin: center center;
            transform-box: fill-box;
        }
        .group:hover .user-center {
            animation: user-center-anim 0.25s ease-out forwards;
        }
        .group:hover .user-left {
            animation: user-left-anim 0.3s ease-out forwards;
            animation-delay: 0.05s;
        }
        .group:hover .user-right {
            animation: user-right-anim 0.3s ease-out forwards;
            animation-delay: 0.05s;
        }
        @keyframes user-center-anim {
            0% { transform: translateY(0) scale(1); }
            100% { transform: translateY(-2px) scale(1.05); }
        }
        @keyframes user-left-anim {
            0% { transform: translateX(0) scale(1); }
            100% { transform: translateX(-1px) scale(1.02); }
        }
        @keyframes user-right-anim {
            0% { transform: translateX(0) scale(1); }
            100% { transform: translateX(1px) scale(1.02); }
        }
        
        @keyframes paw-anim {
            0% { opacity: 0; transform: translateY(-20px) scale(1.5); }
            15% { opacity: 1; transform: translateY(0) scale(1); }
            25% { transform: translateY(-1px) scale(0.75); }
            35% { transform: translateY(0) scale(1.1); }
            45% { transform: translateY(0) scale(1); }
            100% { opacity: 0.6; transform: translateY(0) scale(1.03); }
        }
        .paw-inner {
            transition: all 0.3s;
            transform-origin: center;
            transform-box: fill-box;
        }
        .group:hover .paw-inner {
            animation: paw-anim 0.95s forwards;
        }

        @keyframes cart-upper-anim {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(4px); }
        }
        @keyframes cart-wheel-anim {
            0%, 100% { transform: translateX(0) rotate(0); }
            50% { transform: translateX(4px) rotate(180deg); }
        }
        @keyframes cart-item-anim {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-2px); }
        }
        .cart-wheel {
            transform-origin: center center;
            transform-box: fill-box;
        }
        .group:hover .cart-upper {
            animation: cart-upper-anim 0.6s ease-in-out forwards;
        }
        .group:hover .cart-wheel {
            animation: cart-wheel-anim 0.6s ease-in-out forwards;
        }
        .group:hover .cart-item {
            animation: cart-item-anim 0.4s ease-in-out forwards;
        }

        .clock-hands {
            transform-origin: center;
            transform-box: fill-box;
            transition: transform 1s ease-in-out;
        }
        .group:hover .clock-hands {
            transform: rotate(360deg);
        }

        @keyframes star-outline-anim {
            0% { transform: scale(1) rotate(0deg); }
            33% { transform: scale(1.1) rotate(-5deg); }
            50% { transform: scale(1.1) rotate(0deg); }
            66% { transform: scale(1) rotate(5deg); }
            100% { transform: scale(1) rotate(0deg); }
        }
        .star-outline {
            transform-origin: center;
            transform-box: fill-box;
            transition: all 0.3s ease-in-out;
        }
        .group:hover .star-outline {
            animation: star-outline-anim 0.5s ease-in-out;
        }
        .star-fill {
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s ease-out;
            transform-origin: center;
            transform-box: fill-box;
        }
        .group:hover .star-fill {
            opacity: 1;
            transform: scale(1);
            transition: all 0.4s ease-out;
        }

        .sidebar-arrow {
            transition: transform 0.3s ease-out;
        }
        .group:hover .sidebar-arrow {
            transform: translateX(4px);
        }
        .sidebar-dash {
            transition: all 0.3s ease-out;
            transform-origin: 4px 12px;
        .group:hover .sidebar-dash {
            transform: scale(1.2);
            opacity: 0.7;
        }

        /* Transiciones Swup */
        .transition-main {
            transition: opacity 200ms ease-out, transform 200ms ease-out;
            opacity: 1;
            transform: translateY(0);
        }
        html.is-animating .transition-main {
            opacity: 0;
            transform: translateY(10px);
        }
        @media (prefers-reduced-motion: reduce) {
            .transition-main {
                transition: opacity 200ms ease-out;
            }
            html.is-animating .transition-main {
                transform: none;
            }
        }
    </style>
</head>
<body class="bg-slate-100 antialiased">

{{-- Alpine scope global: profileOpen controla el modal de perfil, pageLoading el loader --}}
<div class="flex h-screen overflow-hidden"
     x-data="{ 
        sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true'),
        profileOpen: false, 
        clienteModalOpen: false, 
        pageLoading: false 
     }"
     x-init="$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val))"
     @show-loader.window="pageLoading = true; setTimeout(() => { pageLoading = false }, 8000);"
     @hide-loader.window="pageLoading = false"
     @loading.window="$event.detail ? (pageLoading = true, setTimeout(() => { pageLoading = false }, 8000)) : (pageLoading = false)">

    <!-- ===== SIDEBAR ===== -->
    <aside id="sidebar" :class="sidebarOpen ? 'w-72' : 'w-[72px]'" class="vet-sidebar flex-shrink-0 flex flex-col transition-all duration-300 z-40">
        <!-- Logo / Brand -->
        <!-- Cambiamos la disposición si está colapsado -->
        <div class="flex items-center py-5 border-b border-white/10 w-full transition-all duration-300" :class="sidebarOpen ? 'justify-between px-6' : 'justify-center px-2 flex-col gap-3'">
            
            <!-- Lado Izquierdo: Agrupamos el Icono de Usuario y el Texto -->
            <div class="flex items-center gap-3">
                
                <!-- Contenedor del Icono de Usuario -->
                <div class="w-12 h-12 bg-[#0c3859] rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                
                <!-- Textos -->
                <div class="whitespace-nowrap overflow-hidden transition-all duration-300" :class="sidebarOpen ? 'w-auto opacity-100' : 'w-0 opacity-0 hidden'">
                    <h1 class="text-white text-xl font-bold leading-tight">VetCare</h1>
                    <p class="text-sky-300 text-sm leading-tight">Gestión Veterinaria</p>
                </div>
            </div>
            
            <!-- Lado Derecho: Botón de colapsar -->      
            <div class="flex items-center" :class="sidebarOpen ? '-mr-2' : ''">
                <button type="button"
                   @click="sidebarOpen = !sidebarOpen"
                   class="p-2 text-sky-400 rounded-lg hover:bg-[#1a5582] hover:text-white transition-colors duration-200 group"
                   :title="sidebarOpen ? 'Colapsar menú' : 'Expandir menú'">
                    <svg class="w-6 h-6 transition-transform duration-300" :class="sidebarOpen ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path class="sidebar-arrow" d="M11 9a1 1 0 0 0 1-1V5.061a1 1 0 0 1 1.811-.75l6.836 6.836a1.207 1.207 0 0 1 0 1.707l-6.836 6.835a1 1 0 0 1-1.811-.75V16a1 1 0 0 0-1-1H9a1 1 0 0 1-1-1v-4a1 1 0 0 1 1-1z" />
                        <path class="sidebar-dash" d="M4 9v6" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 py-5 space-y-1 overflow-y-auto" :class="sidebarOpen ? 'px-3' : 'px-2'">

            <p x-show="sidebarOpen" class="text-sky-400/60 text-xs font-semibold uppercase tracking-wider px-3 mb-2 transition-all">Principal</p>

            <a href="{{ route('dashboard') }}"
               :title="!sidebarOpen ? 'Dashboard' : ''"
               class="nav-link-item flex items-center py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('dashboard') ? 'nav-item-active text-white' : '' }}"
               :class="sidebarOpen ? 'px-3 gap-3' : 'justify-center px-0'">
                <svg class="w-5 h-5 text-sky-400 group-hover:text-sky-300 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path class="home-roof transition-all duration-200" d="M5 12l-2 0l9 -9l9 9l-2 0" />
                    <path class="home-house transition-all duration-200" style="transform-origin: center;" d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                    <path class="home-door transition-all duration-200" style="transform-origin: center bottom;" d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Dashboard</span>
            </a>

            <p x-show="sidebarOpen" class="text-sky-400/60 text-xs font-semibold uppercase tracking-wider px-3 mt-4 mb-2 transition-all">Gestión</p>

            <a href="{{ route('clientes.index') }}"
               :title="!sidebarOpen ? 'Clientes' : ''"
               class="nav-link-item flex items-center py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('clientes.*') ? 'nav-item-active text-white' : '' }}"
               :class="sidebarOpen ? 'px-3 gap-3' : 'justify-center px-0 mt-2'">
                <svg class="w-5 h-5 text-emerald-400 group-hover:text-emerald-300 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <!-- Center user -->
                    <g class="user-center">
                        <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                    </g>
                    <!-- Right user -->
                    <g class="user-right">
                        <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                    </g>
                    <!-- Left user -->
                    <g class="user-left">
                        <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                    </g>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Clientes</span>
            </a>

            <a href="{{ route('mascotas.index') }}"
               :title="!sidebarOpen ? 'Mascotas' : ''"
               class="nav-link-item flex items-center py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('mascotas.*') ? 'nav-item-active text-white' : '' }}"
               :class="sidebarOpen ? 'px-3 gap-3' : 'justify-center px-0 mt-2'">
                <svg class="w-5 h-5 text-cyan-400 group-hover:text-cyan-300 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="overflow: visible;">
                    <g class="paw-inner">
                        <circle cx="11" cy="4" r="2" />
                        <circle cx="18" cy="8" r="2" />
                        <circle cx="20" cy="16" r="2" />
                        <path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z" />
                    </g>
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Mascotas</span>
            </a>

            <a href="{{ route('citas.index') }}"
               :title="!sidebarOpen ? 'Citas' : ''"
               class="nav-link-item flex items-center py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('citas.*') ? 'nav-item-active text-white' : '' }}"
               :class="sidebarOpen ? 'px-3 gap-3' : 'justify-center px-0 mt-2'">
                <svg class="w-5 h-5 text-violet-400 group-hover:text-violet-300 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" class="clock-body" />
                    <path d="M12 7v5l3 3" class="clock-hands" />
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Citas</span>
            </a>

            <a href="{{ route('productos.index') }}"
               :title="!sidebarOpen ? 'Productos' : ''"
               class="nav-link-item flex items-center py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('productos.*') ? 'nav-item-active text-white' : '' }}"
               :class="sidebarOpen ? 'px-3 gap-3' : 'justify-center px-0 mt-2'">
                <svg class="w-5 h-5 text-amber-400 group-hover:text-amber-300 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path class="star-fill" d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" fill="currentColor" />
                    <path class="star-outline" d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Productos</span>
            </a>

            <a href="{{ route('ventas.index') }}"
               :title="!sidebarOpen ? 'Ventas' : ''"
               class="nav-link-item flex items-center py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('ventas.*') ? 'nav-item-active text-white' : '' }}"
               :class="sidebarOpen ? 'px-3 gap-3' : 'justify-center px-0 mt-2'">
                <svg class="w-5 h-5 text-rose-400 group-hover:text-rose-300 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="4" stroke-miterlimit="10" stroke-linecap="square">
                    <g class="cart-upper">
                        <path d="M8.49994 10H41L37.569 21.4367C36.9345 23.5517 34.9879 25 32.7798 25H10.4999" />
                        <path d="M41 32H9.46174C7.17727 32 6.08953 29.1885 7.77914 27.651L10.6923 25L7.81067 5.14103C7.63231 3.91188 6.57863 3.00005 5.33661 3.00003L3 3" />
                        <path class="cart-item" d="M30 16L30 19" />
                        <path class="cart-item" d="M24 16L24 19" />
                        <path class="cart-item" d="M18 16L18 19" />
                    </g>
                    <path class="cart-wheel" d="M11 45C13.2091 45 15 43.2091 15 41C15 38.7909 13.2091 37 11 37C8.79086 37 7 38.7909 7 41C7 43.2091 8.79086 45 11 45Z" />
                    <path class="cart-wheel" d="M37 45C39.2091 45 41 43.2091 41 41C41 38.7909 39.2091 37 37 37C34.7909 37 33 38.7909 33 41C33 43.2091 34.7909 45 37 45Z" />
                </svg>
                <span x-show="sidebarOpen" class="text-sm font-medium whitespace-nowrap">Ventas</span>
            </a>

        </nav>

        <!-- User Info (bottom) -->
        <div class="py-4 border-t border-white/10" :class="sidebarOpen ? 'px-4' : 'px-2'">
            <div class="flex items-center" :class="sidebarOpen ? 'gap-3' : 'flex-col gap-4 justify-center'">
                <!-- Avatar -->
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0 cursor-pointer"
                     @click="!sidebarOpen ? profileOpen = true : null"
                     :title="!sidebarOpen ? 'Mi Perfil' : ''">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                
                <!-- Text Info -->
                <button type="button" @click="profileOpen = true" x-show="sidebarOpen" class="flex-1 min-w-0 flex flex-col text-left hover:bg-white/5 rounded-lg p-1 -ml-1 transition-colors group">
                    <p class="text-white text-sm font-medium truncate group-hover:text-sky-300 transition-colors">{{ Auth::user()->name }}</p>
                    <p class="text-slate-400 text-xs truncate">{{ Auth::user()->email }}</p>
                </button>
                
                <!-- Logout Button -->
                <form method="POST" action="{{ route('logout') }}" :class="!sidebarOpen ? 'mt-2' : ''">
                    @csrf
                    <button type="submit" title="Cerrar sesión"
                            class="text-slate-400 hover:text-rose-400 transition-colors group"
                            :class="sidebarOpen ? '' : 'p-2'">
                        <!-- Icono de Cerrar Sesión Animado -->
                        <svg class="w-5 h-5" 
                             xmlns="http://www.w3.org/2000/svg" 
                             viewBox="0 0 24 24" 
                             fill="none" 
                             stroke="currentColor" 
                             stroke-width="2" 
                             stroke-linecap="round" 
                             stroke-linejoin="round">
                            <style>
                                @keyframes logout-arrow-move {
                                    0% { transform: translateX(0); }
                                    50% { transform: translateX(6px); }
                                    100% { transform: translateX(0); }
                                }
                                @keyframes logout-door-move {
                                    0% { transform: translateX(0); }
                                    50% { transform: translateX(-2px); }
                                    100% { transform: translateX(0); }
                                }
                                .group:hover .logout-arrow, 
                                .group:hover .logout-arrow-bottom {
                                    animation: logout-arrow-move 0.3s ease-in-out;
                                }
                                .group:hover .logout-door {
                                    animation: logout-door-move 0.25s ease-out;
                                }
                            </style>
                            <path class="logout-door" style="transform-origin: 50% 50%;" d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                            <path class="logout-arrow" d="M9 12h12" />
                            <path class="logout-arrow-bottom" d="M18 15l3 -3l-3 -3" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Top Header Bar -->
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div>
                @isset($header)
                    {{ $header }}
                @endisset
            </div>
            <div class="flex items-center gap-3">
                <!-- Notification Bell -->
                <button class="relative p-2 text-slate-500 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors group">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <style>
                            @keyframes bell-ring {
                                0% { transform: rotate(0deg); }
                                20% { transform: rotate(-8deg); }
                                40% { transform: rotate(6deg); }
                                60% { transform: rotate(-4deg); }
                                80% { transform: rotate(2deg); }
                                100% { transform: rotate(0deg); }
                            }
                            @keyframes bell-circle-ring {
                                0% { transform: rotate(0deg); }
                                20% { transform: rotate(20deg); }
                                40% { transform: rotate(-18deg); }
                                60% { transform: rotate(12deg); }
                                80% { transform: rotate(-6deg); }
                                100% { transform: rotate(0deg); }
                            }
                            .group:hover .bell-animate {
                                animation: bell-ring 0.6s ease-in-out;
                            }
                            .group:hover .bell-circle-animate {
                                animation: bell-circle-ring 0.6s ease-in-out 0.05s;
                            }
                        </style>
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path class="bell-circle-animate" style="transform-origin: 50% 0%;" d="M14.235 19c.865 0 1.322 1.024 .745 1.668a3.992 3.992 0 0 1 -2.98 1.332a3.992 3.992 0 0 1 -2.98 -1.332c-.552 -.616 -.158 -1.579 .634 -1.661l.11 -.006h4.471z" />
                        <path class="bell-animate" style="transform-origin: 50% 10%;" d="M12 2c1.358 0 2.506 .903 2.875 2.141l.046 .171l.008 .043a8.013 8.013 0 0 1 4.024 6.069l.028 .287l.019 .289v2.931l.021 .136a3 3 0 0 0 1.143 1.847l.167 .117l.162 .099c.86 .487 .56 1.766 -.377 1.864l-.116 .006h-16c-1.028 0 -1.387 -1.364 -.493 -1.87a3 3 0 0 0 1.472 -2.063l.021 -.143l.001 -2.97a8 8 0 0 1 3.821 -6.454l.248 -.146l.01 -.043a3.003 3.003 0 0 1 2.562 -2.29l.182 -.017l.176 -.004z" />
                    </svg>
                </button>
                <!-- Current Date -->
                <span class="text-xs text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg hidden md:block">
                    {{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </span>
            </div>
        </header>

        <!-- Page Content -->
        <main id="swup" class="flex-1 overflow-y-auto bg-slate-50 p-6 transition-main">
            @if(session('success'))
                <div id="flash-success"
                     class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                    <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-emerald-500 hover:text-emerald-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div id="flash-error"
                     class="mb-4 flex items-center gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button onclick="document.getElementById('flash-error').remove()" class="ml-auto text-rose-500 hover:text-rose-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    {{-- ===== MODAL DE PERFIL ===== --}}
    @include('partials.modals.modal-perfil')

    {{-- ===== MODAL NUEVO CLIENTE ===== --}}
    @include('partials.modals.modal-nuevo-cliente')

    {{-- ===== MODAL VER CLIENTE ===== --}}
    @include('partials.modals.modal-ver-cliente')

    {{-- ===== MODAL VER MASCOTA ===== --}}
    @include('partials.modals.modal-ver-mascota')

    {{-- ===== MODAL EDITAR MASCOTA ===== --}}
    @include('partials.modals.modal-editar-mascota')

    {{-- ===== MODAL VER PRODUCTO ===== --}}
    @include('partials.modals.modal-ver-producto')

    {{-- ===== MODAL SOLICITAR REABASTECIMIENTO ===== --}}
    @include('partials.modals.modal-solicitar-reabastecimiento')

    {{-- ===== MODALS DE CITAS ===== --}}
    @include('partials.modals.modal-agendar-cita')
    @include('partials.modals.modal-ver-cita')
    @include('partials.modals.modal-cancelar-cita')
    @include('partials.modals.modal-editar-cita')

    {{-- ===== LOADING SCREEN ===== --}}
    <x-loading-screen />

</div>{{-- /Alpine x-data profileOpen --}}

</body>
</html>
