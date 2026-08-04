{{--
    LOADING SCREEN — PERRO CORRIENDO
    Uso: incluir en app.blade.php dentro del scope Alpine global.
    Controlar con: $dispatch('loading', true) / $dispatch('loading', false)
    O bien con la variable Alpine `pageLoading` del layout.
--}}
<div
    x-cloak
    x-show="pageLoading"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center cursor-wait"
    style="background: rgba(248,250,252,0.55); backdrop-filter: blur(2px); pointer-events: all;"
>
    <div class="flex flex-col items-center gap-4 select-none">

        {{-- ─── DOG SVG RUNNER ─── --}}
        <div class="dog-loader" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 60" width="200" height="100">
                <style>
                    /* ── Ground dots ── */
                    .ground-dot { animation: ground-scroll 0.55s linear infinite; fill: #cbd5e1; rx: 2; }
                    .ground-dot:nth-child(2) { animation-delay: -0.275s; }
                    @keyframes ground-scroll {
                        0%   { transform: translateX(0); opacity: 1; }
                        100% { transform: translateX(-30px); opacity: 0; }
                    }

                    /* ── Dog body bounce ── */
                    .dog-body { animation: body-bounce 0.5s ease-in-out infinite alternate; transform-origin: 60px 30px; }
                    @keyframes body-bounce {
                        0%   { transform: translateY(0) rotate(-2deg); }
                        100% { transform: translateY(-4px) rotate(2deg); }
                    }

                    /* ── Front legs ── */
                    .leg-front-1 { animation: leg-f1 0.5s linear infinite; transform-origin: 52px 36px; }
                    .leg-front-2 { animation: leg-f2 0.5s linear infinite; transform-origin: 56px 36px; }
                    @keyframes leg-f1 {
                        0%,100% { transform: rotate(-35deg); }
                        50%     { transform: rotate(25deg); }
                    }
                    @keyframes leg-f2 {
                        0%,100% { transform: rotate(25deg); }
                        50%     { transform: rotate(-35deg); }
                    }

                    /* ── Rear legs ── */
                    .leg-rear-1 { animation: leg-r1 0.5s linear infinite; transform-origin: 72px 36px; }
                    .leg-rear-2 { animation: leg-r2 0.5s linear infinite; transform-origin: 76px 36px; }
                    @keyframes leg-r1 {
                        0%,100% { transform: rotate(25deg); }
                        50%     { transform: rotate(-35deg); }
                    }
                    @keyframes leg-r2 {
                        0%,100% { transform: rotate(-35deg); }
                        50%     { transform: rotate(25deg); }
                    }

                    /* ── Tail wag ── */
                    .dog-tail { animation: tail-wag 0.5s ease-in-out infinite alternate; transform-origin: 80px 30px; }
                    @keyframes tail-wag {
                        0%   { transform: rotate(-20deg); }
                        100% { transform: rotate(15deg); }
                    }

                    /* ── Ear flop ── */
                    .dog-ear { animation: ear-flop 0.5s ease-in-out infinite alternate; transform-origin: 42px 22px; }
                    @keyframes ear-flop {
                        0%   { transform: rotate(-5deg); }
                        100% { transform: rotate(10deg); }
                    }
                </style>

                <!-- Ground scroll dots -->
                <g>
                    <rect class="ground-dot" x="10"  y="52" width="8" height="3" rx="1.5"/>
                    <rect class="ground-dot" x="40"  y="52" width="8" height="3" rx="1.5"/>
                    <rect class="ground-dot" x="70"  y="52" width="8" height="3" rx="1.5"/>
                    <rect class="ground-dot" x="100" y="52" width="8" height="3" rx="1.5"/>
                </g>

                <!-- Dog group (all parts move together on body bounce) -->
                <g class="dog-body">
                    <!-- Tail -->
                    <g class="dog-tail">
                        <path d="M82 28 Q92 18 96 14" stroke="#0f4c75" stroke-width="4" stroke-linecap="round" fill="none"/>
                    </g>

                    <!-- Body -->
                    <ellipse cx="63" cy="33" rx="18" ry="10" fill="#1b7fc4"/>

                    <!-- Rear legs -->
                    <g class="leg-rear-1">
                        <rect x="70" y="36" width="4" height="14" rx="2" fill="#0f4c75"/>
                    </g>
                    <g class="leg-rear-2">
                        <rect x="74" y="36" width="4" height="14" rx="2" fill="#1b7fc4"/>
                    </g>

                    <!-- Front legs -->
                    <g class="leg-front-1">
                        <rect x="50" y="36" width="4" height="14" rx="2" fill="#0f4c75"/>
                    </g>
                    <g class="leg-front-2">
                        <rect x="54" y="36" width="4" height="14" rx="2" fill="#1b7fc4"/>
                    </g>

                    <!-- Neck -->
                    <ellipse cx="47" cy="28" rx="7" ry="8" fill="#1b7fc4"/>

                    <!-- Head -->
                    <ellipse cx="40" cy="22" rx="11" ry="9" fill="#1b7fc4"/>

                    <!-- Ear -->
                    <g class="dog-ear">
                        <ellipse cx="35" cy="16" rx="5" ry="8" fill="#0f4c75" transform="rotate(-15,35,16)"/>
                    </g>

                    <!-- Eye -->
                    <circle cx="36" cy="21" r="2" fill="white"/>
                    <circle cx="36" cy="21" r="1.1" fill="#0c3859"/>

                    <!-- Nose -->
                    <ellipse cx="30" cy="24" rx="3" ry="2" fill="#0c3859"/>
                    <circle cx="30" cy="23" r="0.8" fill="white" opacity="0.5"/>

                    <!-- Mouth / tongue -->
                    <path d="M29 26 Q31 29 33 27" stroke="#e11d48" stroke-width="1.5" stroke-linecap="round" fill="none"/>

                    <!-- Collar -->
                    <rect x="41" y="31" width="12" height="4" rx="2" fill="#4fc3f7"/>
                    <circle cx="47" cy="33" r="1.5" fill="#fbbf24"/>
                </g>
            </svg>
        </div>

        {{-- ─── Text ─── --}}
        <div class="flex items-center gap-2">
            <p class="text-sm font-semibold" style="color:#0f4c75; letter-spacing:.04em;">Procesando</p>
            <span class="loading-dots flex gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-bounce" style="animation-delay:0s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-bounce" style="animation-delay:.15s"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-sky-500 animate-bounce" style="animation-delay:.3s"></span>
            </span>
        </div>

    </div>
</div>
