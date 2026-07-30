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

<div class="flex h-screen overflow-hidden">

    <!-- ===== SIDEBAR ===== -->
    <aside id="sidebar" class="vet-sidebar w-64 flex-shrink-0 flex flex-col transition-all duration-300 z-40">

        <!-- Logo / Brand -->
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="flex items-center justify-center w-10 h-10 bg-sky-400/20 rounded-xl paw-pulse">
                <span class="text-2xl">🐾</span>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg leading-none">VetCare</h1>
                <p class="text-sky-300 text-xs mt-0.5">Gestión Veterinaria</p>
            </div>
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
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ Auth::user()->name }}</p>
                    <p class="text-slate-400 text-xs truncate">{{ Auth::user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Cerrar sesión"
                            class="text-slate-400 hover:text-rose-400 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
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
                <button class="relative p-2 text-slate-500 hover:text-sky-600 hover:bg-sky-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
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
</div>

</body>
</html>
