    <div class="max-w-4xl mx-auto space-y-6">

        @php
            $estadoConfig = match($cita->estado ?? 'pendiente') {
                'pendiente'  => ['class' => 'bg-amber-100 text-amber-700 border-amber-200',   'label' => 'Pendiente',  'emoji' => '⏳'],
                'confirmada' => ['class' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'label' => 'Confirmada', 'emoji' => '✅'],
                'completada' => ['class' => 'bg-sky-100 text-sky-700 border-sky-200',         'label' => 'Completada', 'emoji' => '🏁'],
                'cancelada'  => ['class' => 'bg-rose-100 text-rose-700 border-rose-200',     'label' => 'Cancelada',  'emoji' => '❌'],
                default      => ['class' => 'bg-slate-100 text-slate-600 border-slate-200',  'label' => ucfirst($cita->estado ?? ''), 'emoji' => '📅'],
            };
        @endphp

        <!-- Cita Summary Badge -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4">
            <div class="flex items-center gap-4 flex-wrap">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-slate-800">{{ $cita->tipo_servicio ?? 'Consulta general' }}</h3>
                    <p class="text-sm text-slate-500 mt-0.5 truncate">
                        {{ $cita->mascota->nombre ?? '—' }}
                        @if($cita->mascota) · {{ $cita->mascota->especie }} @endif
                        · {{ $cita->cliente->nombre ?? '' }} {{ $cita->cliente->apellido ?? '' }}
                    </p>
                </div>
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs text-slate-400">Fecha</p>
                        <p class="text-sm font-bold text-slate-700">{{ $cita->fecha->format('d/m/Y') }} · {{ $cita->hora }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full border {{ $estadoConfig['class'] }}">
                        {{ $estadoConfig['emoji'] }} {{ $estadoConfig['label'] }}
                    </span>
                </div>
            </div>
            
            @if($cita->motivo)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-sm text-slate-500 font-medium mb-1">Motivo / Notas:</p>
                    <p class="text-sm text-slate-700">{{ $cita->motivo }}</p>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Panel Enviar Confirmación -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r from-violet-500 to-purple-600 px-5 py-4">
                        <h3 class="text-white font-bold text-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Enviar Confirmación
                        </h3>
                    </div>
                    
                    <form method="POST" action="{{ route('citas.notificar', $cita) }}" class="p-5 space-y-4" onsubmit="this.querySelector('button[type=submit]').disabled = true; this.querySelector('button[type=submit]').innerHTML = 'Enviando...'">
                        @csrf
                        <input type="hidden" name="from_modal" value="1">
                        
                        <div class="space-y-3">
                            <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition-colors {{ empty($cita->cliente->telefono) ? 'opacity-50' : '' }}">
                                <input type="checkbox" name="canales[]" value="whatsapp" class="mt-1 w-4 h-4 rounded text-violet-600 border-slate-300 focus:ring-violet-500" {{ empty($cita->cliente->telefono) ? 'disabled' : '' }}>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">WhatsApp</p>
                                    <p class="text-xs text-slate-500">{{ $cita->cliente->telefono ?: 'No registrado' }}</p>
                                </div>
                            </label>

                            <label class="flex items-start gap-3 p-3 border rounded-xl cursor-pointer hover:bg-slate-50 transition-colors {{ empty($cita->cliente->email) ? 'opacity-50' : '' }}">
                                <input type="checkbox" name="canales[]" value="email" class="mt-1 w-4 h-4 rounded text-violet-600 border-slate-300 focus:ring-violet-500" {{ empty($cita->cliente->email) ? 'disabled' : '' }}>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Correo Electrónico</p>
                                    <p class="text-xs text-slate-500">{{ $cita->cliente->email ?: 'No registrado' }}</p>
                                </div>
                            </label>
                        </div>
                        
                        <button type="submit" class="w-full justify-center inline-flex items-center gap-2 px-4 py-2.5 bg-violet-600 text-white text-sm font-semibold rounded-xl hover:bg-violet-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
                            </svg>
                            Enviar Notificación
                        </button>
                    </form>
                </div>
            </div>

            <!-- Historial de Confirmaciones -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Historial de Notificaciones</h3>
                            <p class="text-sm text-slate-500">Registro de mensajes enviados a este cliente</p>
                        </div>
                    </div>
                    
                    <div class="p-0">
                        @if($cita->confirmaciones->isEmpty())
                            <div class="p-8 text-center">
                                <div class="w-16 h-16 mx-auto mb-3 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-500 text-sm">No se han enviado notificaciones para esta cita.</p>
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider border-b border-slate-100">
                                            <th class="px-6 py-3 font-semibold">Fecha/Hora</th>
                                            <th class="px-6 py-3 font-semibold">Canal</th>
                                            <th class="px-6 py-3 font-semibold">Destinatario</th>
                                            <th class="px-6 py-3 font-semibold">Estado</th>
                                            <th class="px-6 py-3 text-right font-semibold">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100">
                                        @foreach($cita->confirmaciones()->orderByDesc('created_at')->get() as $conf)
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-6 py-4">
                                                    <p class="text-sm font-medium text-slate-800">{{ $conf->created_at->format('d/m/Y') }}</p>
                                                    <p class="text-xs text-slate-500">{{ $conf->created_at->format('H:i') }} hrs</p>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-700">
                                                        @if($conf->canal === 'whatsapp')
                                                            <span class="text-emerald-500">📱</span> WhatsApp
                                                        @else
                                                            <span class="text-violet-500">📧</span> Email
                                                        @endif
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 text-sm text-slate-600">
                                                    {{ $conf->destinatario }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($conf->estado === 'enviado' || $conf->estado === 'entregado')
                                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-full">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            {{ ucfirst($conf->estado) }}
                                                        </span>
                                                    @elseif($conf->estado === 'error')
                                                        <div class="flex flex-col gap-1 items-start">
                                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-200 px-2 py-1 rounded-full" title="{{ $conf->mensaje_error }}">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                Error
                                                            </span>
                                                            <p class="text-[10px] text-rose-500 max-w-[150px] truncate" title="{{ $conf->mensaje_error }}">{{ $conf->mensaje_error }}</p>
                                                        </div>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-1 rounded-full">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                            Pendiente
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    @if($conf->estado === 'error')
                                                        <form method="POST" action="{{ route('confirmaciones.reintentar', $conf) }}" class="inline-block" onsubmit="this.querySelector('button[type=submit]').disabled = true;">
                                                            @csrf
                                                            <input type="hidden" name="from_modal" value="1">
                                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Reintentar envío">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-slate-300 text-xs">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
