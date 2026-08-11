<!-- Modal Detalles de Cita -->
<div x-data="modalVerCita()" 
     x-cloak
     @ver-cita.window="abrir($event.detail.id)"
     @keydown.escape.window="cerrar()">
    
    <!-- Backdrop -->
    <div x-show="abierto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100]" 
         @click="cerrar()"></div>

    <!-- Modal Panel -->
    <div x-show="abierto" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-[110] overflow-y-auto">
         
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-slate-50 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-slate-100">
                
                <!-- Header -->
                <div class="bg-white px-6 py-4 flex items-center justify-between border-b border-slate-100">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800">Detalles de la Cita</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Consulta la información y notificaciones de esta cita</p>
                    </div>
                    <button type="button" @click="cerrar()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Body (Cargado por AJAX) -->
                <div class="p-6 relative min-h-[300px]">
                    <div x-show="cargando" class="absolute inset-0 bg-slate-50/50 flex flex-col items-center justify-center z-10">
                        <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-violet-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="text-slate-500 font-medium">Cargando detalles...</p>
                    </div>
                    
                    <div x-html="htmlContent" x-show="!cargando"></div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function modalVerCita() {
        return {
            abierto: false,
            cargando: false,
            htmlContent: '',
            
            abrir(id) {
                this.abierto = true;
                this.cargando = true;
                this.htmlContent = '';
                
                fetch(`/citas/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.htmlContent = data.html;
                })
                .catch(err => {
                    this.htmlContent = '<div class="text-center text-rose-500 py-8">Ocurrió un error al cargar la información. Intenta de nuevo.</div>';
                })
                .finally(() => {
                    this.cargando = false;
                });
            },
            
            cerrar() {
                this.abierto = false;
            },

            init() {
                @if(session('abrir_detalle'))
                    // Abrir automáticamente si hay una sesión que lo requiere (al redirigir después de notificar)
                    setTimeout(() => {
                        this.abrir({{ session('abrir_detalle') }});
                    }, 500);
                @endif
            }
        }
    }
</script>
