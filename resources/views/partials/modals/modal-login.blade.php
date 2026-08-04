{{-- ═══════════════════════════════════════════════════════════
     MODAL 1 – INICIAR SESIÓN
     Controlado por Alpine.js: activeModal === 'login'
     Formulario enviado a la ruta POST /login de Breeze.
═══════════════════════════════════════════════════════════ --}}

{{-- Capa de backdrop + modal. x-cloak evita flash antes de Alpine --}}
<div
    x-cloak
    x-show="activeModal === 'login'"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="activeModal = null"
>
    {{-- Backdrop oscuro --}}
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter: blur(4px);" @click="activeModal = null"></div>

    {{-- Panel del modal --}}
    <div
        x-show="activeModal === 'login'"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 translate-y-6 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-6 scale-95"
        class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-hidden"
        style="max-height: 80vh; overflow-y: auto;"
        @click.stop
    >
        {{-- Barra decorativa superior --}}
        <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #38bdf8, #0ea5e9, #059669);"></div>

        <div class="px-7 py-5">
            {{-- Encabezado --}}
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background:linear-gradient(135deg,rgba(186,230,253,0.70),rgba(167,243,208,0.70)); border:1.5px solid rgba(56,189,248,0.35);">
                        <svg class="w-5 h-5" style="color:#0ea5e9" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 7.5c-1.5 0-2 1.5-2 2.5 0 1.5 1 2.5 2 2.5s2-1 2-2.5c0-1-.5-2.5-2-2.5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.5c-2.5 0-4.5 1.5-5.5 3.5-.5 1-1 2-1 3.5 0 2.5 2.5 4.5 6.5 4.5s6.5-2 6.5-4.5c0-1.5-.5-2.5-1-3.5-1-2-3-3.5-5.5-3.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold" style="color:#0c4a6e;">Iniciar Sesión</h2>
                        <p class="text-xs" style="color:#64748b;">VetCare · Portal Interno</p>
                    </div>
                </div>
                <button type="button" @click="activeModal = null"
                        class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-slate-100 transition-colors"
                        style="color:#94a3b8;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Mensaje de éxito si viene de restablecer contraseña --}}
            @if (session('success_modal') === 'reset_success' || session('success_message'))
                <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium" style="background:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
                    ✅ {{ session('success_message') }}
                </div>
            @endif

            {{-- Alerta de error si hubo problema de sesión (419) o credenciales --}}
            @if ($errors->any())
                <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2.5" style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b;">
                    <span>⚠️</span>
                    <span>{{ $errors->first('email') ?: $errors->first() }}</span>
                </div>
            @endif

            {{-- Formulario Login → POST /login de Breeze con URL relativa para evitar problemas de cookie domain / CORS --}}
            <form method="POST" action="{{ route('login', [], false) }}"
                  role="presentation"
                  autocomplete="off"
                  autocorrect="off"
                  autocapitalize="off"
                  spellcheck="false"
                  x-data="{
                      email: @js(old('email', '')),
                      password: '',
                      sending: false,
                      errors: {
                          email: @js($errors->first('email') ?: ''),
                          password: @js($errors->first('password') ?: '')
                      },
                      init() {
                          this.$watch('activeModal', val => {
                              if (val !== 'login') {
                                  this.email = '';
                                  this.password = '';
                                  this.errors.email = '';
                                  this.errors.password = '';
                                  this.sending = false;
                              }
                          });
                      },
                      validateEmail() {
                          this.errors.email = window.VetCareValidators.email(this.email);
                      },
                      validatePassword() {
                          if (!this.password || this.password.trim() === '') {
                              this.errors.password = 'La contraseña es obligatoria.';
                          } else {
                              this.errors.password = '';
                          }
                      },
                      validateAll(e) {
                          this.validateEmail();
                          this.validatePassword();
                          if (this.errors.email || this.errors.password) {
                              e.preventDefault();
                              this.sending = false;
                          }
                      }
                  }"
                  @submit="sending = true; validateAll($event)"
            >
                @csrf

                {{-- Honeypot: campos invisibles para engañar al autocompletado del navegador --}}
                <input type="text"     style="display:none" name="_fake_user"     autocomplete="username">
                <input type="password" style="display:none" name="_fake_pass"     autocomplete="current-password">

                {{-- Email --}}
                <div class="mb-3">
                    <label class="block text-sm font-semibold mb-1.5" style="color:#374151;" for="login_email">
                        Correo Electrónico
                    </label>
                    <input
                        type="text"
                        name="email"
                        id="login_email"
                        x-model="email"
                        @input="validateEmail()"
                        @blur="validateEmail()"
                        @focus="this.type='text'"
                        @keydown.stop
                        required
                        autocomplete="off"
                        autocorrect="off"
                        autocapitalize="off"
                        spellcheck="false"
                        onpaste="return false"
                        oncopy="return false"
                        oncut="return false"
                        placeholder="correo@vetcare.com"
                        class="w-full px-4 py-3 rounded-xl text-sm transition-all outline-none"
                        style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc;"
                        onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.12)';"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                    >
                    <p x-cloak x-show="errors.email" x-text="errors.email" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                </div>

                {{-- Contraseña con ojo animado --}}
                <div class="mb-4" x-data="{ showPwd: false }">
                    <label class="block text-sm font-semibold mb-1.5" style="color:#374151;" for="login_password">
                        Contraseña
                    </label>
                    <div class="relative">
                        <input
                            :type="showPwd ? 'text' : 'password'"
                            name="password"
                            id="login_password"
                            x-model="password"
                            @input="validatePassword()"
                            @blur="validatePassword()"
                            @keydown.stop
                            required
                            autocomplete="current-password"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            onpaste="return false"
                            oncopy="return false"
                            oncut="return false"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 pr-12 rounded-xl text-sm transition-all outline-none"
                            style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc;"
                            onfocus="this.style.borderColor='#0ea5e9'; this.style.boxShadow='0 0 0 3px rgba(14,165,233,0.12)';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"
                        >
                        <button type="button" @click="showPwd = !showPwd" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center"
                                style="color:#94a3b8;">
                            <svg x-show="!showPwd" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <svg x-show="showPwd" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p x-cloak x-show="errors.password" x-text="errors.password" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                </div>

                {{-- Botón Entrar con estado loading --}}
                <button type="submit"
                        :disabled="sending"
                        :class="{ 'opacity-60 cursor-not-allowed': sending }"
                        class="w-full py-3.5 rounded-xl font-semibold text-white text-sm transition-all flex items-center justify-center gap-2"
                        style="background:linear-gradient(135deg,#0ea5e9,#3b82f6); box-shadow:0 4px 18px rgba(14,165,233,0.30);"
                        x-on:mouseover="if(!sending){ this.style.background='linear-gradient(135deg,#38bdf8,#60a5fa)'; this.style.boxShadow='0 6px 22px rgba(14,165,233,0.45)'; }"
                        x-on:mouseout="if(!sending){ this.style.background='linear-gradient(135deg,#0ea5e9,#3b82f6)'; this.style.boxShadow='0 4px 18px rgba(14,165,233,0.30)'; }">
                    {{-- Spinner SVG --}}
                    <svg x-show="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-show="!sending">Entrar al Sistema</span>
                    <span x-show="sending">Verificando...</span>
                </button>
            </form>

            {{-- Links de texto --}}
            <div class="mt-4 flex flex-col items-center gap-2">
                <button type="button" @click="activeModal = 'reset'"
                        class="text-sm hover:underline transition-all"
                        style="color:#0ea5e9; background:none; border:none; cursor:pointer;">
                    ¿Olvidaste tu contraseña?
                </button>
                <button type="button" @click="activeModal = 'register'"
                        class="text-sm font-medium hover:underline transition-all"
                        style="color:#059669; background:none; border:none; cursor:pointer;">
                    Registrar Nuevo Usuario
                </button>
            </div>
        </div>
    </div>
</div>
