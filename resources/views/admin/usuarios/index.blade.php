<x-app-layout>
    <x-slot name="header">
        <style>
            .btn-custom {
                border: none;
                color: #fff;
                background-image: linear-gradient(30deg, #ff007f, #ffb6c1);
                /* rosado */
                border-radius: 0.75rem;
                background-size: 100% auto;
                font-family: inherit;
                transition: all 0.3s ease;
            }

            .btn-custom:hover {
                background-position: right center;
                background-size: 200% auto;
                -webkit-animation: pulse 2s infinite;
                animation: pulse512 1.5s infinite;
            }

            @keyframes pulse512 {
                0% {
                    box-shadow: 0 0 0 0 #ff007f66;
                }

                70% {
                    box-shadow: 0 0 0 10px rgba(255, 0, 127, 0);
                }

                100% {
                    box-shadow: 0 0 0 0 rgba(255, 0, 127, 0);
                }
            }

            /* Animations for action icons */
            .icon-edit-btn:hover .pen-group {
                animation: pen-wiggle 0.8s ease-in-out;
                transform-origin: center;
            }

            .icon-edit-btn:hover .pen-slash {
                animation: pen-slash-draw 0.5s ease-in-out;
            }

            .pen-slash {
                stroke-dasharray: 15;
                stroke-dashoffset: 15;
                opacity: 0;
            }

            @keyframes pen-wiggle {

                0%,
                100% {
                    transform: translate(0, 0) rotate(0);
                }

                20% {
                    transform: translate(1px, -2px) rotate(-6deg);
                }

                40% {
                    transform: translate(-1px, -4px) rotate(-4deg);
                }

                60% {
                    transform: translate(1px, -6px) rotate(-6deg);
                }

                80% {
                    transform: translate(-1px, -8px) rotate(-4deg);
                }
            }

            @keyframes pen-slash-draw {
                0% {
                    stroke-dashoffset: 15;
                    opacity: 0;
                }

                50% {
                    stroke-dashoffset: 0;
                    opacity: 1;
                }

                100% {
                    stroke-dashoffset: 15;
                    opacity: 0;
                }
            }

            .icon-deactivate-btn:hover .triangle {
                animation: triangle-bounce 0.25s ease-out;
            }

            .icon-deactivate-btn:hover .exclamation-line {
                animation: line-stretch 0.3s ease-out;
                transform-origin: 12px 11px;
            }

            .icon-deactivate-btn:hover .exclamation-dot {
                animation: dot-pulse 0.25s 0.05s ease-out;
                transform-origin: 12px 17px;
            }

            @keyframes triangle-bounce {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-1.5px);
                }
            }

            @keyframes line-stretch {

                0%,
                100% {
                    transform: scaleY(1);
                }

                50% {
                    transform: scaleY(1.35);
                }
            }

            @keyframes dot-pulse {

                0%,
                100% {
                    transform: scale(1);
                    opacity: 1;
                }

                50% {
                    transform: scale(1.4);
                    opacity: 0.6;
                }
            }

            .icon-activate-btn:hover svg {
                animation: svg-scale 0.3s ease-in-out;
            }

            .icon-activate-btn:hover .filled-circle {
                animation: circle-pulse 0.45s ease-out;
                transform-origin: center;
            }

            @keyframes svg-scale {

                0%,
                100% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.1);
                }
            }

            @keyframes circle-pulse {

                0%,
                100% {
                    transform: scale(1);
                    opacity: 1;
                }

                33% {
                    transform: scale(1.15);
                    opacity: 0.8;
                }
            }

            .icon-reset-btn:hover .lock-upper-body {
                animation: lock-open 0.28s ease-out forwards;
            }
            .lock-upper-body {
                transform-origin: 50% 100%;
                transition: transform 0.22s ease-in-out;
            }
            @keyframes lock-open {
                0% { transform: translate(0, 0) rotate(0); }
                100% { transform: translate(3px, -1.7px) rotate(40deg); }
            }
        </style>
        <div class="flex items-center justify-start gap-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Usuarios y Roles</h2>
                <p class="text-sm text-slate-500 mt-0.5">Gestiona los usuarios del sistema y sus permisos</p>
            </div>
            <button type="button" @click="$dispatch('abrir-crear-usuario')"
                class="btn-custom inline-flex items-center gap-3 text-sm font-semibold px-5 py-2.5 shadow-sm active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Usuario
            </button>
        </div>
    </x-slot>

    {{-- Filtros --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" name="buscar" value="{{ request('buscar') }}"
                    placeholder="Buscar por nombre o correo..."
                    class="w-full pl-12 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-slate-50 transition-all">
            </div>
            <select name="rol"
                class="pl-4 pr-12 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 text-slate-700">
                <option value="">Todos los roles</option>
                <option value="admin" {{ request('rol') === 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="recepcionista" {{ request('rol') === 'recepcionista' ? 'selected' : '' }}>Recepcionista
                </option>
            </select>
            <select name="estado"
                class="pl-4 pr-12 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 text-slate-700">
                <option value="">Todos los estados</option>
                <option value="activo" {{ request('estado') === 'activo' ? 'selected' : '' }}>Activos</option>
                <option value="inactivo" {{ request('estado') === 'inactivo' ? 'selected' : '' }}>Inactivos</option>
            </select>
            <button type="submit" class="btn-custom flex items-center justify-center px-5 py-2.5 text-sm font-semibold">
                Filtrar
            </button>
            @if(request()->hasAny(['buscar', 'rol', 'estado']))
                <a href="{{ route('admin.usuarios.index') }}"
                    class="px-5 py-2.5 inline-flex items-center justify-center border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    Limpiar
                </a>
            @endif
        </form>
    </div>

    {{-- Tabla de Usuarios --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        @if($usuarios->isNotEmpty())
            <div class="px-6 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                <p class="text-xs text-slate-500 font-medium">
                    Mostrando <span class="text-slate-800 font-bold">{{ $usuarios->count() }}</span> de
                    <span class="text-slate-800 font-bold">{{ $usuarios->total() }}</span> usuarios
                </p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider bg-slate-50">
                        <th class="text-left px-6 py-3.5 font-semibold">Usuario</th>
                        <th class="text-left px-6 py-3.5 font-semibold">Rol</th>
                        <th class="text-left px-6 py-3.5 font-semibold hidden md:table-cell">Último acceso</th>
                        <th class="text-center px-6 py-3.5 font-semibold">Estado</th>
                        <th class="text-center px-6 py-3.5 font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($usuarios as $usuario)
                        <tr
                            class="hover:bg-slate-50/80 transition-colors {{ $usuario->id === auth()->id() ? 'bg-blue-50/30' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-800 text-sm">
                                            {{ $usuario->name }}
                                            @if($usuario->id === auth()->id())
                                                <span class="text-xs text-blue-500 font-normal ml-1">(tú)</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-400">{{ $usuario->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($usuario->role === 'admin')
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-blue-100 text-blue-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M12 1l3.09 6.26L22 8.27l-5 4.87 1.18 6.88L12 16.77l-6.18 3.25L7 13.14 2 8.27l6.91-1.01z" />
                                        </svg>
                                        Administrador
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Recepcionista
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 hidden md:table-cell">
                                <p class="text-sm text-slate-600">
                                    {{ $usuario->last_login_at ? $usuario->last_login_at->diffForHumans() : 'Nunca' }}
                                </p>
                                @if($usuario->last_login_at)
                                    <p class="text-xs text-slate-400">{{ $usuario->last_login_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($usuario->activo)
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Activo
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-rose-100 text-rose-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-400"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Editar --}}
                                    <button type="button" title="Editar usuario" @click="$dispatch('abrir-editar-usuario', {
                                                    id: {{ $usuario->id }},
                                                    name: '{{ addslashes($usuario->name) }}',
                                                    email: '{{ $usuario->email }}',
                                                    role: '{{ $usuario->role }}'
                                                })"
                                        class="icon-edit-btn p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"
                                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="square"
                                            stroke-miterlimit="10" style="overflow: visible;">
                                            <g class="pen-group">
                                                <path class="pen-slash" d="M20 6 L26 12" />
                                                <path class="pen-body"
                                                    d="m10.5,27.5l-8,2 2-8L22.257,3.743c1.657-1.657,4.343-1.657,6,0s1.657,4.343,0,6L10.5,27.5Z" />
                                            </g>
                                        </svg>
                                    </button>

                                    {{-- Activar/Desactivar --}}
                                    @if($usuario->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.usuarios.toggle-activo', $usuario) }}"
                                            x-data
                                            @submit.prevent="if(confirm('{{ $usuario->activo ? '¿Desactivar a ' . $usuario->name . '? No podrá iniciar sesión.' : '¿Reactivar a ' . $usuario->name . '?' }}')) $el.submit()">
                                            @csrf
                                            <button type="submit"
                                                title="{{ $usuario->activo ? 'Desactivar usuario' : 'Activar usuario' }}"
                                                class="{{ $usuario->activo ? 'icon-deactivate-btn hover:text-rose-600 hover:bg-rose-50' : 'icon-activate-btn hover:text-emerald-600 hover:bg-emerald-50' }} p-1.5 text-slate-400 rounded-lg transition-colors">
                                                @if($usuario->activo)
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                        stroke-linejoin="round">
                                                        <path class="triangle"
                                                            d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                                                        <path class="exclamation-line" d="M12 9v4" />
                                                        <path class="exclamation-dot" d="M12 17h.01" />
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                        fill="currentColor" stroke="none">
                                                        <path class="filled-circle"
                                                            d="M17 3.34a10 10 0 1 1 -14.995 8.984l-.005 -.324l.005 -.324a10 10 0 0 1 14.995 -8.336z" />
                                                        <path
                                                            d="M15.707 9.293a1 1 0 0 0 -1.32 -.083l-.094 .083l-3.293 3.292l-1.293 -1.292l-.094 -.083a1 1 0 0 0 -1.403 1.403l.083 .094l2 2l.094 .083a1 1 0 0 0 1.226 0l.094 -.083l4 -4l.083 -.094a1 1 0 0 0 -.083 -1.32z"
                                                            fill="white" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Restablecer contraseña --}}
                                    <button type="button" title="Restablecer contraseña"
                                        @click="$dispatch('abrir-restablecer-password', {
                                            id: {{ $usuario->id }},
                                            name: '{{ addslashes($usuario->name) }}'
                                        })"
                                        class="icon-reset-btn p-1.5 text-slate-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="overflow: visible;">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                                            <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                                            <path class="lock-upper-body" d="M8 11v-4a4 4 0 1 1 8 0v4" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-5xl mb-4">👤</span>
                                    <h3 class="text-base font-semibold text-slate-700 mb-1">No se encontraron usuarios</h3>
                                    <p class="text-sm text-slate-400">Intenta con otros filtros o crea un nuevo usuario.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($usuarios->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>

    {{-- Modal Crear Usuario --}}
    <div x-data="{ open: false, loading: false, pass: '', conf: '', showPass: false, showConf: false }" x-cloak @abrir-crear-usuario.window="open = true"
        @keydown.escape.window="open = false">

        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100]" @click="open = false"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="fixed inset-0 z-[110] overflow-y-auto flex items-center justify-center p-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md border border-slate-100">
                <div
                    class="bg-gradient-to-r from-blue-600 to-sky-500 px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h3 class="text-white font-bold text-lg">Nuevo Usuario</h3>
                    <button @click="open = false" class="text-white/70 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.usuarios.store') }}" @submit="loading = true"
                    class="p-6 space-y-4" autocomplete="off">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nombre completo *</label>
                        <input type="text" name="name" required minlength="3" maxlength="100" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+" title="El nombre solo debe contener letras y espacios" autocomplete="off"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50"
                            placeholder="Ej. María González">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico *</label>
                        <input type="email" name="email" required maxlength="255" autocomplete="off" oninput="this.value = this.value.toLowerCase()"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50"
                            placeholder="correo@ejemplo.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Rol *</label>
                        <select name="role" required autocomplete="off"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                            <option value="recepcionista">Recepcionista</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña *</label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'" name="password" required minlength="8" autocomplete="new-password"
                                x-model="pass" @input="$refs.conf.setCustomValidity(pass !== conf ? 'Las contraseñas no coinciden' : '')"
                                class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 pr-10"
                                placeholder="Mínimo 8 caracteres">
                            <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg x-show="!showPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Confirmar contraseña *</label>
                        <div class="relative">
                            <input :type="showConf ? 'text' : 'password'" name="password_confirmation" required minlength="8" autocomplete="new-password"
                                x-model="conf" x-ref="conf" @input="$refs.conf.setCustomValidity(pass !== conf ? 'Las contraseñas no coinciden' : '')"
                                class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 pr-10"
                                placeholder="Repite la contraseña">
                            <button type="button" @click="showConf = !showConf" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg x-show="!showConf" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showConf" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="open = false"
                            class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="loading"
                            class="flex-1 px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors disabled:opacity-60">
                            <span x-show="!loading">Crear Usuario</span>
                            <span x-show="loading">Creando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Editar Usuario --}}
    <div x-data="{ open: false, loading: false, userId: null, userName: '', userEmail: '', userRole: '' }" x-cloak
        @abrir-editar-usuario.window="open = true; userId = $event.detail.id; userName = $event.detail.name; userEmail = $event.detail.email; userRole = $event.detail.role"
        @keydown.escape.window="open = false">

        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100]"
            @click="open = false"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="fixed inset-0 z-[110] overflow-y-auto flex items-center justify-center p-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md border border-slate-100">
                <div
                    class="bg-gradient-to-r from-amber-500 to-orange-400 px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h3 class="text-white font-bold text-lg">Editar Usuario</h3>
                    <button @click="open = false" class="text-white/70 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form method="POST" :action="`/admin/usuarios/${userId}`" @submit="loading = true"
                    class="p-6 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nombre completo *</label>
                        <input type="text" name="name" x-model="userName" required maxlength="100"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Correo electrónico *</label>
                        <input type="email" name="email" x-model="userEmail" required maxlength="255"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 bg-slate-50">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Rol *</label>
                        <select name="role" x-model="userRole" required
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 bg-slate-50">
                            <option value="recepcionista">Recepcionista</option>
                            <option value="admin">Administrador</option>
                        </select>
                        <p class="text-xs text-amber-600 mt-1">⚠️ Cambiar el rol modifica inmediatamente las secciones
                            visibles para el usuario.</p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="open = false"
                            class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="loading"
                            class="flex-1 px-4 py-2.5 bg-amber-500 text-white text-sm font-semibold rounded-xl hover:bg-amber-600 transition-colors disabled:opacity-60">
                            <span x-show="!loading">Guardar Cambios</span>
                            <span x-show="loading">Guardando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Restablecer Contraseña --}}
    <div x-data="{ 
            open: false, 
            loading: false, 
            userId: null, 
            userName: '', 
            newPass: '', 
            adminPass: '', 
            showNewPass: false, 
            showAdminPass: false,
            errorMessage: '',
            successMessage: '',
            async submitForm() {
                this.loading = true;
                this.errorMessage = '';
                this.successMessage = '';
                try {
                    const response = await fetch(`/admin/usuarios/${this.userId}/reset-password`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                        },
                        body: JSON.stringify({
                            new_password: this.newPass,
                            admin_password: this.adminPass
                        })
                    });
                    const result = await response.json();
                    if (!response.ok) {
                        if (result.errors) {
                            this.errorMessage = Object.values(result.errors)[0][0];
                        } else if (result.error) {
                            this.errorMessage = result.error;
                        } else if (result.message) {
                            this.errorMessage = result.message;
                        } else {
                            this.errorMessage = 'Ocurrió un error al restablecer la contraseña.';
                        }
                    } else {
                        this.successMessage = result.message || 'Contraseña restablecida correctamente.';
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                } catch (error) {
                    this.errorMessage = 'Error de conexión. Inténtalo de nuevo.';
                } finally {
                    this.loading = false;
                }
            }
        }" 
        x-cloak
        @abrir-restablecer-password.window="open = true; userId = $event.detail.id; userName = $event.detail.name; newPass = ''; adminPass = ''; errorMessage = ''; successMessage = ''"
        @keydown.escape.window="open = false">

        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100]"
            @click="open = false"></div>

        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            class="fixed inset-0 z-[110] overflow-y-auto flex items-center justify-center p-4">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md border border-slate-100">
                <div
                    class="bg-gradient-to-r from-violet-500 to-fuchsia-500 px-6 py-4 rounded-t-2xl flex items-center justify-between">
                    <h3 class="text-white font-bold text-lg">Restablecer Contraseña</h3>
                    <button @click="open = false" class="text-white/70 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitForm" class="p-6 space-y-4">
                    
                    <div x-show="errorMessage" x-cloak class="p-3 bg-rose-50 text-rose-700 text-sm rounded-lg border border-rose-200 flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span x-text="errorMessage"></span>
                    </div>

                    <div x-show="successMessage" x-cloak class="p-3 bg-emerald-50 text-emerald-700 text-sm rounded-lg border border-emerald-200 flex items-start gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span x-text="successMessage"></span>
                    </div>

                    <p class="text-sm text-slate-600 mb-2">Restableciendo contraseña para: <span class="font-bold text-slate-800" x-text="userName"></span></p>
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nueva contraseña para el usuario *</label>
                        <div class="relative">
                            <input :type="showNewPass ? 'text' : 'password'" name="new_password" x-model="newPass" required minlength="8"
                                class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 bg-slate-50 pr-10"
                                placeholder="Mínimo 8 caracteres">
                            <button type="button" @click="showNewPass = !showNewPass" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg x-show="!showNewPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showNewPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Contraseña de Administrador *</label>
                        <div class="relative">
                            <input :type="showAdminPass ? 'text' : 'password'" name="admin_password" x-model="adminPass" required
                                class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-violet-500 bg-slate-50 pr-10"
                                placeholder="Confirma tu contraseña">
                            <button type="button" @click="showAdminPass = !showAdminPass" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg x-show="!showAdminPass" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showAdminPass" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="open = false"
                            class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" :disabled="loading"
                            class="flex-1 px-4 py-2.5 bg-violet-500 text-white text-sm font-semibold rounded-xl hover:bg-violet-600 transition-colors disabled:opacity-60">
                            <span x-show="!loading">Restablecer</span>
                            <span x-show="loading">Procesando...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>