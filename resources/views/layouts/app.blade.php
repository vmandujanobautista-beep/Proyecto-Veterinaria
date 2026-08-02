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
    </style>
</head>
<body class="bg-slate-100 antialiased">

{{-- Alpine scope global: profileOpen controla el modal de perfil --}}
<div class="flex h-screen overflow-hidden" x-data="{ profileOpen: false }">

    <!-- ===== SIDEBAR ===== -->
    <aside id="sidebar" class="vet-sidebar w-72 flex-shrink-0 flex flex-col transition-all duration-300 z-40">
<!-- Logo / Brand -->
<!-- Agregamos justify-between aquí para separar los extremos -->
<div class="flex items-center justify-between px-6 py-5 border-b border-white/10 w-full">
    
    <!-- Lado Izquierdo: Agrupamos el Icono de Usuario y el Texto -->
    <div class="flex items-center gap-3">
        
        <!-- Contenedor del Icono de Usuario -->
        <div class="w-12 h-12 bg-[#0c3859] rounded-full flex items-center justify-center shrink-0">
            <!-- El SVG del usuario (solo uno) -->
            <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        
        <!-- Textos -->
        <div class="whitespace-nowrap">
            <h1 class="text-white text-xl font-bold leading-tight">VetCare</h1>
            <p class="text-sky-300 text-sm leading-tight">Gestión Veterinaria</p>
        </div>

    </div>
    
    <!-- Lado Derecho: Nuevo botón de perfil en forma de tuerca -->      
    <!-- Al estar dentro de un contenedor justify-between, esto se empuja solo a la derecha -->
    <!-- Puedes quitar el -mr-6, o dejarlo como -mr-2 si quieres que pegue aún más al borde -->
    <button type="button"
       @click="profileOpen = true"
       class="p-2 text-sky-400 rounded-lg hover:bg-[#1a5582] hover:text-white transition-colors duration-200 -mr-2 group"
       title="Mi Perfil">
        <!-- Icono de Tuerca (Engranaje) SVG Animado -->
        <svg class="w-6 h-6" 
             xmlns="http://www.w3.org/2000/svg" 
             viewBox="0 0 32 32" 
             fill="none" 
             stroke="currentColor" 
             stroke-width="2" 
             stroke-linecap="square" 
             stroke-linejoin="miter" 
             stroke-miterlimit="10"
             style="overflow: visible;">
            <style>
                @keyframes gear-rotate {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
                @keyframes gear-scale-center {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                }
                @keyframes gear-scale-body {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.02); }
                }
                .group:hover .gear-rotator {
                    animation: gear-rotate 0.9s ease-in-out;
                }
                .group:hover .gear-center {
                    animation: gear-scale-center 0.3s ease-out;
                }
                .group:hover .gear-body {
                    animation: gear-scale-body 0.6s ease-in-out;
                }
                .gear-rotator, .gear-center, .gear-body {
                    transform-origin: 50% 50%;
                    transform-box: fill-box;
                }
            </style>
            <g class="gear-rotator">
                <circle class="gear-center" cx="16" cy="16" r="5" />
                <path class="gear-body" d="m30,17.5v-3l-3.388-1.355c-.25-.933-.617-1.815-1.089-2.633l1.436-3.351-2.121-2.121-3.351,1.436c-.817-.472-1.7-.838-2.633-1.089l-1.355-3.388h-3l-1.355,3.388c-.933.25-1.815.617-2.633,1.089l-3.351-1.436-2.121,2.121 1.436,3.351c-.472.817-.838,1.7-1.089,2.633l-3.388,1.355v3l3.388,1.355c.25.933.617,1.815,1.089,2.633l-1.436,3.351 2.121,2.121 3.351-1.436c.817.472 1.7.838 2.633,1.089l1.355,3.388h3l1.355-3.388c.933-.25 1.815-.617 2.633-1.089l3.351,1.436 2.121-2.121-1.436-3.351c.472-.817.838-1.7 1.089-2.633l3.388-1.355Z" />
            </g>
        </svg>
    </button>
</div>


        <!-- Navigation -->
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">

            <p class="text-sky-400/60 text-xs font-semibold uppercase tracking-wider px-3 mb-2">Principal</p>

            <a href="{{ route('dashboard') }}"
               class="nav-link-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('dashboard') ? 'nav-item-active text-white' : '' }}">
                <svg class="w-5 h-5 text-sky-400 group-hover:text-sky-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <p class="text-sky-400/60 text-xs font-semibold uppercase tracking-wider px-3 mt-4 mb-2">Gestión</p>

            <a href="{{ route('clientes.index') }}"
               class="nav-link-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('clientes.*') ? 'nav-item-active text-white' : '' }}">
                <svg class="w-5 h-5 text-emerald-400 group-hover:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span class="text-sm font-medium">Clientes</span>
            </a>

            <a href="{{ route('mascotas.index') }}"
               class="nav-link-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('mascotas.*') ? 'nav-item-active text-white' : '' }}">
                <span class="text-lg">🐶</span>
                <span class="text-sm font-medium">Mascotas</span>
            </a>

            <a href="{{ route('citas.index') }}"
               class="nav-link-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('citas.*') ? 'nav-item-active text-white' : '' }}">
                <svg class="w-5 h-5 text-violet-400 group-hover:text-violet-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-sm font-medium">Citas</span>
            </a>

            <a href="{{ route('productos.index') }}"
               class="nav-link-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('productos.*') ? 'nav-item-active text-white' : '' }}">
                <svg class="w-5 h-5 text-amber-400 group-hover:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span class="text-sm font-medium">Productos</span>
            </a>

            <a href="{{ route('ventas.index') }}"
               class="nav-link-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-300 hover:text-white group {{ request()->routeIs('ventas.*') ? 'nav-item-active text-white' : '' }}">
                <svg class="w-5 h-5 text-rose-400 group-hover:text-rose-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
                </svg>
                <span class="text-sm font-medium">Ventas</span>
            </a>

        </nav>

        <!-- User Info (bottom) -->
        <div class="px-4 py-4 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <!-- User Info button triggers profile modal -->
                <button type="button" @click="profileOpen = true" class="flex-1 min-w-0 flex flex-col text-left hover:bg-white/5 rounded-lg p-1 -ml-1 transition-colors group">
                    <p class="text-white text-sm font-medium truncate group-hover:text-sky-300 transition-colors">{{ Auth::user()->name }}</p>
                    <p class="text-slate-400 text-xs truncate">{{ Auth::user()->email }}</p>
                </button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Cerrar sesión"
                            class="text-slate-400 hover:text-rose-400 transition-colors group">
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
        <main class="flex-1 overflow-y-auto bg-slate-50 p-6">
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

</div>{{-- /Alpine x-data profileOpen --}}

</body>
</html>
