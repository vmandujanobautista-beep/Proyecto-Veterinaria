{{-- ═══════════════════════════════════════════════════════════
     MODAL 2 – REGISTRAR NUEVO USUARIO
     Controlado por Alpine.js: activeModal === 'register'
     Formulario POST → AuthModalController@register
     Contraseña de admin requerida en backend.
═══════════════════════════════════════════════════════════ --}}

<div
    x-cloak
    x-show="activeModal === 'register'"
    x-transition:enter="transition ease-out duration-250"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="activeModal = null"
>
    <div class="absolute inset-0 bg-black/60" style="backdrop-filter: blur(4px);" @click="activeModal = null"></div>

    <div
        x-show="activeModal === 'register'"
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 translate-y-6 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 translate-y-6 scale-95"
        class="relative w-full max-w-xl bg-white rounded-2xl shadow-2xl overflow-y-auto"
        style="max-height: 88vh;"
        @click.stop
    >
        <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #059669, #0ea5e9, #38bdf8);"></div>

        <div class="px-7 py-4">
            {{-- Encabezado --}}
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background:linear-gradient(135deg,rgba(167,243,208,0.70),rgba(186,230,253,0.70)); border:1.5px solid rgba(52,211,153,0.35);">
                        <svg class="w-5 h-5" style="color:#059669" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/>
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4"/>
                            <path d="M16 19h6"/><path d="M19 16v6"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold" style="color:#064e3b;">Registrar Usuario</h2>
                        <p class="text-xs" style="color:#64748b;">Solo personal autorizado</p>
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

            {{-- Mensaje de éxito --}}
            @if (session('success_modal') === 'register')
                <div class="mb-5 px-4 py-3 rounded-xl text-sm font-medium" style="background:#ecfdf5; border:1px solid #6ee7b7; color:#065f46;">
                    ✅ {{ session('success_message') }}
                    <br>
                    <button type="button" @click="activeModal = 'login'"
                            class="underline mt-1 font-semibold" style="color:#059669; background:none; border:none; cursor:pointer;">
                        Ir a Iniciar Sesión →
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('modal.register', [], false) }}"
                  role="presentation"
                  autocomplete="off"
                  autocorrect="off"
                  autocapitalize="off"
                  spellcheck="false"
                  x-data="{
                      name: @js(old('name', '')),
                      email: @js(old('email', '')),
                      password: '',
                      password_confirmation: '',
                      admin_password: '',
                      sending: false,
                      errors: {
                          name: @js($errors->first('name') ?: ''),
                          email: @js($errors->first('email') ?: ''),
                          password: @js($errors->first('password') ?: ''),
                          password_confirmation: @js($errors->first('password_confirmation') ?: ''),
                          admin_password: @js($errors->first('admin_password') ?: '')
                      },
                      init() {
                          this.$watch('activeModal', val => {
                              if (val !== 'register') {
                                  this.name = '';
                                  this.email = '';
                                  this.password = '';
                                  this.password_confirmation = '';
                                  this.admin_password = '';
                                  this.errors.name = '';
                                  this.errors.email = '';
                                  this.errors.password = '';
                                  this.errors.password_confirmation = '';
                                  this.errors.admin_password = '';
                                  this.sending = false;
                              }
                          });
                      },
                      validateName() {
                          this.errors.name = window.VetCareValidators.name(this.name);
                      },
                      validateEmail() {
                          this.errors.email = window.VetCareValidators.email(this.email);
                      },
                      validatePassword() {
                          this.errors.password = window.VetCareValidators.password(this.password);
                          if (this.password_confirmation) {
                              this.validateConfirmation();
                          }
                      },
                      validateConfirmation() {
                          if (!this.password_confirmation) {
                              this.errors.password_confirmation = 'Debes confirmar la contraseña.';
                          } else if (this.password !== this.password_confirmation) {
                              this.errors.password_confirmation = 'Las contraseñas no coinciden.';
                          } else {
                              this.errors.password_confirmation = '';
                          }
                      },
                      validateAdminPassword() {
                          if (!this.admin_password || this.admin_password.trim() === '') {
                              this.errors.admin_password = 'La contraseña de administrador es obligatoria.';
                          } else {
                              this.errors.admin_password = '';
                          }
                      },
                      validateAll(e) {
                          this.validateName();
                          this.validateEmail();
                          this.validatePassword();
                          this.validateConfirmation();
                          this.validateAdminPassword();
                          if (this.errors.name || this.errors.email || this.errors.password || this.errors.password_confirmation || this.errors.admin_password) {
                              e.preventDefault();
                              this.sending = false;
                          }
                      }
                  }"
                  @submit="sending = true; validateAll($event)"
            >
                @csrf

                {{-- Honeypot: engaña al autocompletado del navegador --}}
                <input type="text"     style="display:none" name="_fake_name" autocomplete="name">
                <input type="email"    style="display:none" name="_fake_email" autocomplete="email">
                <input type="password" style="display:none" name="_fake_pw"   autocomplete="new-password">

                {{-- Nombre --}}
                <div class="mb-3">
                    <label class="block text-sm font-semibold mb-1.5" style="color:#374151;" for="reg_name">Nombre Completo</label>
                    <input type="text" name="name" id="reg_name"
                           x-model="name"
                           @input="validateName()"
                           @blur="validateName()"
                           @keydown.stop
                           required
                           autocomplete="off"
                           autocorrect="off"
                           autocapitalize="off"
                           spellcheck="false"
                           onpaste="return false"
                           oncopy="return false"
                           oncut="return false"
                           placeholder="Ej: María García"
                           class="w-full px-4 py-3 rounded-xl text-sm transition-all outline-none"
                           style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc;"
                           onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.12)';"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                    <p x-cloak x-show="errors.name" x-text="errors.name" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label class="block text-sm font-semibold mb-1.5" style="color:#374151;" for="reg_email">Correo Electrónico</label>
                    <input type="text" name="email" id="reg_email"
                           x-model="email"
                           @input="validateEmail()"
                           @blur="validateEmail()"
                           @keydown.stop
                           required
                           autocomplete="off"
                           autocorrect="off"
                           autocapitalize="off"
                           spellcheck="false"
                           onpaste="return false"
                           oncopy="return false"
                           oncut="return false"
                           placeholder="usuario@vetcare.com"
                           class="w-full px-4 py-3 rounded-xl text-sm transition-all outline-none"
                           style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc;"
                           onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.12)';"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                    <p x-cloak x-show="errors.email" x-text="errors.email" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                </div>

                {{-- Contraseña --}}
                <div class="mb-3" x-data="{ showP: false }">
                    <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">Contraseña</label>
                    <div class="relative">
                        <input :type="showP ? 'text' : 'password'" name="password" id="reg_password"
                               x-model="password"
                               @input="validatePassword()"
                               @blur="validatePassword()"
                               @keydown.stop
                               required
                               autocomplete="new-password"
                               autocorrect="off"
                               autocapitalize="off"
                               spellcheck="false"
                               onpaste="return false"
                               oncopy="return false"
                               oncut="return false"
                               placeholder="Mínimo 8 caracteres"
                               class="w-full px-4 py-3 pr-12 rounded-xl text-sm transition-all outline-none"
                               style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc;"
                               onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.12)';"
                               onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        <button type="button" @click="showP = !showP" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center" style="color:#94a3b8;">
                            <svg x-show="!showP" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="showP" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <p x-cloak x-show="errors.password" x-text="errors.password" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                </div>

                {{-- Confirmar Contraseña --}}
                <div class="mb-3" x-data="{ showP2: false }">
                    <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">Confirmar Contraseña</label>
                    <div class="relative">
                        <input :type="showP2 ? 'text' : 'password'" name="password_confirmation" id="reg_password_confirmation"
                               x-model="password_confirmation"
                               @input="validateConfirmation()"
                               @blur="validateConfirmation()"
                               @keydown.stop
                               required
                               autocomplete="new-password"
                               autocorrect="off"
                               autocapitalize="off"
                               spellcheck="false"
                               onpaste="return false"
                               oncopy="return false"
                               oncut="return false"
                               placeholder="Repite la contraseña"
                               class="w-full px-4 py-3 pr-12 rounded-xl text-sm transition-all outline-none"
                               style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc;"
                               onfocus="this.style.borderColor='#059669'; this.style.boxShadow='0 0 0 3px rgba(5,150,105,0.12)';"
                               onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                        <button type="button" @click="showP2 = !showP2" tabindex="-1"
                                class="absolute right-3 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center" style="color:#94a3b8;">
                            <svg x-show="!showP2" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <svg x-show="showP2" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <p x-cloak x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                </div>

                {{-- Contraseña de Administrador --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold mb-1.5" style="color:#374151;">Contraseña de Administrador</label>
                    <input type="password" name="admin_password" id="reg_admin_password"
                           x-model="admin_password"
                           @input="validateAdminPassword()"
                           @blur="validateAdminPassword()"
                           @keydown.stop
                           required
                           autocomplete="new-password"
                           autocorrect="off"
                           autocapitalize="off"
                           spellcheck="false"
                           onpaste="return false"
                           oncopy="return false"
                           oncut="return false"
                           placeholder="Requerida para registrar usuarios"
                           class="w-full px-4 py-3 rounded-xl text-sm transition-all outline-none"
                           style="border:1.5px solid #e2e8f0; color:#1e293b; background:#f8fafc;"
                           onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 3px rgba(245,158,11,0.12)';"
                           onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
                    <p class="mt-1.5 text-xs" style="color:#94a3b8;">⚠️ Solicita esta contraseña al administrador del sistema.</p>
                    <p x-cloak x-show="errors.admin_password" x-text="errors.admin_password" class="mt-1.5 text-xs font-semibold" style="color:#dc2626;"></p>
                </div>

                {{-- Botón Registrar con estado loading --}}
                <button type="submit"
                        :disabled="sending"
                        :class="{ 'opacity-60 cursor-not-allowed': sending }"
                        class="w-full py-3 rounded-xl font-semibold text-white text-sm transition-all flex items-center justify-center gap-2"
                        style="background:linear-gradient(135deg,#059669,#10b981); box-shadow:0 4px 18px rgba(5,150,105,0.28);"
                        x-on:mouseover="if(!sending){ this.style.background='linear-gradient(135deg,#10b981,#34d399)'; this.style.boxShadow='0 6px 22px rgba(5,150,105,0.40)'; }"
                        x-on:mouseout="if(!sending){ this.style.background='linear-gradient(135deg,#059669,#10b981)'; this.style.boxShadow='0 4px 18px rgba(5,150,105,0.28)'; }">
                    <svg x-show="sending" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span x-show="!sending">Registrar Usuario</span>
                    <span x-show="sending">Registrando...</span>
                </button>
            </form>

            <div class="mt-3 text-center">
                <button type="button" @click="activeModal = 'login'"
                        class="text-sm hover:underline" style="color:#64748b; background:none; border:none; cursor:pointer;">
                    ← Volver a Iniciar Sesión
                </button>
            </div>
        </div>
    </div>
</div>
